<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // FULLTEXT index pada products.name — menggantikan LIKE '%term%' yang full table scan.
        // Butuh MySQL InnoDB (default Laravel). Aktifkan dengan whereFullText() di query.
        DB::statement('ALTER TABLE products ADD FULLTEXT INDEX ft_products_name (name)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE products DROP INDEX ft_products_name');
    }
};
