<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemasukans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('transaction_categories')->restrictOnDelete();
            $table->string('sumber');
            $table->foreignId('order_id')->nullable()->constrained('orders')->restrictOnDelete();
            $table->integer('jumlah');
            $table->date('tanggal');
            $table->longText('keterangan')->nullable();
            $table->string('bukti')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukans');
    }
};
