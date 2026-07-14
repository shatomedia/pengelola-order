<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function makeOrder(): Order
    {
        return Order::create([
            'status' => 'Pending',
            'payment_status' => 'Belum Bayar',
            'nama_pembeli' => 'Eka',
            'alamat' => 'Jl. Contoh',
            'no_hp' => '081299998888',
            'order_via' => 'WhatsApp',
            'tgl_order' => now()->format('Y-m-d'),
            'tgl_kirim' => now()->addDays(2)->format('Y-m-d'),
            'title' => 'Judul',
            'keterangan' => 'Keterangan',
            'total_qty' => 1,
            'total_harga_jual' => 50000,
        ]);
    }

    public function test_guest_is_redirected_away_from_invoice(): void
    {
        $order = $this->makeOrder();

        $response = $this->get(route('order.invoice', $order->id));

        $response->assertRedirect(route('login.index'));
    }

    public function test_authorized_user_can_view_both_invoice_formats(): void
    {
        $order = $this->makeOrder();
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user)
            ->get(route('order.invoice', $order->id))
            ->assertOk()
            ->assertSee($order->no_faktur);

        $this->actingAs($user)
            ->get(route('order.invoice.thermal', $order->id))
            ->assertOk()
            ->assertSee($order->no_faktur);
    }

    public function test_user_without_order_permission_is_forbidden(): void
    {
        $order = $this->makeOrder();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('order.invoice', $order->id))
            ->assertForbidden();
    }
}
