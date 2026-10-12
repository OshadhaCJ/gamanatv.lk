<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index()
    {
        return view('admin.owners.index', ['owners' => Owner::latest()->paginate(20)]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        $owner = Owner::create($validated);

        if ($request->wantsJson()) {
            return response()->json($owner);
        }

        return back()->with('status', 'Owner added.');
    }

    public function update(Request $request, Owner $owner)
    {
        $owner->update($this->validated($request));

        return back()->with('status', 'Owner updated.');
    }

    public function destroy(Owner $owner)
    {
        $owner->delete();

        return back()->with('status', 'Owner deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);
    }
}
