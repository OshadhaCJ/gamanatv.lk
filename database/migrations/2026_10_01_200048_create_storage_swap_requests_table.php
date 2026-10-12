<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A coordinator logs "I'm swapping this bus's storage device for
     * that one" here, then the webhook confirms it automatically once a
     * ping actually arrives from the bus's processing unit reporting the
     * new pen ID. If a ping arrives reporting neither the old nor the
     * new pen ID while a swap is pending, it's marked failed/faulty.
     */
    public function up(): void
    {
        Schema::create('storage_swap_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->cascadeOnDelete();
            $table->foreignId('old_storage_unit_id')->nullable()->constrained('storage_units')->nullOnDelete();
            $table->foreignId('new_storage_unit_id')->constrained('storage_units')->cascadeOnDelete();
            $table->enum('status', ['pending', 'confirmed', 'failed'])->default('pending');
            $table->string('fault_reason')->nullable();
            $table->boolean('acknowledged')->default(false);
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_swap_requests');
    }
};
