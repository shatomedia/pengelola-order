#!/usr/bin/env python3
"""Run PHPUnit in a disposable, offline container; production paths are never mounted."""
import argparse
import json
import os
from pathlib import Path
import shutil
import subprocess
import tempfile

ROOT = Path(__file__).resolve().parents[1]
parser = argparse.ArgumentParser(description=__doc__)
parser.add_argument('--vendor', type=Path, default=(ROOT/'.test-deps/vendor' if (ROOT/'.test-deps/vendor').exists() else ROOT/'vendor'))
parser.add_argument('--image', default='nextcloud:29-apache')
parser.add_argument('--filter', help='PHPUnit test name filter')
args = parser.parse_args()
lock = json.loads((ROOT/'composer.lock').read_text())
installed = json.loads((args.vendor/'composer/installed.json').read_text())
expected = {x['name']:x['version'] for x in lock['packages']+lock.get('packages-dev',[])}
actual = {x['name']:x['version'] for x in installed['packages']}
if any(actual.get(k) != v for k,v in expected.items()):
    raise SystemExit('Vendor versions do not match composer.lock; install matching development dependencies first.')
# Resolve a local immutable image ID. Do not silently pull mutable tags.
image = subprocess.check_output(['docker','image','inspect',args.image,'--format','{{.Id}}'],text=True).strip()
with tempfile.TemporaryDirectory(prefix='order-tests-') as temp:
    app = Path(temp)
    for name in ['app','bootstrap','config','database','resources','routes','tests']:
        shutil.copytree(ROOT/name,app/name,ignore=shutil.ignore_patterns('.env*','cache','*.log','*.sql','*.zip'))
    subprocess.run(['cp','-a','--no-preserve=ownership','--reflink=auto',str(args.vendor.resolve()),str(app/'vendor')],check=True)
    for name in ['artisan','composer.json','composer.lock','phpunit.isolated.xml']:
        shutil.copy2(ROOT/name,app/name)
    for name in ['bootstrap/cache','storage/framework/cache/data','storage/framework/sessions','storage/framework/views','storage/logs','storage/app/public','public']:
        (app/name).mkdir(parents=True,exist_ok=True)
    manifest=ROOT/'public/build/manifest.json'
    if manifest.exists():
        (app/'public/build').mkdir(parents=True)
        shutil.copy2(manifest,app/'public/build/manifest.json')
    # No .env, database dump, host socket, live storage or production code mount.
    command=['docker','run','--rm','--network','none','--read-only','--cap-drop','ALL',
             '--security-opt','no-new-privileges','--memory','1g','--cpus','1','--pids-limit','256',
             '--user',f'{os.getuid()}:{os.getgid()}', '--tmpfs','/tmp:rw,nosuid,nodev,size=128m',
             '--mount',f'type=bind,source={app},target=/app','--workdir','/app',
             '--entrypoint','php',image,'vendor/bin/phpunit','--configuration','phpunit.isolated.xml']
    if args.filter:
        command += ['--filter',args.filter]
    print('Offline test container; temporary copy; SQLite :memory:; 1 CPU / 1 GiB.',flush=True)
    result=subprocess.run(command)
    raise SystemExit(result.returncode)
