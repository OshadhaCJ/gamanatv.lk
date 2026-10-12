@extends('admin.layout')
@section('title', $bus->number)
@section('content')
  <div class="fade-in max-w-5xl mx-auto">
    <div class="flex items-center justify-between gap-4 mb-8">
      <div class="flex items-center gap-3">
        <a href="{{ route('admin.buses.index') }}" class="w-9 h-9 rounded-full border border-gray-200 bg-white flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors duration-200">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">{{ $bus->number }}{{ $bus->name ? ' — '.$bus->name : '' }}</h2>
      </div>
      <a href="{{ route('admin.buses.edit', $bus) }}" class="inline-flex items-center gap-2 bg-gray-900 text-white px-5 py-2.5 rounded-full font-bold text-sm">Edit</a>
    </div>

    <!-- Live Status -->
    <div class="grid sm:grid-cols-3 gap-4 mb-8">
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-2">Current Status</p>
        @php
          $statusColors = ['play' => 'bg-green-50 text-green-600', 'on' => 'bg-blue-50 text-blue-600', 'pause' => 'bg-amber-50 text-amber-600', 'off' => 'bg-gray-100 text-gray-500'];
          $status = $currentPing?->status;
        @endphp
        <span class="inline-flex items-center gap-2 text-sm font-black uppercase tracking-wide px-3 py-1.5 rounded-full {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-400' }}">
          <span class="w-2 h-2 rounded-full bg-current"></span>
          {{ $status ?? 'No data yet' }}
        </span>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-2">Today's Play Time</p>
        <p class="text-xl font-black text-gray-900">{{ intdiv($todayPlayMinutes, 60) }}h {{ $todayPlayMinutes % 60 }}m</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-2">Last Reported</p>
        <p class="text-sm font-bold text-gray-900">{{ $currentPing?->recorded_at?->diffForHumans() ?? '—' }}</p>
      </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      @foreach (['front_image'=>'Front','side_image'=>'Side','tv_front_image'=>'TV Front','tv_back_image'=>'TV Back'] as $field => $label)
        <div class="rounded-2xl overflow-hidden border border-gray-100 bg-gray-50 aspect-square flex items-center justify-center">
          @if ($bus->$field)
            <img src="{{ asset('images/uploads/'.$bus->$field) }}" class="w-full h-full object-cover" />
          @else
            <span class="text-xs text-gray-400 font-semibold">{{ $label }} — no image</span>
          @endif
        </div>
      @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
      <section class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6">
        <div>
          <h2 class="font-black text-gray-900 mb-3">Overview</h2>
          <dl class="grid sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div><dt class="text-gray-400 font-semibold">City</dt><dd class="font-bold text-gray-900">{{ $bus->city->name }}</dd></div>
            <div><dt class="text-gray-400 font-semibold">District</dt><dd class="font-bold text-gray-900">{{ $bus->city->district->name }}</dd></div>
            <div><dt class="text-gray-400 font-semibold">Province</dt><dd class="font-bold text-gray-900">{{ $bus->city->district->province->name }}</dd></div>
            <div><dt class="text-gray-400 font-semibold">Owner</dt><dd class="font-bold text-gray-900">{{ $bus->owner?->name ?: '—' }} {{ $bus->owner?->phone }}</dd></div>
          </dl>
        </div>

        <div>
          <h2 class="font-black text-gray-900 mb-3">Drivers</h2>
          @forelse ($bus->drivers as $driver)
            <p class="text-sm"><span class="font-bold text-gray-900">{{ $driver->name }}</span> <span class="text-gray-500">{{ $driver->phone }}</span></p>
          @empty
            <p class="text-sm text-gray-400">—</p>
          @endforelse
        </div>

        <div>
          <h2 class="font-black text-gray-900 mb-3">Conductors</h2>
          @forelse ($bus->conductors as $conductor)
            <p class="text-sm"><span class="font-bold text-gray-900">{{ $conductor->name }}</span> <span class="text-gray-500">{{ $conductor->phone }}</span></p>
          @empty
            <p class="text-sm text-gray-400">—</p>
          @endforelse
        </div>

        <div>
          <h2 class="font-black text-gray-900 mb-3">Schedule</h2>
          @if ($bus->schedule_type === 'fixed')
            <p class="text-sm"><span class="text-gray-400 font-semibold">Fixed Time:</span> <span class="font-bold text-gray-900">{{ $bus->fixed_time ? \Illuminate\Support\Carbon::parse($bus->fixed_time)->format('g:i A') : '—' }}</span></p>
          @else
            <p class="text-sm"><span class="text-gray-400 font-semibold">Rotation:</span> <span class="font-bold text-gray-900">{{ $bus->rotation_description ?: '—' }}</span></p>
          @endif
        </div>

        <div>
          <h2 class="font-black text-gray-900 mb-3">Route Operations</h2>
          <dl class="grid sm:grid-cols-3 gap-x-6 gap-y-3 text-sm">
            <div><dt class="text-gray-400 font-semibold">Routes / Day</dt><dd class="font-bold text-gray-900">{{ $bus->routes_per_day ?? '—' }}</dd></div>
            <div><dt class="text-gray-400 font-semibold">Avg Time / Route</dt><dd class="font-bold text-gray-900">{{ $bus->route_duration_minutes ? $bus->route_duration_minutes.' min' : '—' }}</dd></div>
            <div>
              <dt class="text-gray-400 font-semibold">Starting Place</dt>
              <dd class="font-bold text-gray-900">
                @if ($bus->starting_place_fixed && $bus->route)
                  {{ $bus->route->start_place }} &rarr; {{ $bus->route->route }} &rarr; {{ $bus->route->end_place }}
                @else
                  Not Fixed
                @endif
              </dd>
            </div>
          </dl>
        </div>

        <div>
          <h2 class="font-black text-gray-900 mb-3">Holiday Note</h2>
          <p class="text-sm font-bold text-gray-900">{{ $bus->holiday_note ?: '—' }}</p>
        </div>
      </section>

      <aside class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 h-fit space-y-6">
        <div>
          <h2 class="font-black text-gray-900 mb-2">TV Details</h2>
          <dl class="space-y-3 text-sm">
            <div><dt class="text-gray-400 font-semibold">Brand</dt><dd class="font-bold text-gray-900">{{ $bus->tv_brand ?: '—' }}</dd></div>
            <div><dt class="text-gray-400 font-semibold">Size</dt><dd class="font-bold text-gray-900">{{ $bus->tv_size ?: '—' }}</dd></div>
            <div><dt class="text-gray-400 font-semibold">Input</dt><dd class="font-bold text-gray-900">
              @if ($bus->input_type === 'tv') Smart TV (built-in)
              @elseif ($bus->input_type === 'set') Set-top Box
              @else — @endif
            </dd></div>
          </dl>
        </div>

        <div>
          <h2 class="font-black text-gray-900 mb-2">Processing Unit</h2>
          <dl class="space-y-3 text-sm">
            <div><dt class="text-gray-400 font-semibold">Device ID</dt><dd class="font-bold text-gray-900">{{ $bus->processingUnit?->device_id ?: 'Not assigned' }}</dd></div>
            @if ($bus->processingUnit?->telephone_number)
              <div><dt class="text-gray-400 font-semibold">Telephone</dt><dd class="font-bold text-gray-900">{{ $bus->processingUnit->telephone_number }}</dd></div>
            @endif
          </dl>
        </div>

        <div>
          <h2 class="font-black text-gray-900 mb-2">Storage Unit</h2>
          <dl class="space-y-3 text-sm">
            <div><dt class="text-gray-400 font-semibold">Pen ID</dt><dd class="font-bold text-gray-900">{{ $bus->storageUnit?->pen_id ?: 'Not assigned' }}</dd></div>
            @if ($bus->storageUnit)
              <div><dt class="text-gray-400 font-semibold">Last Processing Unit</dt><dd class="font-bold text-gray-900">{{ $bus->storageUnit->lastProcessingUnit?->device_id ?: '—' }}</dd></div>
              <div><dt class="text-gray-400 font-semibold">Last Changed</dt><dd class="font-bold text-gray-900">{{ $bus->storageUnit->last_paired_at?->diffForHumans() ?: '—' }}</dd></div>
            @endif
          </dl>
        </div>
      </aside>
    </div>
  </div>
@endsection
