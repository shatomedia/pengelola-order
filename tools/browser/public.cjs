const assert=require('node:assert/strict');
const {chromium}=require('/tmp/sales-browser-audit/node_modules/playwright');
(async()=>{
const browser=await chromium.launch({headless:true,args:['--no-sandbox','--disable-dev-shm-usage']});
try {
for(const [name,width,height] of [['desktop',1365,900],['mobile',390,844]]){
 const page=await browser.newPage({viewport:{width,height}});const errors=[];page.on('pageerror',e=>errors.push(e.message));
 const response=await page.goto('https://sales.shatomedia.com/login',{waitUntil:'networkidle',timeout:45000});
 assert.equal(response.status(),200);
 await page.screenshot({path:`/tmp/sales-browser-audit/login-${name}.png`,fullPage:true,timeout:60000});
 console.log(JSON.stringify({viewport:name,status:response.status(),emailVisible:await page.locator('[name=email]').isVisible(),passwordVisible:await page.locator('[name=password]').isVisible(),buttonVisible:await page.getByRole('button',{name:'Sign in'}).isVisible(),overflow:await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth),errors}));
 await page.goto('https://sales.shatomedia.com/order',{waitUntil:'domcontentloaded',timeout:45000});console.log('GUEST_REDIRECT',name,new URL(page.url()).pathname);
 await page.close();
}
}finally{await browser.close();}
})().catch(e=>{console.error(e.message);process.exitCode=1});
