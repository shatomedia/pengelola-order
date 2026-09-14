const assert=require('node:assert/strict');
const {chromium}=require('/tmp/sales-browser-audit/node_modules/playwright');const fs=require('fs');
(async()=>{
 const base='http://'+fs.readFileSync('/tmp/sales-browser-audit/address.txt','utf8').trim();
 const credentials=JSON.parse(fs.readFileSync('/tmp/sales-browser-audit/credentials.json'));
 const browser=await chromium.launch({headless:true,args:['--no-sandbox','--disable-dev-shm-usage']});
 const results=[];
 try {
 for(const [name,width,height] of [['desktop',1365,900],['mobile',390,844]]){
  const context=await browser.newContext({viewport:{width,height}});const page=await context.newPage();
  page.setDefaultTimeout(30000);const errors=[];const failures=[];
  page.on('pageerror',e=>errors.push(e.message));
  page.on('response',r=>{if(r.status()>=400) failures.push({url:r.url().replace(base,''),status:r.status()});});
  await page.goto(base+'/login',{waitUntil:'networkidle'});
  await page.locator('[name=email]').fill(credentials.email);await page.locator('[name=password]').fill(credentials.password);
  await Promise.all([page.waitForURL('**/dashboard'),page.getByRole('button',{name:'Sign in'}).click()]);
  results.push({viewport:name,login:true});
  for(const path of ['/dashboard','/order','/order/create','/pemasukan','/pengeluaran','/laporan-keuangan','/order/1/invoice','/order/1/invoice/thermal']){
   const response=await page.goto(base+path,{waitUntil:'networkidle'});
   results.push({viewport:name,path,status:response.status(),overflow:await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth),productInputs:path==='/order/create'?await page.locator('[name="produk_id[]"]').count():undefined});
   assert.equal(response.status(),200);
 await page.screenshot({path:'/tmp/sales-browser-audit/'+name+path.replaceAll('/','-')+'.png',fullPage:true,timeout:60000});
   if(path==='/order/1/invoice') await page.pdf({path:'/tmp/sales-browser-audit/invoice-'+name+'.pdf',format:'A4'});
  }
  await page.goto(base+'/pemasukan/create',{waitUntil:'networkidle'});
  await page.locator('[name=kategori_id]').selectOption({label:'Browser income'});
  await page.locator('[name=sumber]').fill('Browser submission '+name);
  await page.locator('[name=jumlah]').fill('10000');
  await page.locator('[name=tanggal]').fill('2026-09-14');
  await page.locator('[name=keterangan]').fill('Synthetic browser test');
  await page.screenshot({path:'/tmp/sales-browser-audit/'+name+'-income-before.png',fullPage:true});
  const posts=[];page.on('response',r=>{if(r.request().method()==='POST')posts.push({url:r.url().replace(base,''),status:r.status()});});
  try { await Promise.all([page.waitForURL('**/pemasukan'),page.getByRole('button',{name:'Submit'}).click()]); }
  catch(e) { results.push({viewport:name,submitError:e.message,posts,url:page.url().replace(base,''),alerts:await page.locator('.alert').allTextContents(),invalid:await page.locator('input:invalid,select:invalid').evaluateAll(xs=>xs.map(x=>({name:x.name,value:x.value,message:x.validationMessage})))}); }
  await page.screenshot({path:'/tmp/sales-browser-audit/'+name+'-income-after.png',fullPage:true});
  results.push({viewport:name,incomeSubmit:await page.getByText('Browser submission '+name,{exact:true}).count()>0});
  await page.goto(base+'/logout',{waitUntil:'domcontentloaded'});
  results.push({viewport:name,logout:new URL(page.url()).pathname==='/login',errors,failures});
  await context.close();
 }
 assert.ok(results.filter(r=>r.incomeSubmit===true).length===2,'Income submit must succeed on both viewports');
 }finally{fs.writeFileSync('/tmp/sales-browser-audit/results.json',JSON.stringify(results,null,2));console.log(JSON.stringify(results));await browser.close();}
})().catch(e=>{console.error(e.message);process.exitCode=1;});
