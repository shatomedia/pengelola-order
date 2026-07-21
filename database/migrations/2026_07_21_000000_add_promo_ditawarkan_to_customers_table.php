<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('promo_ditawarkan')->default(false)->after('no_hp');
            $table->timestamp('promo_ditawarkan_at')->nullable()->after('promo_ditawarkan');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['promo_ditawarkan', 'promo_ditawarkan_at']);
        });
    }
};
