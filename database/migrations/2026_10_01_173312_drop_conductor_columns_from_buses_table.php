<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn(['conductor_name', 'conductor_phone']);
        });
    }

    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->string('conductor_name')->nullable();
            $table->string('conductor_phone')->nullable();
        });
    }
};
