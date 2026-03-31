<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Make price nullable (null = pakai base product price)
        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->nullable()->change();
        });

        // 2. Buat variant S/M/L/XL untuk setiap produk yang belum punya
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            foreach (['S', 'M', 'L', 'XL'] as $size) {
                $exists = DB::table('product_variants')
                    ->where('product_id', $product->id)
                    ->where('size', $size)
                    ->exists();

                if (! $exists) {
                    DB::table('product_variants')->insert([
                        'product_id' => $product->id,
                        'size'       => $size,
                        'price'      => null, // inherit base price
                        'stock'      => $product->stock,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 3. Reset global stock ke 0 (sudah dipindah ke variants)
        DB::table('products')->update(['stock' => 0]);
    }

    public function down(): void
    {
        // Kembalikan price NOT NULL
        Schema::table('product_variants', function (Blueprint $table) {
            DB::table('product_variants')->whereNull('price')->delete();
            $table->unsignedBigInteger('price')->nullable(false)->change();
        });
    }
};
