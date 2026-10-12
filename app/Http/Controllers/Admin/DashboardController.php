<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\BusAlertDismissal;
use App\Models\BusAttendance;
use App\Models\DevicePing;
use App\Models\StorageSwapRequest;
use App\Services\DeviceStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index(Request $request, DeviceStatusService $deviceStatus)
    {
        $buses = Bus::with('city', 'processingUnit', 'storageUnit')
            ->when($request->search, fn ($q, $s) => $q->where('number', 'like', "%{$s}%")->orWhere('name', 'like', "%{$s}%"))
            ->orderBy('number')
            ->get();

        $data = $deviceStatus->dashboardData($buses);

        $stats = [
            'total' => $buses->count(),
            'playing' => collect($data)->filter(fn ($d) => $d['ping']?->status === DevicePing::STATUS_PLAY)->count(),
            'off' => collect($data)->filter(fn ($d) => $d['ping']?->status === DevicePing::STATUS_OFF)->count(),
            'no_data' => collect($data)->filter(fn ($d) => ! $d['ping'])->count(),
            'total_play_minutes' => collect($data)->sum('play_minutes'),
        ];

        $alerts = [];
        foreach ($buses as $bus) {
            $overrun = $deviceStatus->routeOverrun($bus);
            if ($overrun && ! $deviceStatus->isOverrunDismissed($bus, $overrun['session_started_at'])) {
                $alerts[] = ['bus' => $bus] + $overrun;
            }
        }

        $attendanceAlerts = [];
        if (now()->hour >= 12) {
            $todayAttendanceBusIds = BusAttendance::where('date', now()->toDateString())->pluck('bus_id');
            $attendanceAlerts = $buses->reject(fn ($bus) => $todayAttendanceBusIds->contains($bus->id))->values();
        }

        $swapFaults = StorageSwapRequest::where('status', StorageSwapRequest::FAILED)
            ->where('acknowledged', false)
            ->with('bus', 'newStorageUnit')
            ->get();

        return view('admin.dashboard.index', [
            'buses' => $buses,
            'data' => $data,
            'stats' => $stats,
            'search' => $request->search,
            'alerts' => $alerts,
            'attendanceAlerts' => $attendanceAlerts,
            'swapFaults' => $swapFaults,
            'flaggedCount' => DevicePing::where('is_flagged', true)->count(),
        ]);
    }

    /**
     * Lightweight JSON used by the dashboard to refresh statuses every
     * few seconds without a full page reload.
     */
    public function data(DeviceStatusService $deviceStatus): JsonResponse
    {
        $buses = Bus::select('id')->get();
        $data = $deviceStatus->dashboardData($buses);

        return response()->json(
            collect($data)->map(fn ($d, $busId) => [
                'bus_id' => $busId,
                'status' => $d['ping']?->status,
                'play_minutes' => $d['play_minutes'],
                'last_reported' => $d['ping']?->recorded_at?->diffForHumans(),
            ])->values()
        );
    }

    public function dismissAlert(Request $request, Bus $bus)
    {
        $request->validate(['mode' => [Rule::in(['now', 'forever'])]]);

        $sessionStart = app(DeviceStatusService::class)->currentSessionStart($bus);

        BusAlertDismissal::create([
            'bus_id' => $bus->id,
            'type' => 'route_overrun',
            'forever' => $request->mode === 'forever',
            'session_started_at' => $request->mode === 'now' ? $sessionStart : null,
            'dismissed_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Alert dismissed.');
    }
}
