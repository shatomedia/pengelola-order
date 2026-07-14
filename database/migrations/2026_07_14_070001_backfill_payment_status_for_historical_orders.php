<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Payment status/jumlah_dibayar were introduced 2026-07-14, so every order
     * before that date defaulted to "Belum Bayar" / Rp 0, even ones long since
     * delivered or cancelled - inflating the dashboard's piutang total with
     * debt that was, in reality, settled or moot. Mark historical orders that
     * are no longer active (Dikirim/Diambil/Batal) as fully paid, so piutang
     * tracking effectively starts fresh from today for orders still in
     * progress (Pending/Diproses) and for anything created from now on.
     */
    public function up(): void
    {
        DB::table('orders')
            ->where('tgl_order', '<', '2026-07-14')
            ->whereNotIn('status', ['Pending', 'Diproses'])
            ->update([
                'payment_status' => 'Lunas',
                'jumlah_dibayar' => DB::raw('total_harga_jual'),
            ]);
    }

    public function down(): void
    {
        DB::table('orders')
            ->where('tgl_order', '<', '2026-07-14')
            ->whereNotIn('status', ['Pending', 'Diproses'])
            ->update([
                'payment_status' => 'Belum Bayar',
                'jumlah_dibayar' => 0,
            ]);
    }
};
