<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Storage units (SD cards / drives) move between processing units, so
     * instead of a telephone number we track which processing unit it was
     * last seen paired with, and when that pairing last changed.
     */
    public function up(): void
    {
        Schema::table('storage_units', function (Blueprint $table) {
            $table->dropColumn('telephone_number');
            $table->foreignId('last_processing_unit_id')->nullable()->after('pen_id')->constrained('processing_units')->nullOnDelete();
            $table->timestamp('last_paired_at')->nullable()->after('last_processing_unit_id');
        });
    }

    public function down(): void
    {
        Schema::table('storage_units', function (Blueprint $table) {
            $table->dropConstrainedForeignId('last_processing_unit_id');
            $table->dropColumn('last_paired_at');
            $table->string('telephone_number')->nullable();
        });
    }
};
