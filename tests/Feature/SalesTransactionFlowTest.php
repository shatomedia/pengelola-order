<?php

namespace Tests\Feature;

use App\Models\{Order, Pemasukan, Pengeluaran, Product, ProductCategory, TransactionCategory, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SalesTransactionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_order_payment_finance_invoice_and_logout_flow(): void
    {
        $user = User::factory()->create(['password' => Hash::make('synthetic-test-only')]);
        $user->assignRole('admin');
        $this->get('/login')->assertOk();
        $this->from('/login')->post('/login/store', ['email' => $user->email, 'password' => 'incorrect'])
            ->assertRedirect('/login')->assertSessionHas('error');
        $this->assertGuest();
        $this->post('/login/store', ['email' => $user->email, 'password' => 'synthetic-test-only'])
            ->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        foreach (['/dashboard', '/order', '/order/create', '/pemasukan', '/pengeluaran', '/laporan-keuangan'] as $path) {
            $this->get($path)->assertOk();
        }

        $category = ProductCategory::firstOrFail();
        $product = Product::create(['kode_produk' => 'synthetic-flow', 'nama' => 'Synthetic product',
            'kategori_id' => $category->id, 'harga' => 25000, 'harga_modal' => 10000,
            'deskripsi' => 'Test', 'stok' => 10, 'satuan' => 'pcs']);
        $payload = ['status' => 'Pending', 'payment_status' => 'Belum Bayar', 'jumlah_dibayar' => 0,
            'nama_pembeli' => 'Synthetic flow', 'alamat' => 'Test', 'no_hp' => '081200009999',
            'order_via' => 'WhatsApp', 'tgl_order' => now()->toDateString(),
            'tgl_kirim' => now()->addDays(3)->toDateString(), 'title' => 'Test', 'background' => 'Test',
            'request' => 'Test', 'keterangan' => 'Test', 'produk_id' => [$product->id], 'qty' => [2]];
        $this->post(route('order.store'), $payload)->assertSessionHasNoErrors()->assertRedirect('/order');
        $order = Order::where('nama_pembeli', 'Synthetic flow')->firstOrFail();
        $this->assertEquals(50000, $order->total_harga_jual);
        $this->assertEquals(8, $product->fresh()->stok);
        $this->get('/dashboard')->assertOk()->assertViewHas('piutang', 50000);

        $payload['payment_status'] = 'DP';
        $payload['jumlah_dibayar'] = 20000;
        $this->from(route('order.edit', $order->id))->put(route('order.update', $order->id), $payload)
            ->assertSessionHasNoErrors()->assertRedirect(route('order.edit', $order->id));
        $this->assertEquals(20000, $order->fresh()->jumlah_dibayar);
        $this->get('/dashboard')->assertOk()->assertViewHas('piutang', 30000)
            ->assertViewHas('totalPemasukanBulanIni', 20000);
        $payload['payment_status'] = 'Lunas';
        $payload['jumlah_dibayar'] = 50000;
        $this->put(route('order.update', $order->id), $payload)->assertSessionHasNoErrors();
        $this->assertEquals('Lunas', $order->fresh()->payment_status);
        foreach (['order.invoice', 'order.invoice.thermal'] as $route) {
            $this->get(route($route, $order->id))->assertOk()->assertSee($order->no_faktur);
        }

        $incomeCategory = TransactionCategory::create(['nama_kategori' => 'Synthetic income', 'jenis' => 'pemasukan']);
        $expenseCategory = TransactionCategory::create(['nama_kategori' => 'Synthetic expense', 'jenis' => 'pengeluaran']);
        $this->post(route('pemasukan.store'), ['kategori_id' => $incomeCategory->id, 'sumber' => 'Synthetic',
            'jumlah' => 10000, 'tanggal' => now()->toDateString()])->assertSessionHasNoErrors()->assertRedirect('/pemasukan');
        $this->post(route('pengeluaran.store'), ['kategori_id' => $expenseCategory->id,
            'jumlah' => 15000, 'tanggal' => now()->toDateString()])->assertSessionHasNoErrors()->assertRedirect('/pengeluaran');
        $this->get('/dashboard')->assertOk()->assertViewHas('piutang', 0)
            ->assertViewHas('totalPemasukanBulanIni', 60000)
            ->assertViewHas('totalPengeluaranBulanIni', 15000)->assertViewHas('labaBersihBulanIni', 45000);
        $this->get('/laporan-keuangan')->assertOk();

        $income = Pemasukan::where('kategori_id', $incomeCategory->id)->firstOrFail();
        $expense = Pengeluaran::where('kategori_id', $expenseCategory->id)->firstOrFail();
        $this->delete(route('pemasukan.destroy', $income->id))->assertRedirect();
        $this->delete(route('pengeluaran.destroy', $expense->id))->assertRedirect();
        $this->delete(route('order.destroy', $order->id))->assertRedirect();
        $this->assertDatabaseMissing('pemasukans', ['id' => $income->id]);
        $this->assertDatabaseMissing('pengeluarans', ['id' => $expense->id]);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertEquals(10, $product->fresh()->stok);
        $this->get('/logout')->assertRedirect(route('login.index'));
        $this->assertGuest();
        $this->get('/dashboard')->assertRedirect(route('login.index'));
    }
}
