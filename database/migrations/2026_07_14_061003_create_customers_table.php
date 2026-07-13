<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('no_hp')->unique();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('id')->constrained('customers')->nullOnDelete();
        });

        $this->backfillCustomersFromExistingOrders();
    }

    /**
     * Historical orders (pre-dating the customers table) stored buyer info as free
     * text per order. Dedupe by phone number into a customer record and link every
     * matching order back to it, so "Riwayat Pelanggan" works for existing data too.
     */
    private function backfillCustomersFromExistingOrders(): void
    {
        $buyers = DB::table('orders')
            ->select('no_hp', 'nama_pembeli', 'alamat')
            ->whereNotNull('no_hp')
            ->orderBy('id')
            ->get()
            ->unique('no_hp');

        foreach ($buyers as $buyer) {
            $customerId = DB::table('customers')->insertGetId([
                'nama' => $buyer->nama_pembeli,
                'alamat' => $buyer->alamat,
                'no_hp' => $buyer->no_hp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('orders')
                ->where('no_hp', $buyer->no_hp)
                ->update(['customer_id' => $customerId]);
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_id');
        });

        Schema::dropIfExists('customers');
    }
};
