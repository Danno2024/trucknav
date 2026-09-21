<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('origin_address');
            $table->decimal('origin_lat', 10, 7);
            $table->decimal('origin_lng', 10, 7);
            $table->string('destination_address');
            $table->decimal('destination_lat', 10, 7);
            $table->decimal('destination_lng', 10, 7);
            $table->json('waypoints')->nullable();
            $table->string('vehicle_type')->default('truck');
            $table->integer('vehicle_weight_kg')->nullable();
            $table->decimal('vehicle_height_m', 4, 1)->nullable();
            $table->decimal('vehicle_width_m', 4, 1)->nullable();
            $table->decimal('vehicle_length_m', 5, 1)->nullable();
            $table->decimal('total_distance_km', 10, 2)->nullable();
            $table->integer('total_duration_minutes')->nullable();
            $table->json('route_geometry')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_routes');
    }
};
