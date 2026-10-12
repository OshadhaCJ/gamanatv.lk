<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\StorageSwapRequest;
use App\Models\StorageUnit;
use Illuminate\Http\Request;

class StorageSwapController extends Controller
{
    public function index()
    {
        return view('admin.storage-swaps.index', [
            'buses' => Bus::with('storageUnit')->orderBy('number')->get(),
            'storageUnits' => StorageUnit::orderBy('pen_id')->get(),
            'requests' => StorageSwapRequest::with('bus', 'oldStorageUnit', 'newStorageUnit', 'requestedBy')
                ->latest()
                ->paginate(15),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => ['required', 'exists:buses,id'],
            'new_storage_unit_id' => ['required', 'exists:storage_units,id'],
        ]);

        $bus = Bus::findOrFail($validated['bus_id']);

        StorageSwapRequest::create([
            'bus_id' => $bus->id,
            'old_storage_unit_id' => $bus->storage_unit_id,
            'new_storage_unit_id' => $validated['new_storage_unit_id'],
            'status' => StorageSwapRequest::PENDING,
            'requested_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Swap logged. Waiting for the device to confirm the new storage unit.');
    }

    public function acknowledge(StorageSwapRequest $swap)
    {
        $swap->update(['acknowledged' => true]);

        return back()->with('status', 'Acknowledged.');
    }
}
