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
        Schema::table('users', function (Blueprint $table) {
            $table->string('province_id')->nullable()->after('email');
            $table->string('province_name')->nullable()->after('province_id');
            $table->string('city_id')->nullable()->after('province_name');
            $table->string('city_name')->nullable()->after('city_id');
            $table->string('district_id')->nullable()->after('city_name');
            $table->string('district_name')->nullable()->after('district_id');
            $table->string('village_id')->nullable()->after('district_name');
            $table->string('village_name')->nullable()->after('village_id');
            $table->text('address_detail')->nullable()->after('village_name');
            $table->string('phone')->nullable()->after('address_detail');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('province_id')->nullable()->after('shipping_address');
            $table->string('city_id')->nullable()->after('province_id');
            $table->string('district_id')->nullable()->after('city_id');
            $table->string('village_id')->nullable()->after('district_id');
            $table->string('courier_service')->nullable()->after('village_id');
            $table->unsignedInteger('shipping_cost')->default(0)->after('courier_service');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_and_orders', function (Blueprint $table) {
            //
        });
    }
};
