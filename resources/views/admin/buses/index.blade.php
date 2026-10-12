@extends('admin.layout')

@section('title', 'Buses')

@section('content')
  <div class="fade-in flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight lg:hidden">Bus Details</h2>
      <p class="text-gray-500 text-sm mt-1">Manage every bus in the Gamana TV network.</p>
    </div>
    <a href="{{ route('admin.buses.create') }}" class="inline-flex items-center justify-center gap-2 bg-brand-red hover:bg-brand-darkRed active:scale-[0.98] text-white px-5 py-3 rounded-full font-bold text-sm tracking-wide shadow-md shadow-brand-red/25 transition-all duration-300">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
      Add Bus
    </a>
  </div>

  <!-- Stats -->
  <div class="fade-in grid grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
      <p class="text-2xl font-black text-gray-900">{{ $stats['total'] }}</p>
      <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mt-1">Total Buses</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
      <p class="text-2xl font-black text-blue-600">{{ $stats['fixed'] }}</p>
      <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mt-1">Fixed Schedule</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
      <p class="text-2xl font-black text-amber-600">{{ $stats['rotation'] }}</p>
      <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mt-1">Rotation</p>
    </div>
  </div>

  <form method="GET" class="fade-in mb-6">
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
    <div class="fade-in bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
      <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16V7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v9M4 16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M4 16h16M8 11h8M7 20h.01M17 20h.01" /></svg>
      <p class="text-gray-500 text-sm">No buses found{{ $search ? " for \"$search\"" : '' }}.</p>
    </div>
  @else
    <div class="fade-in grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
      @foreach ($buses as $bus)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group">
          <div class="relative h-40 bg-gray-100">
            @if ($bus->front_image)
              <img src="{{ asset('images/uploads/'.$bus->front_image) }}" alt="{{ $bus->number }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            @else
              <div class="w-full h-full flex items-center justify-center text-gray-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16V7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v9M4 16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M4 16h16M8 11h8M7 20h.01M17 20h.01" />
                </svg>
              </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
            <span class="absolute top-3 right-3 bg-white/95 backdrop-blur px-2.5 py-1 rounded-full text-[11px] font-bold text-gray-700 uppercase tracking-wide">
              {{ $bus->city->name }}
            </span>
            <span class="absolute bottom-3 left-3 text-[11px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $bus->schedule_type === 'fixed' ? 'bg-blue-500/90 text-white' : 'bg-amber-500/90 text-white' }}">
              {{ $bus->schedule_type }}
            </span>
          </div>

          <div class="p-5">
            <h3 class="font-black text-lg text-gray-900 tracking-tight">{{ $bus->number }}</h3>
            <p class="text-gray-500 text-sm mb-4 truncate">{{ $bus->name ?: ($bus->route ? "{$bus->route->start_place} → {$bus->route->end_place}" : 'No route set') }}</p>

            <div class="flex items-center gap-4 text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-4">
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z" /></svg>
                {{ $bus->drivers_count }} driver{{ $bus->drivers_count === 1 ? '' : 's' }}
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                {{ $bus->routes_per_day ?? '—' }}/day
              </span>
            </div>

            <div class="flex items-center gap-2">
              <a href="{{ route('admin.buses.show', $bus) }}" class="flex-1 text-center text-xs font-bold text-gray-700 border border-gray-200 rounded-full py-2 hover:bg-gray-50 transition-colors duration-200">
                View
              </a>
              <a href="{{ route('admin.buses.edit', $bus) }}" class="flex-1 text-center text-xs font-bold text-white bg-gray-900 rounded-full py-2 hover:bg-gray-800 transition-colors duration-200">
                Edit
              </a>
              <form method="POST" action="{{ route('admin.buses.destroy', $bus) }}" onsubmit="return confirm('Delete bus {{ $bus->number }}? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-full border border-red-100 text-brand-red hover:bg-red-50 transition-colors duration-200">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0-1 13a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7h12Z" />
                  </svg>
                </button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-8">
      {{ $buses->links() }}
    </div>
  @endif
@endsection
