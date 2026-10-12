<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Raw status pings sent by the in-bus device (TV on/play/pause/off),
     * identified by the Processing Unit (deviceID) and Storage Unit (penID).
     * Kept even when the device/bus can't be matched, so nothing is lost.
     */
    public function up(): void
    {
        Schema::create('device_pings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('processing_unit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('storage_unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('device_id');
            $table->string('pen_id')->nullable();
            $table->string('status');
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['bus_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_pings');
    }
};
