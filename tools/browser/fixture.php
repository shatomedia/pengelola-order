<?php
if (getenv('APP_ENV') !== 'testing' || getenv('DB_CONNECTION') !== 'sqlite' || getenv('DB_DATABASE') !== '/app/database/browser.sqlite' || file_exists('/app/.env')) { exit(2); }
require '/app/vendor/autoload.php';
$app = require '/app/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force'=>true]);
Illuminate\Support\Facades\Artisan::call('db:seed', ['--class'=>Database\Seeders\PermissionSeeder::class,'--force'=>true]);
$role=Spatie\Permission\Models\Role::create(['name'=>'admin']);
$role->syncPermissions(Spatie\Permission\Models\Permission::all());
$user=App\Models\User::factory()->create(['email'=>'browser-test@example.invalid','password'=>Illuminate\Support\Facades\Hash::make(getenv('TEST_PASSWORD'))]);
$user->assignRole($role);
App\Models\TransactionCategory::create(['nama_kategori'=>'Browser income','jenis'=>'pemasukan']);
App\Models\TransactionCategory::create(['nama_kategori'=>'Browser expense','jenis'=>'pengeluaran']);
$category=App\Models\ProductCategory::create(['nama_kategori'=>'Browser category']);
App\Models\Product::create(['kode_produk'=>'browser-item','nama'=>'Browser product','kategori_id'=>$category->id,'harga'=>25000,'harga_modal'=>10000,'deskripsi'=>'Synthetic','stok'=>10,'satuan'=>'pcs']);
App\Models\Order::create(['status'=>'Pending','payment_status'=>'DP','nama_pembeli'=>'Synthetic browser','alamat'=>'Test','no_hp'=>'081200009999','order_via'=>'WhatsApp','tgl_order'=>date('Y-m-d'),'tgl_kirim'=>date('Y-m-d',time()+86400),'title'=>'Test','keterangan'=>'Synthetic','total_qty'=>2,'total_harga_jual'=>50000,'jumlah_dibayar'=>20000]);
echo "SYNTHETIC_FIXTURE_READY\n";
