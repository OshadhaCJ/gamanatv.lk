<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pings get flagged when the incoming sequence looks wrong (e.g. the
     * same status repeated back to back, or an off/pause with no session
     * to close) — most likely the device sent pings out of order or two
     * devices' logs got mixed up. Flags surface on the Logs page for an
     * admin to review and dismiss.
     */
    public function up(): void
    {
        Schema::table('device_pings', function (Blueprint $table) {
            $table->boolean('is_flagged')->default(false)->after('duration_seconds');
            $table->string('flag_reason')->nullable()->after('is_flagged');
        });
    }

    public function down(): void
    {
        Schema::table('device_pings', function (Blueprint $table) {
            $table->dropColumn(['is_flagged', 'flag_reason']);
        });
    }
};
