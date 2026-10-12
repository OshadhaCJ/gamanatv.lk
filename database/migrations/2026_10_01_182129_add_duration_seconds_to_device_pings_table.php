<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The real device reports the "time" param as an elapsed-seconds
     * counter, not a clock timestamp: 0 when a "play" starts, and the
     * number of seconds just played when it reports "off" (confirmed
     * against the legacy bus_data table's actual rows). `recorded_at`
     * stays the server-received time; `duration_seconds` holds that
     * counter value.
     */
    public function up(): void
    {
        Schema::table('device_pings', function (Blueprint $table) {
            $table->unsignedInteger('duration_seconds')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('device_pings', function (Blueprint $table) {
            $table->dropColumn('duration_seconds');
        });
    }
};
