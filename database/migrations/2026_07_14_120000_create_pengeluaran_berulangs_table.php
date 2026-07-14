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
        Schema::create('pengeluaran_berulangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('transaction_categories')->restrictOnDelete();
            $table->string('nama');
            $table->integer('jumlah_estimasi');
            $table->tinyInteger('tanggal_jatuh_tempo');
            $table->boolean('aktif')->default(true);
            $table->longText('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_berulangs');
    }
};
