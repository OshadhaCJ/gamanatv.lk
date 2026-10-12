<?php

namespace App\Services;

use App\Models\Bus;
use App\Models\BusAlertDismissal;
use App\Models\DevicePing;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Turns the raw stream of device pings into useful numbers: a bus's
 * current status, how long it has actually played content on a given
 * day, and whether it's running longer than its route should take.
 *
 * Status meanings:
 *   on    — bus started, TV not playing yet
 *   play  — bus started and the TV is playing
 *   pause — bus started but playback is paused
 *   off   — bus stopped
 *
 * Both "off" and "pause" pings carry `duration_seconds`: how long the
 * content played for the session that just ended/paused (the device's
 * own counter, not a timestamp diff — see DeviceWebhookController). So a
 * day's play time is the sum of `duration_seconds` on "off"/"pause"
 * pings received that day, plus — if still playing right now — the time
 * elapsed since that still-open "play" ping.
 */
class DeviceStatusService
{
    private const SESSION_END_STATUSES = [DevicePing::STATUS_OFF, DevicePing::STATUS_PAUSE];

    /**
     * The most recent ping for a bus, or null if it has never reported.
     */
    public function currentPing(Bus $bus): ?DevicePing
    {
        return DevicePing::where('bus_id', $bus->id)->latest('recorded_at')->first();
    }

    /**
     * Total minutes spent playing content during the given day
     * (defaults to today), in the app timezone.
     */
    public function playMinutesForDay(Bus $bus, ?Carbon $day = null): int
    {
        $day ??= now();
        $start = $day->copy()->startOfDay();
        $end = $day->copy()->endOfDay();

        $seconds = (int) DevicePing::where('bus_id', $bus->id)
            ->whereIn('status', self::SESSION_END_STATUSES)
            ->whereBetween('recorded_at', [$start, $end])
            ->sum('duration_seconds');

        $current = $this->currentPing($bus);
        if ($current && $current->status === DevicePing::STATUS_PLAY && $current->recorded_at->between($start, $end)) {
            $seconds += $current->recorded_at->diffInSeconds(now());
        }

        return (int) round($seconds / 60);
    }

    /**
     * When the bus's current "on" (running) session started — walking
     * back through pings until hitting the last "off", or the beginning
     * of its history. Null if the bus is currently off / has no data.
     */
    public function currentSessionStart(Bus $bus): ?Carbon
    {
        $pings = DevicePing::where('bus_id', $bus->id)->orderByDesc('recorded_at')->get();

        $start = null;
        foreach ($pings as $ping) {
            if ($ping->status === DevicePing::STATUS_OFF) {
                break;
            }
            $start = $ping->recorded_at;
        }

        return $start;
    }

    /**
     * If the bus is currently running longer than its route duration
     * (plus the configured markup) allows, returns the overrun details;
     * otherwise null. Does not consider whether it's been dismissed.
     */
    public function routeOverrun(Bus $bus): ?array
    {
        if (! $bus->route_duration_minutes) {
            return null;
        }

        $current = $this->currentPing($bus);
        if (! $current || $current->status === DevicePing::STATUS_OFF) {
            return null;
        }

        $start = $this->currentSessionStart($bus);
        if (! $start) {
            return null;
        }

        $markup = (float) Setting::get('route_overrun_markup_percent', 20);
        $thresholdMinutes = $bus->route_duration_minutes * (1 + $markup / 100);
        $elapsedMinutes = $start->diffInMinutes(now());

        if ($elapsedMinutes <= $thresholdMinutes) {
            return null;
        }

        return [
            'session_started_at' => $start,
            'elapsed_minutes' => (int) $elapsedMinutes,
            'threshold_minutes' => (int) round($thresholdMinutes),
        ];
    }

    /**
     * Whether an active overrun for this bus/session has already been
     * dismissed (forever, or for this specific session).
     */
    public function isOverrunDismissed(Bus $bus, Carbon $sessionStart): bool
    {
        return BusAlertDismissal::where('bus_id', $bus->id)
            ->where('type', 'route_overrun')
            ->where(function ($q) use ($sessionStart) {
                $q->where('forever', true)
                    ->orWhere('session_started_at', $sessionStart->format('Y-m-d H:i:s'));
            })
            ->exists();
    }

    /**
     * Batch version of currentPing() + playMinutesForDay() for a set of
     * buses, avoiding N+1 queries on a dashboard listing all of them.
     * Returns an array keyed by bus id: ['ping' => DevicePing|null, 'play_minutes' => int].
     */
    public function dashboardData(Collection $buses): array
    {
        $busIds = $buses->pluck('id');
        $start = now()->startOfDay();
        $end = now()->endOfDay();

        $latestPings = DevicePing::whereIn('bus_id', $busIds)
            ->orderByDesc('recorded_at')
            ->get()
            ->groupBy('bus_id')
            ->map(fn ($pings) => $pings->first());

        $todaySeconds = DevicePing::whereIn('bus_id', $busIds)
            ->whereIn('status', self::SESSION_END_STATUSES)
            ->whereBetween('recorded_at', [$start, $end])
            ->selectRaw('bus_id, SUM(duration_seconds) as secs')
            ->groupBy('bus_id')
            ->pluck('secs', 'bus_id');

        $result = [];
        foreach ($busIds as $busId) {
            $ping = $latestPings->get($busId);
            $seconds = (int) ($todaySeconds->get($busId) ?? 0);

            if ($ping && $ping->status === DevicePing::STATUS_PLAY && $ping->recorded_at->between($start, $end)) {
                $seconds += $ping->recorded_at->diffInSeconds(now());
            }

            $result[$busId] = [
                'ping' => $ping,
                'play_minutes' => (int) round($seconds / 60),
            ];
        }

        return $result;
    }
}
