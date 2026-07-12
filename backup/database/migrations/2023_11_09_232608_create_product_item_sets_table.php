<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_item_sets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_item_set');
            $table->integer('total_transaksi');
            $table->string('persentase');
            $table->enum('kategori', ['1_itemset','2_itemset','3_itemset','4_itemset']);
            $table->year('tahun');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_item_sets');
    }
};
