<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Districts that belong to a given province (for the cascading select).
     */
    public function districts(Request $request)
    {
        $request->validate(['province_id' => ['required', 'exists:provinces,id']]);

        return District::where('province_id', $request->province_id)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Cities that belong to a given district (for the cascading select).
     */
    public function cities(Request $request)
    {
        $request->validate(['district_id' => ['required', 'exists:districts,id']]);

        return City::where('district_id', $request->district_id)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Quickly add a new city under a district from the Bus form, without
     * leaving the page.
     */
    public function storeCity(Request $request)
    {
        $validated = $request->validate([
            'district_id' => ['required', 'exists:districts,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $district = District::findOrFail($validated['district_id']);

        $city = City::firstOrCreate(
            ['district_id' => $district->id, 'name' => $validated['name']],
            ['province_id' => $district->province_id]
        );

        return response()->json($city);
    }
}
