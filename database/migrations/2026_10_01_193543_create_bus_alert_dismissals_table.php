<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Records that an admin dismissed a "bus running longer than expected"
     * alert — either just for the session currently in progress
     * (`session_started_at` set, `forever` false) or permanently for that
     * bus (`forever` true, ignores session).
     */
    public function up(): void
    {
        Schema::create('bus_alert_dismissals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('route_overrun');
            $table->boolean('forever')->default(false);
            $table->timestamp('session_started_at')->nullable();
            $table->foreignId('dismissed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_alert_dismissals');
    }
};
