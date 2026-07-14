<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_create_update_delete_are_logged(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $order = Order::create([
            'status' => 'Pending',
            'payment_status' => 'Belum Bayar',
            'nama_pembeli' => 'Fajar',
            'alamat' => 'Jl. Contoh',
            'no_hp' => '081277776666',
            'order_via' => 'WhatsApp',
            'tgl_order' => now()->format('Y-m-d'),
            'tgl_kirim' => now()->addDays(2)->format('Y-m-d'),
            'title' => 'Judul',
            'keterangan' => 'Keterangan',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'model_type' => Order::class,
            'model_id' => $order->id,
            'action' => 'created',
            'user_id' => $user->id,
        ]);

        $order->update(['status' => 'Diproses']);

        $this->assertDatabaseHas('activity_logs', [
            'model_type' => Order::class,
            'model_id' => $order->id,
            'action' => 'updated',
        ]);

        $order->delete();

        $this->assertDatabaseHas('activity_logs', [
            'model_type' => Order::class,
            'model_id' => $order->id,
            'action' => 'deleted',
        ]);
    }

    public function test_product_changes_are_logged(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = ProductCategory::create(['nama_kategori' => 'Kategori']);
        $product = Product::create([
            'kode_produk' => 'prod-log',
            'nama' => 'Produk Log',
            'kategori_id' => $category->id,
            'harga' => 10000,
            'deskripsi' => 'Deskripsi',
            'stok' => 5,
            'satuan' => 'pcs',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'model_type' => Product::class,
            'model_id' => $product->id,
            'action' => 'created',
        ]);
    }
}
