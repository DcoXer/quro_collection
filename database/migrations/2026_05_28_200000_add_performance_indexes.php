<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // product_variants: query WHERE product_id AND size sering dipanggil di checkout,
        // cart, dan webhook Midtrans — tanpa index ini setiap query full table scan
        Schema::table('product_variants', function (Blueprint $table) {
            $table->index(['product_id', 'size'], 'pv_product_size_idx');
        });

        // product_reviews: aggregasi rating + filter is_approved dipanggil di setiap
        // halaman produk — composite index mempercepat withAvg() dan withCount()
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->index(['product_id', 'is_approved'], 'pr_product_approved_idx');
        });

        // orders: query latest() per user dipanggil di halaman riwayat order —
        // composite index (user_id, created_at) menggantikan full scan + sort
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'orders_user_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex('pv_product_size_idx');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropIndex('pr_product_approved_idx');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_user_created_idx');
        });
    }
};
