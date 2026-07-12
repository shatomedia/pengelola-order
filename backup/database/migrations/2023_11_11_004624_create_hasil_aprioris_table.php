<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hasil_aprioris', function (Blueprint $table) {
            $table->id();
            $table->integer('no_urut');
            $table->string('kode_pengujian');
            $table->unsignedBigInteger('penguji');
            $table->string('nama_produk');
            $table->string('persentase_hasil_support');
            $table->string('persentase_hasil_confidence');
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('penguji')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_aprioris');
    }
};
