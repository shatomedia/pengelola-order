<?php

namespace Tests\Feature;

use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderDeletionRestocksTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_an_order_restores_stock_using_qty_not_price(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $category = ProductCategory::create(['nama_kategori' => 'Kategori']);
        $product = Product::create([
            'kode_produk' => 'prod-restock',
            'nama' => 'Produk Restock',
            'kategori_id' => $category->id,
            'harga' => 50000,
            'harga_modal' => 30000,
            'deskripsi' => 'Deskripsi',
            'stok' => 10,
            'satuan' => 'pcs',
        ]);

        $order = Order::create([
            'status' => 'Pending',
            'payment_status' => 'Belum Bayar',
            'nama_pembeli' => 'Dewi',
            'alamat' => 'Jl. Contoh',
            'no_hp' => '081200001111',
            'order_via' => 'WhatsApp',
            'tgl_order' => now()->format('Y-m-d'),
            'tgl_kirim' => now()->addDays(2)->format('Y-m-d'),
            'title' => 'Judul',
            'keterangan' => 'Keterangan',
        ]);

        $qty = 3;
        DetailOrder::create([
            'order_id' => $order->id,
            'produk_id' => $product->id,
            'qty' => $qty,
            'sub_total' => $qty * $product->harga, // 150000 - deliberately far from qty, to catch the old bug
        ]);

        $product->update(['stok' => $product->stok - $qty]);
        $this->assertEquals(7, $product->fresh()->stok);

        $this->actingAs($user)->delete(route('order.destroy', $order->id));

        // Regression test: restock must add back the qty (3), not the sub_total (150000).
        $this->assertEquals(10, $product->fresh()->stok);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
