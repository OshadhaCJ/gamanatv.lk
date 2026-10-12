@extends('admin.layout')

@section('title', 'Live Dashboard')

@section('content')
  @php
    $initialData = collect($data)->mapWithKeys(fn ($d, $busId) => [
        $busId => [
            'bus_id' => $busId,
            'status' => $d['ping']?->status,
            'play_minutes' => $d['play_minutes'],
            'last_reported' => $d['ping']?->recorded_at?->diffForHumans(),
        ],
    ]);
  @endphp
  <div
    x-data='liveDashboard("{{ route('admin.dashboard.data') }}", @json($initialData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT))'
    x-init="start()"
    class="fade-in"
  >
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight lg:hidden">Live Dashboard</h2>
        <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
          <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
          </span>
          Auto-updating every 10 seconds &middot; last refreshed <span x-text="lastRefreshed"></span>
        </p>
      </div>
      <a href="{{ route('admin.buses.create') }}" class="inline-flex items-center justify-center gap-2 bg-brand-red hover:bg-brand-darkRed active:scale-[0.98] text-white px-5 py-3 rounded-full font-bold text-sm tracking-wide shadow-md shadow-brand-red/25 transition-all duration-300">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
        Add Bus
      </a>
    </div>

    @if (count($alerts))
      <div class="space-y-3 mb-8">
        @foreach ($alerts as $alert)
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              <p class="text-sm text-amber-800">
                <span class="font-black">{{ $alert['bus']->number }}</span> has been running for
                <span class="font-bold">{{ $alert['elapsed_minutes'] }} min</span>
                — over its route time + markup ({{ $alert['threshold_minutes'] }} min allowed).
              </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
              <form method="POST" action="{{ route('admin.dashboard.alerts.dismiss', $alert['bus']) }}">
                @csrf
                <input type="hidden" name="mode" value="now" />
                <button class="text-xs font-bold text-amber-700 border border-amber-300 rounded-full px-4 py-2 hover:bg-amber-100 transition-colors duration-200">
                  Dismiss for now
                </button>
              </form>
              <form method="POST" action="{{ route('admin.dashboard.alerts.dismiss', $alert['bus']) }}">
                @csrf
                <input type="hidden" name="mode" value="forever" />
                <button class="text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-full px-4 py-2 transition-colors duration-200">
                  Dismiss forever
                </button>
              </form>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    @if (count($swapFaults))
      <div class="space-y-3 mb-8">
        @foreach ($swapFaults as $fault)
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-red-50 border border-red-200 rounded-2xl px-5 py-4">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-brand-red shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
              </svg>
              <p class="text-sm text-red-800">
                <span class="font-black">{{ $fault->bus->number }}</span> storage swap didn't verify —
                {{ $fault->fault_reason }}
              </p>
            </div>
            <form method="POST" action="{{ route('admin.storage-swaps.acknowledge', $fault) }}">
              @csrf
              <button class="text-xs font-bold text-red-700 border border-red-300 rounded-full px-4 py-2 hover:bg-red-100 transition-colors duration-200 shrink-0">
                Acknowledge
              </button>
            </form>
          </div>
        @endforeach
      </div>
    @endif

    @if (count($attendanceAlerts))
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4 mb-8">
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <p class="text-sm text-amber-800">
            <span class="font-black">{{ count($attendanceAlerts) }}</span> bus{{ count($attendanceAlerts) === 1 ? '' : 'es' }}
            still not marked for today, past noon:
            <span class="font-bold">{{ $attendanceAlerts->pluck('number')->join(', ') }}</span>
          </p>
        </div>
        <a href="{{ route('admin.attendance') }}" class="text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-full px-4 py-2 transition-colors duration-200 shrink-0">
          Mark Attendance
        </a>
      </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-black text-gray-900">{{ $stats['total'] }}</p>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mt-1">Total Buses</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-black text-green-600" x-text="counts.playing">{{ $stats['playing'] }}</p>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mt-1">Playing Now</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-black text-gray-500" x-text="counts.off">{{ $stats['off'] }}</p>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mt-1">Off</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-black text-amber-600" x-text="counts.noData">{{ $stats['no_data'] }}</p>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mt-1">No Data</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 col-span-2 lg:col-span-1">
        <p class="text-2xl font-black text-brand-red" x-text="formatHours(counts.totalMinutes)">{{ intdiv($stats['total_play_minutes'], 60) }}h {{ $stats['total_play_minutes'] % 60 }}m</p>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mt-1">Network Play Time Today</p>
      </div>
    </div>

    <form method="GET" class="mb-6">
      <div class="relative max-w-sm">
        <svg class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M11 19a8 8 0 1 1 0-16 8 8 0 0 1 0 16Z" />
        </svg>
        <input
          type="text" name="search" value="{{ $search }}" placeholder="Search by bus number or name..."
          class="w-full pl-11 pr-4 py-2.5 rounded-full border border-gray-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand-red/40 focus:border-brand-red transition-all duration-200"
        />
      </div>
    </form>

    @if ($buses->isEmpty())
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <p class="text-gray-500 text-sm">No buses found{{ $search ? " for \"$search\"" : '' }}.</p>
      </div>
    @else
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-x-auto">
        <table class="w-full text-sm min-w-[900px]">
          <thead>
            <tr class="text-left text-[11px] font-bold text-gray-400 uppercase tracking-wide border-b border-gray-100">
              <th class="px-5 py-3">Bus</th>
              <th class="px-5 py-3">City</th>
              <th class="px-5 py-3">Status</th>
              <th class="px-5 py-3">Play Time Today</th>
              <th class="px-5 py-3">Last Reported</th>
              <th class="px-5 py-3">Processing Unit</th>
              <th class="px-5 py-3">Storage Unit</th>
              <th class="px-5 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            @foreach ($buses as $bus)
              @php $d = $data[$bus->id]; @endphp
              <tr
                x-data="{ bus: buses[{{ $bus->id }}] }"
                x-effect="bus = buses[{{ $bus->id }}] || bus"
                class="hover:bg-gray-50/60 transition-colors duration-200"
              >
                <td class="px-5 py-4">
                  <p class="font-bold text-gray-900">{{ $bus->number }}</p>
                  @if ($bus->name)<p class="text-xs text-gray-500">{{ $bus->name }}</p>@endif
                </td>
                <td class="px-5 py-4 text-gray-600">{{ $bus->city->name }}</td>
                <td class="px-5 py-4">
                  <span
                    class="inline-flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wide px-2.5 py-1 rounded-full"
                    :class="{
                      'bg-green-50 text-green-600': bus.status === 'play',
                      'bg-blue-50 text-blue-600': bus.status === 'on',
                      'bg-amber-50 text-amber-600': bus.status === 'pause',
                      'bg-gray-100 text-gray-500': bus.status === 'off',
                      'bg-gray-50 text-gray-400': !bus.status,
                    }"
                  >
                    <span class="w-1.5 h-1.5 rounded-full bg-current" :class="bus.status === 'play' ? 'animate-pulse' : ''"></span>
                    <span x-text="bus.status || 'No data'"></span>
                  </span>
                </td>
                <td class="px-5 py-4 font-bold text-gray-900">
                  <span x-text="formatHours(bus.play_minutes)">{{ intdiv($d['play_minutes'], 60) }}h {{ $d['play_minutes'] % 60 }}m</span>
                </td>
                <td class="px-5 py-4 text-gray-500">
                  <span x-text="bus.last_reported || '—'">{{ $d['ping']?->recorded_at?->diffForHumans() ?? '—' }}</span>
                </td>
                <td class="px-5 py-4 text-gray-600">{{ $bus->processingUnit?->device_id ?: '—' }}</td>
                <td class="px-5 py-4 text-gray-600">{{ $bus->storageUnit?->pen_id ?: '—' }}</td>
                <td class="px-5 py-4 text-right">
                  <a href="{{ route('admin.buses.show', $bus) }}" class="text-xs font-bold text-brand-red hover:underline">View</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <script>
    function liveDashboard(dataUrl, initialData) {
      return {
        buses: initialData,
        lastRefreshed: 'just now',
        counts: { playing: {{ $stats['playing'] }}, off: {{ $stats['off'] }}, noData: {{ $stats['no_data'] }}, totalMinutes: {{ $stats['total_play_minutes'] }} },

        formatHours(minutes) {
          minutes = minutes || 0;
          return Math.floor(minutes / 60) + 'h ' + (minutes % 60) + 'm';
        },

        async refresh() {
          try {
            const res = await fetch(dataUrl, { headers: { Accept: 'application/json' } });
            if (!res.ok) return;
            const rows = await res.json();

            const next = {};
            let playing = 0, off = 0, noData = 0, totalMinutes = 0;
            for (const row of rows) {
              next[row.bus_id] = row;
              if (row.status === 'play') playing++;
              else if (row.status === 'off') off++;
              if (!row.status) noData++;
              totalMinutes += row.play_minutes || 0;
            }
            this.buses = next;
            this.counts = { playing, off, noData, totalMinutes };
            this.lastRefreshed = 'just now';
          } catch (e) { /* keep showing last known data */ }
        },

        start() {
          this.refresh();
          setInterval(() => this.refresh(), 10000);
        },
      };
    }
  </script>
@endsection
