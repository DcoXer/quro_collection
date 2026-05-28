<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // State saat ini (setelah beberapa partial run):
        // - products.deleted_at     : sudah ada ✓
        // - products.category_id    : nullable ✓, FK nullOnDelete sudah ada ✓
        // - order_items.product_id  : masih NOT NULL, FK sudah terdrop — ini yang perlu diselesaikan

        // Buat order_items.product_id nullable lalu tambah FK nullOnDelete
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }
};
