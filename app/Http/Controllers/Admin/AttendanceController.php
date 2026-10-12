<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\BusAttendance;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ? \Carbon\Carbon::parse($request->date) : now();
        $dateString = $date->toDateString();

        $buses = Bus::with([
            'city',
            'attendances' => fn ($q) => $q->where('date', $dateString)->with('markedBy'),
        ])->orderBy('number')->get();

        return view('admin.attendance.index', [
            'buses' => $buses,
            'date' => $date,
            'isToday' => $date->isToday(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => ['required', 'exists:buses,id'],
            'date' => ['required', 'date'],
            'status' => ['required', Rule::in([BusAttendance::RUNNING, BusAttendance::HOLIDAY, BusAttendance::COULDNT_CONTACT])],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        BusAttendance::updateOrCreate(
            ['bus_id' => $validated['bus_id'], 'date' => $validated['date']],
            [
                'status' => $validated['status'],
                'auto_marked' => false,
                'marked_by' => $request->user()->id,
                'note' => $validated['note'] ?? null,
            ]
        );

        return back()->with('status', 'Attendance updated.');
    }
}
