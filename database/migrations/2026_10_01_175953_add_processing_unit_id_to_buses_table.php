<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->foreignId('processing_unit_id')->nullable()->unique()->after('owner_id')->constrained('processing_units')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('processing_unit_id');
        });
    }
};
