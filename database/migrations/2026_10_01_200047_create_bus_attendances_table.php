<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row per bus per day. Coordinators mark it manually (Running /
     * Holiday / Couldn't Contact); if a device ping shows the bus running
     * before anyone marks it, the webhook auto-fills "running" so the
     * coordinator only needs to act on the exceptions.
     */
    public function up(): void
    {
        Schema::create('bus_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['running', 'holiday', 'couldnt_contact']);
            $table->boolean('auto_marked')->default(false);
            $table->foreignId('marked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->unique(['bus_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_attendances');
    }
};
