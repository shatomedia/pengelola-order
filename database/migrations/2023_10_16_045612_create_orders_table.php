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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('nama_pembeli');
            $table->string('alamat');
            $table->string('no_hp');
            $table->bigInteger('produk_id')->unsigned();
            $table->string('order_via');
            $table->date('tgl_order');
            $table->date('tgl_kirim');
            $table->string('title');
            $table->string('background');
            $table->string('request');
            $table->string('keterangan');
            $table->timestamps();

            $table->foreign('produk_id')->references('id')->on('products')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
