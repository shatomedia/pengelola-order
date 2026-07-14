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
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->foreignId('pengeluaran_berulang_id')->nullable()->after('kategori_id')->constrained('pengeluaran_berulangs')->restrictOnDelete();
            $table->enum('status', ['draft', 'terkonfirmasi'])->default('terkonfirmasi')->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pengeluaran_berulang_id');
            $table->dropColumn('status');
        });
    }
};
