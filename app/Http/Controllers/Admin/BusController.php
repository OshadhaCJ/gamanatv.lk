<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Owner;
use App\Models\ProcessingUnit;
use App\Models\Province;
use App\Models\Route;
use App\Models\StorageUnit;
use App\Services\DeviceStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BusController extends Controller
{
    private const IMAGE_FIELDS = ['front_image', 'side_image', 'tv_front_image', 'tv_back_image'];

    public function index(Request $request)
    {
        $buses = Bus::with('city', 'route')->withCount('drivers', 'conductors')
            ->when($request->search, fn ($q, $s) => $q->where('number', 'like', "%{$s}%")->orWhere('name', 'like', "%{$s}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.buses.index', [
            'buses' => $buses,
            'search' => $request->search,
            'stats' => [
                'total' => Bus::count(),
                'fixed' => Bus::where('schedule_type', 'fixed')->count(),
                'rotation' => Bus::where('schedule_type', 'rotation')->count(),
            ],
        ]);
    }

    public function create()
    {
        return view('admin.buses.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validateBus($request);
        $drivers = $validated['drivers'] ?? [];
        $conductors = $validated['conductors'] ?? [];
        unset($validated['drivers'], $validated['conductors']);

        foreach (self::IMAGE_FIELDS as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('buses', 'uploads');
            }
        }

        $bus = DB::transaction(function () use ($validated, $drivers, $conductors) {
            $bus = Bus::create($validated);
            $bus->drivers()->createMany($drivers);
            $bus->conductors()->createMany($conductors);

            return $bus;
        });

        return redirect()->route('admin.buses.index')->with('status', 'Bus added successfully.');
    }

    public function show(Bus $bus, DeviceStatusService $deviceStatus)
    {
        $bus->load('city.district.province', 'owner', 'route', 'drivers', 'conductors', 'processingUnit', 'storageUnit.lastProcessingUnit');

        return view('admin.buses.show', [
            'bus' => $bus,
            'currentPing' => $deviceStatus->currentPing($bus),
            'todayPlayMinutes' => $deviceStatus->playMinutesForDay($bus),
        ]);
    }

    public function edit(Bus $bus)
    {
        $bus->load('city.district', 'drivers', 'conductors');

        return view('admin.buses.edit', ['bus' => $bus] + $this->formData($bus));
    }

    public function update(Request $request, Bus $bus)
    {
        $validated = $this->validateBus($request, $bus->id);
        $drivers = $validated['drivers'] ?? [];
        $conductors = $validated['conductors'] ?? [];
        unset($validated['drivers'], $validated['conductors']);

        foreach (self::IMAGE_FIELDS as $field) {
            if ($request->hasFile($field)) {
                if ($bus->$field) {
                    Storage::disk('uploads')->delete($bus->$field);
                }
                $validated[$field] = $request->file($field)->store('buses', 'uploads');
            }
        }

        DB::transaction(function () use ($bus, $validated, $drivers, $conductors) {
            $bus->update($validated);
            $bus->drivers()->delete();
            $bus->drivers()->createMany($drivers);
            $bus->conductors()->delete();
            $bus->conductors()->createMany($conductors);
        });

        return redirect()->route('admin.buses.index')->with('status', 'Bus updated successfully.');
    }

    public function destroy(Bus $bus)
    {
        foreach (self::IMAGE_FIELDS as $field) {
            if ($bus->$field) {
                Storage::disk('uploads')->delete($bus->$field);
            }
        }

        $bus->delete();

        return redirect()->route('admin.buses.index')->with('status', 'Bus deleted.');
    }

    private function formData(?Bus $bus = null): array
    {
        return [
            'provinces' => Province::with('districts.cities')->orderBy('name')->get(),
            'owners' => Owner::orderBy('name')->get(),
            'routes' => Route::orderBy('start_place')->get(),
            'processingUnits' => ProcessingUnit::where(function ($q) use ($bus) {
                $q->whereDoesntHave('bus');
                if ($bus) {
                    $q->orWhere('id', $bus->processing_unit_id);
                }
            })->orderBy('device_id')->get(),
            'storageUnits' => StorageUnit::where(function ($q) use ($bus) {
                $q->whereDoesntHave('bus');
                if ($bus) {
                    $q->orWhere('id', $bus->storage_unit_id);
                }
            })->orderBy('pen_id')->get(),
        ];
    }

    private function validateBus(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'city_id' => ['required', 'exists:cities,id'],
            'number' => ['required', 'string', 'max:50', Rule::unique('buses', 'number')->ignore($ignoreId)],
            'name' => ['nullable', 'string', 'max:255'],
            'owner_id' => ['nullable', 'exists:owners,id'],
            'processing_unit_id' => ['nullable', 'exists:processing_units,id', Rule::unique('buses', 'processing_unit_id')->ignore($ignoreId)],
            'storage_unit_id' => ['nullable', 'exists:storage_units,id', Rule::unique('buses', 'storage_unit_id')->ignore($ignoreId)],

            'drivers' => ['nullable', 'array'],
            'drivers.*.name' => ['required_with:drivers', 'string', 'max:255'],
            'drivers.*.phone' => ['nullable', 'string', 'max:20'],

            'conductors' => ['nullable', 'array'],
            'conductors.*.name' => ['required_with:conductors', 'string', 'max:255'],
            'conductors.*.phone' => ['nullable', 'string', 'max:20'],

            'schedule_type' => ['required', Rule::in(['fixed', 'rotation'])],
            'fixed_time' => ['nullable', 'required_if:schedule_type,fixed', 'date_format:H:i'],
            'rotation_description' => ['nullable', 'required_if:schedule_type,rotation', 'string'],

            'front_image' => ['nullable', 'image', 'max:4096'],
            'side_image' => ['nullable', 'image', 'max:4096'],
            'tv_front_image' => ['nullable', 'image', 'max:4096'],
            'tv_back_image' => ['nullable', 'image', 'max:4096'],

            'routes_per_day' => ['nullable', 'integer', 'min:0', 'max:100'],
            'route_duration_minutes' => ['nullable', 'integer', 'min:0', 'max:1440'],

            'starting_place_fixed' => ['required', 'boolean'],
            'route_id' => ['nullable', 'required_if:starting_place_fixed,1', 'exists:routes,id'],

            'tv_brand' => ['nullable', 'string', 'max:100'],
            'tv_size' => ['nullable', 'string', 'max:50'],
            'input_type' => ['nullable', Rule::in(['tv', 'set'])],

            'holiday_note' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validated['schedule_type'] === 'fixed') {
            $validated['rotation_description'] = null;
        } else {
            $validated['fixed_time'] = null;
        }

        if (! $validated['starting_place_fixed']) {
            $validated['route_id'] = null;
        }

        $validated['drivers'] = array_values(array_filter(
            $validated['drivers'] ?? [],
            fn ($d) => filled($d['name'] ?? null)
        ));

        $validated['conductors'] = array_values(array_filter(
            $validated['conductors'] ?? [],
            fn ($c) => filled($c['name'] ?? null)
        ));

        return $validated;
    }
}
