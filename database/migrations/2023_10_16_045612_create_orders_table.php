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
            $table->integer('no_urut')->unique();
            $table->string('no_faktur');
            $table->enum('status', ['Pending', 'Diproses', 'Dikirim', 'Batal', 'Diambil']);
            $table->string('nama_pembeli');
            $table->string('alamat');
            $table->string('no_hp');
            $table->string('order_via');
            $table->date('tgl_order');
            $table->date('tgl_kirim')->nullable();
            $table->longText('title');
            $table->string('background')->nullable();
            $table->longText('request')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('total_qty')->nullable();
            $table->integer('total_harga_jual')->nullable();
            $table->timestamps();
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
