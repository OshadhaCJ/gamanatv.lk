<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        return view('admin.routes.index', ['routes' => Route::latest()->paginate(20)]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        $route = Route::create($validated);

        if ($request->wantsJson()) {
            return response()->json($route);
        }

        return back()->with('status', 'Route added.');
    }

    public function update(Request $request, Route $route)
    {
        $route->update($this->validated($request));

        return back()->with('status', 'Route updated.');
    }

    public function destroy(Route $route)
    {
        $route->delete();

        return back()->with('status', 'Route deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'start_place' => ['required', 'string', 'max:255'],
            'route' => ['nullable', 'string', 'max:255'],
            'end_place' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
        ]);
    }
}
