<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DevicePing;
use Illuminate\Http\Request;

class DeviceLogController extends Controller
{
    public function index(Request $request)
    {
        $pings = DevicePing::with('bus')
            ->when($request->bus_id, fn ($q, $id) => $q->where('bus_id', $id))
            ->when($request->boolean('flagged'), fn ($q) => $q->where('is_flagged', true))
            ->when($request->search, function ($q, $s) {
                $q->where('device_id', 'like', "%{$s}%")->orWhere('pen_id', 'like', "%{$s}%");
            })
            ->latest('recorded_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.logs.index', [
            'pings' => $pings,
            'flaggedCount' => DevicePing::where('is_flagged', true)->count(),
            'search' => $request->search,
            'flaggedOnly' => $request->boolean('flagged'),
        ]);
    }

    public function resolve(DevicePing $log)
    {
        $log->update(['is_flagged' => false]);

        return back()->with('status', 'Marked as reviewed.');
    }
}
