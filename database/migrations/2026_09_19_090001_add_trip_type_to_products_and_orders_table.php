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
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'trip_type')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('trip_type', 20)->default('two_way')->after('price');
            });
        }

        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'trip_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('trip_type', 20)->default('two_way')->after('delivery_charge');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'trip_type')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('trip_type');
            });
        }

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'trip_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('trip_type');
            });
        }
    }
};
