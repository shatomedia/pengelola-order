#!/usr/bin/env python3
"""Run PHPUnit in a disposable, offline container; production paths are never mounted."""
import argparse
import contextlib
import time
import uuid
import xml.etree.ElementTree as ET
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
parser.add_argument('--database', choices=['sqlite', 'mariadb'], default='sqlite')
parser.add_argument('--database-image', default='mariadb:10.11')
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
@contextlib.contextmanager
def database_namespace():
    if args.database == 'sqlite':
        yield 'none'
        return
    db_image = subprocess.check_output(['docker', 'image', 'inspect', args.database_image,
                                        '--format', '{{.Id}}'], text=True).strip()
    name = 'sales-test-db-' + uuid.uuid4().hex[:12]
    try:
        # No network interfaces except loopback; no ports, volumes or host sockets.
        subprocess.run(['docker', 'run', '-d', '--name', name, '--network', 'none',
                        '--memory', '512m', '--cpus', '1', '--pids-limit', '256',
                        '--tmpfs', '/var/lib/mysql:rw,nosuid,nodev,size=256m',
                        '-e', 'MARIADB_ALLOW_EMPTY_ROOT_PASSWORD=1',
                        '-e', 'MARIADB_DATABASE=sales_disposable_test',
                        '-e', 'MARIADB_USER=sales_test',
                        '-e', 'MARIADB_PASSWORD=disposable-test-only', db_image],
                       check=True, stdout=subprocess.DEVNULL)
        for _ in range(60):
            ready = subprocess.run(['docker', 'exec', name, 'healthcheck.sh',
                                    '--connect', '--innodb_initialized'],
                                   stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
            if ready.returncode == 0:
                break
            time.sleep(1)
        else:
            raise RuntimeError('Disposable database did not become ready within 60 seconds.')
        yield 'container:' + name
    finally:
        subprocess.run(['docker', 'rm', '-fv', name], check=True, stdout=subprocess.DEVNULL)

with tempfile.TemporaryDirectory(prefix='order-tests-') as temp, database_namespace() as network:
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
    if args.database == 'mariadb':
        config = ET.parse(app/'phpunit.isolated.xml')
        values = {'DB_CONNECTION': 'mysql', 'DB_DATABASE': 'sales_disposable_test',
                  'DB_HOST': '127.0.0.1', 'DB_PORT': '3306', 'DB_USERNAME': 'sales_test',
                  'DB_PASSWORD': 'disposable-test-only'}
        php = config.getroot().find('php')
        for key, value in values.items():
            node = php.find(f"env[@name='{key}']")
            if node is None:
                node = ET.SubElement(php, 'env', name=key)
            node.set('value', value)
            node.set('force', 'true')
        config.write(app/'phpunit.isolated.xml')
    # No .env, database dump, host socket, live storage or production code mount.
    command=['docker','run','--rm','--network',network,'--read-only','--cap-drop','ALL',
             '--security-opt','no-new-privileges','--memory','1g','--cpus','1','--pids-limit','256',
             '--user',f'{os.getuid()}:{os.getgid()}', '--tmpfs','/tmp:rw,nosuid,nodev,size=128m',
             '--mount',f'type=bind,source={app},target=/app','--workdir','/app',
             '--entrypoint','php',image,'vendor/bin/phpunit','--configuration','phpunit.isolated.xml']
    if args.filter:
        command += ['--filter',args.filter]
    print(f'Isolated test container; temporary copy; {args.database}; no external network.', flush=True)
    result=subprocess.run(command)
    raise SystemExit(result.returncode)
