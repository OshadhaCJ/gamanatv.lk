<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('city_id')->constrained()->restrictOnDelete();

            $table->string('number')->unique();
            $table->string('name')->nullable();

            $table->foreignId('owner_id')->nullable()->constrained('owners')->nullOnDelete();

            $table->string('conductor_name')->nullable();
            $table->string('conductor_phone')->nullable();

            // Fixed time or rotation time
            $table->enum('schedule_type', ['fixed', 'rotation'])->default('fixed');
            $table->time('fixed_time')->nullable();
            $table->text('rotation_description')->nullable();

            // Images
            $table->string('front_image')->nullable();
            $table->string('side_image')->nullable();
            $table->string('tv_front_image')->nullable();
            $table->string('tv_back_image')->nullable();

            // Route operations
            $table->unsignedSmallInteger('routes_per_day')->nullable();
            $table->unsignedSmallInteger('route_duration_minutes')->nullable();

            // Starting place: fixed -> pick a route; not fixed -> no route
            $table->boolean('starting_place_fixed')->default(true);
            $table->foreignId('route_id')->nullable()->constrained('routes')->nullOnDelete();

            // TV details
            $table->string('tv_brand')->nullable();
            $table->string('tv_size')->nullable();
            $table->enum('input_type', ['tv', 'set'])->nullable();

            $table->string('holiday_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
