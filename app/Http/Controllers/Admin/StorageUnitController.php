<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StorageUnit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StorageUnitController extends Controller
{
    public function index()
    {
        return view('admin.storage-units.index', [
            'units' => StorageUnit::withCount('bus')->with('lastProcessingUnit')->latest()->paginate(20),
        ]);
    }

    public function store(Request $request)
    {
        $unit = StorageUnit::create($this->validated($request));

        if ($request->wantsJson()) {
            return response()->json($unit);
        }

        return back()->with('status', 'Storage unit added.');
    }

    public function update(Request $request, StorageUnit $storageUnit)
    {
        $storageUnit->update($this->validated($request, $storageUnit->id));

        return back()->with('status', 'Storage unit updated.');
    }

    public function destroy(StorageUnit $storageUnit)
    {
        $storageUnit->delete();

        return back()->with('status', 'Storage unit deleted.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'pen_id' => ['required', 'digits_between:1,20', Rule::unique('storage_units', 'pen_id')->ignore($ignoreId)],
        ]);
    }
}
