<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\BusIncident;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IncidentController extends Controller
{
    public function index()
    {
        return view('admin.incidents.index', [
            'buses' => Bus::orderBy('number')->get(),
            'incidents' => BusIncident::with('bus', 'reportedBy')->latest('occurred_at')->paginate(20),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => ['required', 'exists:buses,id'],
            'type' => ['required', Rule::in(array_keys(BusIncident::LABELS))],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        BusIncident::create($validated + [
            'reported_by' => $request->user()->id,
            'occurred_at' => now(),
        ]);

        return back()->with('status', 'Incident logged.');
    }
}
