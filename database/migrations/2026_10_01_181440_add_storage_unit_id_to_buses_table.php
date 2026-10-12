<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->foreignId('storage_unit_id')->nullable()->unique()->after('processing_unit_id')->constrained('storage_units')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('storage_unit_id');
        });
    }
};
