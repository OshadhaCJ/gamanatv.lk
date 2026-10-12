@php
  $bus ??= null;
  $initialDrivers = old('drivers', optional($bus)->drivers?->map->only(['name', 'phone'])->toArray() ?? [['name' => '', 'phone' => '']]);
  $initialConductors = old('conductors', optional($bus)->conductors?->map->only(['name', 'phone'])->toArray() ?? [['name' => '', 'phone' => '']]);
@endphp

<div
  x-data='busForm({
    provinces: @json($provinces->toArray(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
    initialCityId: {{ old('city_id', optional($bus)->city_id ?? 'null') }},
    createCityUrl: "{{ route('admin.locations.cities.store') }}",
    csrfToken: "{{ csrf_token() }}",
    drivers: @json($initialDrivers, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
    conductors: @json($initialConductors, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
    owners: @json($owners->toArray(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
    routeOptions: @json($routes->toArray(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
    initialOwnerId: {{ old('owner_id', optional($bus)->owner_id ?? 'null') }},
    initialRouteId: {{ old('route_id', optional($bus)->route_id ?? 'null') }},
    createOwnerUrl: "{{ route('admin.owners.store') }}",
    createRouteUrl: "{{ route('admin.routes.store') }}",
    processingUnits: @json($processingUnits->toArray(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
    initialProcessingUnitId: {{ old('processing_unit_id', optional($bus)->processing_unit_id ?? 'null') }},
    createProcessingUnitUrl: "{{ route('admin.processing-units.store') }}",
    storageUnits: @json($storageUnits->toArray(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
    initialStorageUnitId: {{ old('storage_unit_id', optional($bus)->storage_unit_id ?? 'null') }},
    createStorageUnitUrl: "{{ route('admin.storage-units.store') }}",
  })'
  class="space-y-10"
>

  <!-- City -->
  <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
    <h2 class="font-black text-lg text-gray-900 mb-1">City</h2>
    <p class="text-gray-500 text-sm mb-6">Province &rarr; District &rarr; City</p>
    <div class="grid sm:grid-cols-3 gap-4">
      <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Province</label>
        <select x-model="provinceId" @change="onProvinceChange()" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">
          <option value="">Select province</option>
          <template x-for="p in provinces" :key="p.id"><option :value="p.id" x-text="p.name"></option></template>
        </select>
      </div>
      <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">District</label>
        <select x-model="districtId" @change="onDistrictChange()" :disabled="!provinceId" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm disabled:bg-gray-50">
          <option value="">Select district</option>
          <template x-for="d in districts" :key="d.id"><option :value="d.id" x-text="d.name"></option></template>
        </select>
      </div>
      <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">City</label>
        <select name="city_id" x-model="cityId" :disabled="!districtId" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm disabled:bg-gray-50">
          <option value="">Select city</option>
          <template x-for="c in cities" :key="c.id"><option :value="c.id" x-text="c.name"></option></template>
        </select>
        <div class="flex items-center gap-2 mt-2" x-show="districtId">
          <input type="text" x-model="newCityName" placeholder="Add a new city..." class="flex-1 px-3 py-2 rounded-lg border border-gray-200 text-xs" />
          <button type="button" @click="addCity()" :disabled="!newCityName" class="shrink-0 text-xs font-bold text-brand-red border border-brand-red/30 rounded-lg px-3 py-2 disabled:opacity-40">+ Add</button>
        </div>
      </div>
    </div>
    @error('city_id') <p class="text-brand-red text-xs font-semibold mt-3">{{ $message }}</p> @enderror
  </section>

  <!-- Identification -->
  <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
    <h2 class="font-black text-lg text-gray-900 mb-6">Bus Number &amp; Name</h2>
    <div class="grid sm:grid-cols-2 gap-4">
      <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Bus Number</label>
        <input type="text" name="number" value="{{ old('number', optional($bus)->number ?? '') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm" />
        @error('number') <p class="text-brand-red text-xs font-semibold mt-2">{{ $message }}</p> @enderror
      </div>
      <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Name (optional)</label>
        <input type="text" name="name" value="{{ old('name', optional($bus)->name ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm" />
      </div>
    </div>
  </section>

  <!-- Owner / Drivers / Conductor -->
  <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
    <h2 class="font-black text-lg text-gray-900 mb-6">Contact Details</h2>

    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Owner</label>
    <select name="owner_id" x-model="ownerId" class="w-full sm:w-80 px-4 py-3 rounded-xl border border-gray-200 text-sm mb-2">
      <option value="">No owner</option>
      <template x-for="o in owners" :key="o.id"><option :value="o.id" x-text="o.phone ? o.name + ' — ' + o.phone : o.name"></option></template>
    </select>
    <div class="flex items-center gap-2 mb-6 max-w-xl">
      <input type="text" x-model="newOwnerName" placeholder="New owner name..." class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm" />
      <input type="text" x-model="newOwnerPhone" placeholder="Phone (optional)" class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm" />
      <button type="button" @click="addOwner()" :disabled="!newOwnerName" class="shrink-0 text-xs font-bold text-brand-red border border-brand-red/30 rounded-xl px-4 py-3 disabled:opacity-40">+ Add</button>
    </div>

    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Drivers</label>
    <template x-for="(driver, i) in drivers" :key="i">
      <div class="flex items-center gap-3 mb-3">
        <input type="text" :name="'drivers[' + i + '][name]'" x-model="driver.name" placeholder="Driver name" class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm" />
        <input type="text" :name="'drivers[' + i + '][phone]'" x-model="driver.phone" placeholder="Phone" class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm" />
        <button type="button" @click="removeDriver(i)" class="w-10 h-10 shrink-0 rounded-full border border-red-100 text-brand-red">&times;</button>
      </div>
    </template>
    <button type="button" @click="addDriver()" class="text-xs font-bold text-brand-red mb-6">+ Add another driver</button>

    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Conductors</label>
    <template x-for="(conductor, i) in conductors" :key="i">
      <div class="flex items-center gap-3 mb-3">
        <input type="text" :name="'conductors[' + i + '][name]'" x-model="conductor.name" placeholder="Conductor name" class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm" />
        <input type="text" :name="'conductors[' + i + '][phone]'" x-model="conductor.phone" placeholder="Phone" class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm" />
        <button type="button" @click="removeConductor(i)" class="w-10 h-10 shrink-0 rounded-full border border-red-100 text-brand-red">&times;</button>
      </div>
    </template>
    <button type="button" @click="addConductor()" class="text-xs font-bold text-brand-red">+ Add another conductor</button>
  </section>

  <!-- Schedule -->
  <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
    <h2 class="font-black text-lg text-gray-900 mb-6">Fixed Time or Rotation Time</h2>
    <div class="flex items-center gap-3 mb-5">
      <label class="flex items-center gap-2 text-sm font-semibold"><input type="radio" name="schedule_type" value="fixed" x-model="scheduleType" /> Fixed Time</label>
      <label class="flex items-center gap-2 text-sm font-semibold"><input type="radio" name="schedule_type" value="rotation" x-model="scheduleType" /> Rotation</label>
    </div>
    <div x-show="scheduleType === 'fixed'">
      <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Time</label>
      <input type="time" name="fixed_time" value="{{ old('fixed_time', !empty($bus) && $bus->fixed_time ? \Illuminate\Support\Carbon::parse($bus->fixed_time)->format('H:i') : '') }}" class="w-full sm:w-64 px-4 py-3 rounded-xl border border-gray-200 text-sm" />
    </div>
    <div x-show="scheduleType === 'rotation'">
      <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Description</label>
      <textarea name="rotation_description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">{{ old('rotation_description', optional($bus)->rotation_description ?? '') }}</textarea>
    </div>
  </section>

  <!-- Images -->
  <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
    <h2 class="font-black text-lg text-gray-900 mb-6">Images</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      @foreach (['front_image'=>'Front Image','side_image'=>'Side Image','tv_front_image'=>'TV Front Image','tv_back_image'=>'TV Back Image (with Device)'] as $field => $label)
        <div x-data="{ fileName: '' }">
          <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">{{ $label }}</label>
          @if (!empty($bus) && $bus->$field)
            <img src="{{ asset('images/uploads/'.$bus->$field) }}" class="w-full h-28 object-cover rounded-xl mb-2 border border-gray-100" />
          @endif
          <label class="flex items-center justify-center text-center px-2 h-28 rounded-xl border-2 border-dashed border-gray-200 text-xs text-gray-400 font-semibold cursor-pointer">
            <span x-text="fileName || 'Choose file'" class="truncate"></span>
            <input type="file" name="{{ $field }}" accept="image/*" class="hidden" @change="fileName = $event.target.files[0]?.name ?? ''" />
          </label>
        </div>
      @endforeach
    </div>
  </section>

  <!-- Route Operations -->
  <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
    <h2 class="font-black text-lg text-gray-900 mb-6">Route Operations</h2>
    <div class="grid sm:grid-cols-2 gap-4 mb-6">
      <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Routes per Day</label>
        <input type="number" min="0" name="routes_per_day" value="{{ old('routes_per_day', optional($bus)->routes_per_day ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm" />
      </div>
      <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Average Time per Route (minutes)</label>
        <input type="number" min="0" name="route_duration_minutes" value="{{ old('route_duration_minutes', optional($bus)->route_duration_minutes ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm" />
      </div>
    </div>

    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Starting Place</label>
    <div class="flex items-center gap-3 mb-4">
      <label class="flex items-center gap-2 text-sm font-semibold"><input type="radio" name="starting_place_fixed" value="1" x-model="startingPlaceFixed" /> Fixed</label>
      <label class="flex items-center gap-2 text-sm font-semibold"><input type="radio" name="starting_place_fixed" value="0" x-model="startingPlaceFixed" /> Not Fixed</label>
    </div>
    <div x-show="startingPlaceFixed === '1'">
      <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Route</label>
      <select name="route_id" x-model="routeId" class="w-full sm:w-96 px-4 py-3 rounded-xl border border-gray-200 text-sm mb-2">
        <option value="">Select route</option>
        <template x-for="r in routeOptions" :key="r.id">
          <option :value="r.id" x-text="[r.start_place, r.route, r.end_place].filter(Boolean).join(' → ')"></option>
        </template>
      </select>
      @error('route_id') <p class="text-brand-red text-xs font-semibold mb-2">{{ $message }}</p> @enderror

      <div class="grid sm:grid-cols-3 gap-2 max-w-xl">
        <input type="text" x-model="newRoute.start_place" placeholder="Start" class="px-3 py-2 rounded-lg border border-gray-200 text-xs uppercase" />
        <input type="text" x-model="newRoute.route" placeholder="Route (optional)" class="px-3 py-2 rounded-lg border border-gray-200 text-xs uppercase" />
        <input type="text" x-model="newRoute.end_place" placeholder="End" class="px-3 py-2 rounded-lg border border-gray-200 text-xs uppercase" />
      </div>
      <button type="button" @click="addRoute()" :disabled="!newRoute.start_place || !newRoute.end_place" class="mt-2 text-xs font-bold text-brand-red border border-brand-red/30 rounded-lg px-4 py-2 disabled:opacity-40">+ Add new route</button>
    </div>
  </section>

  <!-- TV Details -->
  <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
    <h2 class="font-black text-lg text-gray-900 mb-6">TV Details</h2>
    <div class="grid sm:grid-cols-2 gap-4 mb-6">
      <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Brand</label>
        <input type="text" name="tv_brand" value="{{ old('tv_brand', optional($bus)->tv_brand ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm" />
      </div>
      <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Size</label>
        <input type="text" name="tv_size" value="{{ old('tv_size', optional($bus)->tv_size ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm" />
      </div>
    </div>
    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Input</label>
    <div class="flex items-center gap-3">
      <label class="flex items-center gap-2 text-sm font-semibold"><input type="radio" name="input_type" value="tv" {{ old('input_type', optional($bus)->input_type) === 'tv' ? 'checked' : '' }} /> TV (built-in)</label>
      <label class="flex items-center gap-2 text-sm font-semibold"><input type="radio" name="input_type" value="set" {{ old('input_type', optional($bus)->input_type) === 'set' ? 'checked' : '' }} /> Set (external)</label>
    </div>
  </section>

  <!-- Devices -->
  <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
    <h2 class="font-black text-lg text-gray-900 mb-6">Devices</h2>

    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Processing Unit</label>
    <select name="processing_unit_id" x-model="processingUnitId" class="w-full sm:w-80 px-4 py-3 rounded-xl border border-gray-200 text-sm mb-2">
      <option value="">No device assigned</option>
      <template x-for="u in processingUnits" :key="u.id"><option :value="u.id" x-text="u.device_id"></option></template>
    </select>
    @error('processing_unit_id') <p class="text-brand-red text-xs font-semibold mb-2">{{ $message }}</p> @enderror
    <div class="flex items-center gap-2 max-w-xl mb-6">
      <input type="text" x-model="newUnitDeviceId" inputmode="numeric" pattern="[0-9]*" placeholder="New device ID (numbers only)..." class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm" />
      <input type="text" x-model="newUnitPhone" placeholder="Telephone (optional)" class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm" />
      <button type="button" @click="addProcessingUnit()" :disabled="!newUnitDeviceId" class="shrink-0 text-xs font-bold text-brand-red border border-brand-red/30 rounded-xl px-4 py-3 disabled:opacity-40">+ Add</button>
    </div>

    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Storage Unit</label>
    <select name="storage_unit_id" x-model="storageUnitId" class="w-full sm:w-80 px-4 py-3 rounded-xl border border-gray-200 text-sm mb-2">
      <option value="">No device assigned</option>
      <template x-for="u in storageUnits" :key="u.id"><option :value="u.id" x-text="u.pen_id"></option></template>
    </select>
    @error('storage_unit_id') <p class="text-brand-red text-xs font-semibold mb-2">{{ $message }}</p> @enderror
    <div class="flex items-center gap-2 max-w-xl">
      <input type="text" x-model="newStorageUnitPenId" inputmode="numeric" pattern="[0-9]*" placeholder="New pen ID (numbers only)..." class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm" />
      <button type="button" @click="addStorageUnit()" :disabled="!newStorageUnitPenId" class="shrink-0 text-xs font-bold text-brand-red border border-brand-red/30 rounded-xl px-4 py-3 disabled:opacity-40">+ Add</button>
    </div>
  </section>

  <!-- Holidays -->
  <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
    <h2 class="font-black text-lg text-gray-900 mb-6">Holiday Note</h2>
    <input type="text" name="holiday_note" value="{{ old('holiday_note', optional($bus)->holiday_note ?? '') }}" placeholder="e.g. Poya days, Sundays" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm" />
  </section>

</div>

<script>
  function busForm({ provinces, initialCityId, createCityUrl, csrfToken, drivers, conductors, owners, routeOptions, initialOwnerId, initialRouteId, createOwnerUrl, createRouteUrl, processingUnits, initialProcessingUnitId, createProcessingUnitUrl, storageUnits, initialStorageUnitId, createStorageUnitUrl }) {
    return {
      provinces,
      provinceId: '',
      districtId: '',
      cityId: initialCityId ?? '',
      scheduleType: '{{ old('schedule_type', optional($bus)->schedule_type ?? 'fixed') }}',
      startingPlaceFixed: '{{ old('starting_place_fixed', isset($bus) ? (int) $bus->starting_place_fixed : 1) }}',
      newCityName: '',
      drivers: drivers.length ? drivers : [{ name: '', phone: '' }],
      conductors: conductors.length ? conductors : [{ name: '', phone: '' }],

      owners,
      ownerId: initialOwnerId ?? '',
      newOwnerName: '',
      newOwnerPhone: '',

      routeOptions,
      routeId: initialRouteId ?? '',
      newRoute: { start_place: '', route: '', end_place: '' },

      processingUnits,
      processingUnitId: initialProcessingUnitId ?? '',
      newUnitDeviceId: '',
      newUnitPhone: '',

      storageUnits,
      storageUnitId: initialStorageUnitId ?? '',
      newStorageUnitPenId: '',

      get districts() {
        const p = this.provinces.find(p => String(p.id) === String(this.provinceId));
        return p ? p.districts : [];
      },
      get cities() {
        const d = this.districts.find(d => String(d.id) === String(this.districtId));
        return d ? d.cities : [];
      },
      onProvinceChange() { this.districtId = ''; this.cityId = ''; },
      onDistrictChange() { this.cityId = ''; },
      addDriver() { this.drivers.push({ name: '', phone: '' }); },
      removeDriver(i) { this.drivers.splice(i, 1); if (!this.drivers.length) this.addDriver(); },
      addConductor() { this.conductors.push({ name: '', phone: '' }); },
      removeConductor(i) { this.conductors.splice(i, 1); if (!this.conductors.length) this.addConductor(); },

      async addCity() {
        if (!this.newCityName || !this.districtId) return;
        const res = await fetch(createCityUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
          body: JSON.stringify({ district_id: this.districtId, name: this.newCityName }),
        });
        if (!res.ok) return;
        const city = await res.json();
        const district = this.districts.find(d => String(d.id) === String(this.districtId));
        if (district && !district.cities.some(c => c.id === city.id)) district.cities.push(city);
        this.cityId = city.id;
        this.newCityName = '';
      },

      async addOwner() {
        if (!this.newOwnerName) return;
        const res = await fetch(createOwnerUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
          body: JSON.stringify({ name: this.newOwnerName, phone: this.newOwnerPhone }),
        });
        if (!res.ok) return;
        const owner = await res.json();
        this.owners.push(owner);
        this.ownerId = owner.id;
        this.newOwnerName = '';
        this.newOwnerPhone = '';
      },

      async addRoute() {
        const { start_place, route, end_place } = this.newRoute;
        if (!start_place || !end_place) return;
        const res = await fetch(createRouteUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
          body: JSON.stringify({ start_place, route, end_place }),
        });
        if (!res.ok) return;
        const newRoute = await res.json();
        this.routeOptions.push(newRoute);
        this.routeId = newRoute.id;
        this.newRoute = { start_place: '', route: '', end_place: '' };
      },

      async addProcessingUnit() {
        if (!this.newUnitDeviceId) return;
        const res = await fetch(createProcessingUnitUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
          body: JSON.stringify({ device_id: this.newUnitDeviceId, telephone_number: this.newUnitPhone }),
        });
        if (!res.ok) return;
        const unit = await res.json();
        this.processingUnits.push(unit);
        this.processingUnitId = unit.id;
        this.newUnitDeviceId = '';
        this.newUnitPhone = '';
      },

      async addStorageUnit() {
        if (!this.newStorageUnitPenId) return;
        const res = await fetch(createStorageUnitUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
          body: JSON.stringify({ pen_id: this.newStorageUnitPenId }),
        });
        if (!res.ok) return;
        const unit = await res.json();
        this.storageUnits.push(unit);
        this.storageUnitId = unit.id;
        this.newStorageUnitPenId = '';
      },

      init() {
        if (!this.cityId) return;
        for (const p of this.provinces) {
          for (const d of p.districts) {
            if (d.cities.some(c => String(c.id) === String(this.cityId))) {
              this.provinceId = p.id;
              this.districtId = d.id;
              return;
            }
          }
        }
      },
    };
  }
</script>
