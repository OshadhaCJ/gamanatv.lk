<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ad-hoc field observations a coordinator (or admin/developer) logs
     * on the spot — e.g. "saw the bus on its route", "TV was playing",
     * "TV wasn't playing", "saw the bus but couldn't see the TV".
     */
    public function up(): void
    {
        Schema::create('bus_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['seen_on_route', 'seen_playing', 'seen_not_playing', 'seen_tv_not_visible']);
            $table->string('note')->nullable();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('occurred_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_incidents');
    }
};
