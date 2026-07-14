<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardPiutangTest extends TestCase
{
    use RefreshDatabase;

    protected function makeOrder(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'status' => 'Pending',
            'payment_status' => 'DP',
            'nama_pembeli' => 'Test Pembeli',
            'alamat' => 'Jl. Contoh',
            'no_hp' => '08' . random_int(1000000000, 1999999999),
            'order_via' => 'WhatsApp',
            'tgl_order' => now()->format('Y-m-d'),
            'tgl_kirim' => now()->addDays(2)->format('Y-m-d'),
            'title' => 'Judul',
            'keterangan' => 'Keterangan',
            'total_qty' => 1,
            'total_harga_jual' => 100000,
            'jumlah_dibayar' => 0,
        ], $overrides));
    }

    public function test_overpaid_order_does_not_reduce_other_orders_outstanding_balance(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        // Genuinely owes 100,000
        $this->makeOrder(['total_harga_jual' => 100000, 'jumlah_dibayar' => 0]);

        // Overpaid by 50,000 (jumlah_dibayar > total_harga_jual) - must not offset the order above
        $this->makeOrder(['total_harga_jual' => 100000, 'jumlah_dibayar' => 150000]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        // Without the GREATEST() clip, piutang would be 100000 + (100000-150000) = 50000.
        // With the fix it must be exactly the genuinely-owed 100000.
        $response->assertViewHas('piutang', 100000);
    }
}
