<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'province_id'))    $table->string('province_id')->nullable()->after('phone');
            if (!Schema::hasColumn('users', 'province_name'))  $table->string('province_name')->nullable()->after('province_id');
            if (!Schema::hasColumn('users', 'city_id'))        $table->string('city_id')->nullable()->after('province_name');
            if (!Schema::hasColumn('users', 'city_name'))      $table->string('city_name')->nullable()->after('city_id');
            if (!Schema::hasColumn('users', 'district_id'))    $table->string('district_id')->nullable()->after('city_name');
            if (!Schema::hasColumn('users', 'district_name'))  $table->string('district_name')->nullable()->after('district_id');
            if (!Schema::hasColumn('users', 'village_id'))     $table->string('village_id')->nullable()->after('district_name');
            if (!Schema::hasColumn('users', 'village_name'))   $table->string('village_name')->nullable()->after('village_id');
            if (!Schema::hasColumn('users', 'address_detail')) $table->text('address_detail')->nullable()->after('village_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                'province_id', 'province_name', 'city_id', 'city_name',
                'district_id', 'district_name', 'village_id', 'village_name',
                'address_detail',
            ], fn($col) => Schema::hasColumn('users', $col)));
        });
    }
};
