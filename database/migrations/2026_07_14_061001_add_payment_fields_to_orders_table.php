<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_status', ['Belum Bayar', 'DP', 'Lunas'])->default('Belum Bayar')->after('status');
            $table->integer('jumlah_dibayar')->default(0)->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'jumlah_dibayar']);
        });
    }
};
