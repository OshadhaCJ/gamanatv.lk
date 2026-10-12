<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProcessingUnit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProcessingUnitController extends Controller
{
    public function index()
    {
        return view('admin.processing-units.index', [
            'units' => ProcessingUnit::withCount('bus')->latest()->paginate(20),
        ]);
    }

    public function store(Request $request)
    {
        $unit = ProcessingUnit::create($this->validated($request));

        if ($request->wantsJson()) {
            return response()->json($unit);
        }

        return back()->with('status', 'Processing unit added.');
    }

    public function update(Request $request, ProcessingUnit $processingUnit)
    {
        $processingUnit->update($this->validated($request, $processingUnit->id));

        return back()->with('status', 'Processing unit updated.');
    }

    public function destroy(ProcessingUnit $processingUnit)
    {
        $processingUnit->delete();

        return back()->with('status', 'Processing unit deleted.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'device_id' => ['required', 'digits_between:1,20', Rule::unique('processing_units', 'device_id')->ignore($ignoreId)],
            'telephone_number' => ['nullable', 'string', 'max:20'],
        ]);
    }
}
