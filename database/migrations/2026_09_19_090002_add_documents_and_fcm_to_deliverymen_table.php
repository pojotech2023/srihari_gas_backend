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
        Schema::table('deliverymen', function (Blueprint $table) {
            if (!Schema::hasColumn('deliverymen', 'driving_licence')) {
                $table->string('driving_licence')->nullable()->after('pan_card');
            }
            if (!Schema::hasColumn('deliverymen', 'insurance')) {
                $table->string('insurance')->nullable()->after('driving_licence');
            }
            if (!Schema::hasColumn('deliverymen', 'fcm_token')) {
                $table->text('fcm_token')->nullable()->after('insurance');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliverymen', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('deliverymen', 'driving_licence')) {
                $columns[] = 'driving_licence';
            }
            if (Schema::hasColumn('deliverymen', 'insurance')) {
                $columns[] = 'insurance';
            }
            if (Schema::hasColumn('deliverymen', 'fcm_token')) {
                $columns[] = 'fcm_token';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
