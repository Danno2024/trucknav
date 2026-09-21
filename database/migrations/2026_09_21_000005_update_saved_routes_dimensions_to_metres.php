<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saved_routes', function (Blueprint $table) {
            $table->decimal('vehicle_height_m', 4, 1)->nullable()->change();
            $table->decimal('vehicle_width_m', 4, 1)->nullable()->change();
            $table->decimal('vehicle_length_m', 5, 1)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('saved_routes', function (Blueprint $table) {
            $table->integer('vehicle_height_cm')->nullable()->change();
            $table->integer('vehicle_width_cm')->nullable()->change();
            $table->integer('vehicle_length_cm')->nullable()->change();
        });
    }
};
