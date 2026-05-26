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
        // Index orders untuk query user + status yang sering dipakai
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
            $table->index(['user_id', 'status']);
        });

        // Unique constraint di voucher_usages — mencegah race condition duplikat pada DB level
        Schema::table('voucher_usages', function (Blueprint $table) {
            $table->unique(['voucher_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'status']);
        });

        Schema::table('voucher_usages', function (Blueprint $table) {
            $table->dropUnique(['voucher_id', 'user_id']);
        });
    }
};
