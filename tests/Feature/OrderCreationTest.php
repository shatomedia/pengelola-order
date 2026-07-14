<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function asAdmin(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    protected function makeProduct(array $overrides = []): Product
    {
        $category = ProductCategory::first() ?? ProductCategory::create([
            'nama_kategori' => 'Test Kategori',
        ]);

        return Product::create(array_merge([
            'kode_produk' => 'prod-' . uniqid(),
            'nama' => 'Produk Test',
            'kategori_id' => $category->id,
            'harga' => 10000,
            'harga_modal' => 6000,
            'deskripsi' => 'Deskripsi',
            'stok' => 50,
            'satuan' => 'pcs',
        ], $overrides));
    }

    public function test_creating_an_order_with_multiple_products_deducts_stock_and_totals_correctly(): void
    {
        $productA = $this->makeProduct(['nama' => 'Produk A', 'harga' => 10000, 'stok' => 50]);
        $productB = $this->makeProduct(['nama' => 'Produk B', 'harga' => 25000, 'stok' => 20]);

        $response = $this->actingAs($this->asAdmin())->post(route('order.store'), [
            'status' => 'Pending',
            'payment_status' => 'Belum Bayar',
            'nama_pembeli' => 'Budi',
            'alamat' => 'Jl. Contoh',
            'no_hp' => '081234567890',
            'order_via' => 'WhatsApp',
            'tgl_order' => now()->format('Y-m-d'),
            'tgl_kirim' => now()->addDays(3)->format('Y-m-d'),
            'title' => 'Judul',
            'background' => 'Background',
            'request' => 'Request',
            'keterangan' => 'Keterangan',
            'produk_id' => [$productA->id, $productB->id],
            'qty' => [2, 1],
        ]);

        $response->assertRedirect('/order');

        $this->assertDatabaseHas('orders', [
            'nama_pembeli' => 'Budi',
            'total_qty' => 3,
            'total_harga_jual' => 2 * 10000 + 1 * 25000,
        ]);

        $productA->refresh();
        $productB->refresh();
        $this->assertEquals(48, $productA->stok);
        $this->assertEquals(19, $productB->stok);

        $this->assertDatabaseHas('customers', [
            'no_hp' => '081234567890',
            'nama' => 'Budi',
        ]);
    }

    public function test_order_creation_fails_when_stock_is_insufficient(): void
    {
        $product = $this->makeProduct(['stok' => 1]);

        $this->actingAs($this->asAdmin())->post(route('order.store'), [
            'status' => 'Pending',
            'payment_status' => 'Belum Bayar',
            'nama_pembeli' => 'Ani',
            'alamat' => 'Jl. Contoh',
            'no_hp' => '081200000000',
            'order_via' => 'WhatsApp',
            'tgl_order' => now()->format('Y-m-d'),
            'tgl_kirim' => now()->addDays(3)->format('Y-m-d'),
            'title' => 'Judul',
            'background' => 'Background',
            'request' => 'Request',
            'keterangan' => 'Keterangan',
            'produk_id' => [$product->id],
            'qty' => [5],
        ]);

        $this->assertDatabaseMissing('orders', ['nama_pembeli' => 'Ani']);
        $product->refresh();
        $this->assertEquals(1, $product->stok);
    }

    public function test_order_creation_rejects_duplicate_products_in_the_same_submission(): void
    {
        $product = $this->makeProduct(['stok' => 50]);

        $response = $this->actingAs($this->asAdmin())->post(route('order.store'), [
            'status' => 'Pending',
            'payment_status' => 'Belum Bayar',
            'nama_pembeli' => 'Citra',
            'alamat' => 'Jl. Contoh',
            'no_hp' => '081211111111',
            'order_via' => 'WhatsApp',
            'tgl_order' => now()->format('Y-m-d'),
            'tgl_kirim' => now()->addDays(3)->format('Y-m-d'),
            'title' => 'Judul',
            'background' => 'Background',
            'request' => 'Request',
            'keterangan' => 'Keterangan',
            'produk_id' => [$product->id, $product->id],
            'qty' => [1, 1],
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('orders', ['nama_pembeli' => 'Citra']);
    }
}
