from pathlib import Path
import shutil,subprocess,json,os,secrets
if os.environ.get('GITHUB_ACTIONS') != 'true':
    raise SystemExit('Browser setup runs on GitHub CI only; do not allocate browser fixtures on the HomeLab.')
root=Path(__file__).resolve().parents[2]
base=Path('/tmp/sales-browser-audit');app=base/'app';app.mkdir(exist_ok=True)
for name in ['app','bootstrap','config','database','resources','routes']:
    shutil.copytree(root/name,app/name,dirs_exist_ok=True,ignore=shutil.ignore_patterns('.env*','cache','*.log','*.sql','*.sqlite','*.zip'))
for name in ['css','custom','fonts','img','js','vendor','build']:
    shutil.copytree(root/'public'/name,app/'public'/name,dirs_exist_ok=True)
for name in ['artisan','composer.json','composer.lock','public/index.php']:
    shutil.copy2(root/name,app/name)
subprocess.run(['cp','-a','--no-preserve=ownership','--reflink=auto',str(root/'.test-deps/vendor'),str(app/'vendor')],check=True)
for name in ['bootstrap/cache','storage/framework/cache/data','storage/framework/sessions','storage/framework/views','storage/logs','storage/app/public']:
    (app/name).mkdir(parents=True,exist_ok=True)
(app/'database/browser.sqlite').touch()
password=secrets.token_hex(20)
(base/'credentials.json').write_text(json.dumps({'email':'browser-test@example.invalid','password':password}));os.chmod(base/'credentials.json',0o600)
env={'APP_ENV':'testing','APP_DEBUG':'false','APP_KEY':'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=','DB_CONNECTION':'sqlite','DB_DATABASE':'/app/database/browser.sqlite','DB_URL':'','DATABASE_URL':'','CACHE_DRIVER':'array','SESSION_DRIVER':'file','MAIL_MAILER':'array','QUEUE_CONNECTION':'sync','LOG_CHANNEL':'stderr','APP_CONFIG_CACHE':'/tmp/browser-config.php','TEST_PASSWORD':password}
(base/'test.env').write_text('\n'.join(k+'='+v for k,v in env.items())+'\n');os.chmod(base/'test.env',0o600)

shutil.copy2(root/'tools/browser/fixture.php',app/'browser-init.php')
