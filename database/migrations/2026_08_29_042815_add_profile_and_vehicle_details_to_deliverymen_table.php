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
            $table->string('rc_book')->nullable();
            $table->string('vehicle_image')->nullable();
            $table->string('photo')->nullable();
            $table->string('aadhar_card')->nullable();
            $table->string('pan_card')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliverymen', function (Blueprint $table) {
            $table->dropColumn(['rc_book', 'vehicle_image', 'photo', 'aadhar_card', 'pan_card']);
        });
    }
};
