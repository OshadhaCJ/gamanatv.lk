@extends('admin.layout')

@section('title', 'Storage Device Changes')

@section('content')
  <div class="fade-in mb-6">
    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight lg:hidden">Storage Device Changes</h2>
    <p class="text-gray-500 text-sm mt-1">
      Log a storage device swap after changing it in the field, then wait for the device to confirm it over its next ping.
    </p>
  </div>

  @php
    $busOptions = $buses->map(fn ($b) => [
      'id' => $b->id,
      'number' => $b->number,
      'name' => $b->name,
      'current_pen' => $b->storageUnit?->pen_id,
    ]);
    $jsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
  @endphp
  <div
    x-data='swapForm({
      buses: @json($busOptions, $jsonFlags),
      storageUnits: @json($storageUnits->toArray(), $jsonFlags),
      createStorageUnitUrl: "{{ route('admin.storage-units.store') }}",
      csrfToken: "{{ csrf_token() }}",
    })'
    class="fade-in bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 mb-8"
  >
    <h3 class="font-black text-base sm:text-lg text-gray-900 mb-5">Log a Swap</h3>

    <form method="POST" action="{{ route('admin.storage-swaps.store') }}" class="space-y-4">
      @csrf

      <div class="relative max-w-sm">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Search Bus</label>
        <input
          type="text" x-model="busSearch" @focus="busOpen = true" @click.outside="busOpen = false"
          placeholder="Search by bus number or name..."
          class="w-full px-5 py-3.5 rounded-full border-2 border-gray-200 text-base focus:outline-none focus:ring-4 focus:ring-brand-red/15 focus:border-brand-red transition-all duration-200"
          autocomplete="off"
        />
        <div x-show="busOpen && filteredBuses.length" class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-56 overflow-y-auto">
          <template x-for="bus in filteredBuses" :key="bus.id">
            <button type="button" @click="selectBus(bus)" class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center justify-between">
              <span x-text="bus.number + (bus.name ? ' — ' + bus.name : '')"></span>
              <span class="text-xs text-gray-400" x-text="bus.current_pen ? 'Pen ' + bus.current_pen : 'No device'"></span>
            </button>
          </template>
        </div>
        <input type="hidden" name="bus_id" :value="selectedBus?.id" />
      </div>

      <template x-if="selectedBus">
        <div class="bg-gray-50 rounded-xl px-4 py-3 text-sm">
          <span class="font-bold text-gray-900" x-text="selectedBus.number"></span>
          — currently has pen <span class="font-bold" x-text="selectedBus.current_pen || 'none'"></span>
        </div>
      </template>

      <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">New Storage Unit (Pen ID)</label>
        <div class="flex items-center gap-2 max-w-md">
          <select name="new_storage_unit_id" x-model="newUnitId" class="flex-1 px-5 py-3.5 rounded-full border-2 border-gray-200 text-base focus:outline-none focus:ring-4 focus:ring-brand-red/15 focus:border-brand-red transition-all duration-200">
            <option value="">Select pen ID</option>
            <template x-for="u in storageUnits" :key="u.id"><option :value="u.id" x-text="u.pen_id"></option></template>
          </select>
        </div>
        <div class="flex items-center gap-2 max-w-md mt-2">
          <input type="text" x-model="newPenId" inputmode="numeric" pattern="[0-9]*" placeholder="Or register a new pen ID..." class="flex-1 px-4 py-2.5 rounded-full border-2 border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-red/15 focus:border-brand-red" />
          <button type="button" @click="addStorageUnit()" :disabled="!newPenId" class="shrink-0 text-xs font-bold text-brand-red border-2 border-brand-red/30 rounded-full px-4 py-2.5 disabled:opacity-40">+ Register</button>
        </div>
      </div>

      <button type="submit" :disabled="!selectedBus || !newUnitId" class="inline-flex items-center gap-2 bg-brand-red hover:bg-brand-darkRed active:scale-[0.98] hover:scale-[1.02] disabled:opacity-40 disabled:hover:scale-100 text-white px-8 py-3.5 rounded-full font-black text-base shadow-lg shadow-brand-red/30 transition-all duration-300">
        Mark as Changed
      </button>
    </form>
  </div>

  <div class="fade-in bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
      <thead>
        <tr class="text-left text-[11px] font-bold text-gray-400 uppercase tracking-wide border-b border-gray-100">
          <th class="px-5 py-3">Bus</th>
          <th class="px-5 py-3">Old &rarr; New Pen</th>
          <th class="px-5 py-3">Status</th>
          <th class="px-5 py-3">Logged By</th>
          <th class="px-5 py-3">When</th>
          <th class="px-5 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse ($requests as $req)
          <tr class="{{ $req->status === 'failed' && !$req->acknowledged ? 'bg-red-50/50' : '' }}">
            <td class="px-5 py-3 font-bold text-gray-900">{{ $req->bus->number }}</td>
            <td class="px-5 py-3 text-gray-600">{{ $req->oldStorageUnit?->pen_id ?? '—' }} &rarr; {{ $req->newStorageUnit->pen_id }}</td>
            <td class="px-5 py-3">
              <span class="text-[11px] font-black uppercase tracking-wide px-2.5 py-1 rounded-full
                {{ match($req->status) { 'confirmed' => 'bg-green-50 text-green-600', 'failed' => 'bg-red-50 text-brand-red', default => 'bg-amber-50 text-amber-600' } }}">
                {{ $req->status }}
              </span>
              @if ($req->status === 'failed')
                <p class="text-[11px] text-brand-red mt-1">{{ $req->fault_reason }}</p>
              @endif
            </td>
            <td class="px-5 py-3 text-gray-600">{{ $req->requestedBy->name }}</td>
            <td class="px-5 py-3 text-gray-500">{{ $req->created_at->diffForHumans() }}</td>
            <td class="px-5 py-3 text-right">
              @if ($req->status === 'failed' && ! $req->acknowledged)
                <form method="POST" action="{{ route('admin.storage-swaps.acknowledge', $req) }}">
                  @csrf
                  <button class="text-xs font-bold text-gray-600 border border-gray-200 rounded-full px-3 py-1.5 hover:bg-gray-50">Acknowledge</button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No swaps logged yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="mt-6">{{ $requests->links() }}</div>

  <script>
    function swapForm({ buses, storageUnits, createStorageUnitUrl, csrfToken }) {
      return {
        buses,
        storageUnits,
        busSearch: '',
        busOpen: false,
        selectedBus: null,
        newUnitId: '',
        newPenId: '',

        get filteredBuses() {
          const q = this.busSearch.toLowerCase();
          if (!q) return this.buses.slice(0, 8);
          return this.buses.filter(b => b.number.toLowerCase().includes(q) || (b.name || '').toLowerCase().includes(q)).slice(0, 8);
        },

        selectBus(bus) {
          this.selectedBus = bus;
          this.busSearch = bus.number;
          this.busOpen = false;
        },

        async addStorageUnit() {
          if (!this.newPenId) return;
          const res = await fetch(createStorageUnitUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
            body: JSON.stringify({ pen_id: this.newPenId }),
          });
          if (!res.ok) return;
          const unit = await res.json();
          this.storageUnits.push(unit);
          this.newUnitId = unit.id;
          this.newPenId = '';
        },
      };
    }
  </script>
@endsection
