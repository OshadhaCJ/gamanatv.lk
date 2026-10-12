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
        Schema::create('phone_otps', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('role')->default('client');
            $table->string('code', 6);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_otps');
    }
};
