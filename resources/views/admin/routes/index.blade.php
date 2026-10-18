@extends('admin.layout')
@section('title', 'Routes')
@section('content')
  <div class="fade-in mb-6">
    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight lg:hidden">Routes</h2>
    <p class="text-gray-500 text-sm mt-1">Fixed start/end combinations buses can be assigned to. Route name is optional.</p>
  </div>

  <form method="POST" action="{{ route('admin.routes.store') }}" class="fade-in bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
    @csrf
    <h3 class="font-black text-base sm:text-lg text-gray-900 mb-5">Add New Route</h3>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
      <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Start</label>
        <input type="text" name="start_place" required placeholder="COLOMBO" class="w-full px-5 py-3.5 rounded-full border-2 border-gray-200 text-base uppercase focus:outline-none focus:ring-4 focus:ring-brand-red/15 focus:border-brand-red transition-all duration-200" />
      </div>
      <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Route (optional)</label>
        <input type="text" name="route" placeholder="HIGH LEVEL RD" class="w-full px-5 py-3.5 rounded-full border-2 border-gray-200 text-base uppercase focus:outline-none focus:ring-4 focus:ring-brand-red/15 focus:border-brand-red transition-all duration-200" />
      </div>
      <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">End</label>
        <input type="text" name="end_place" required placeholder="KANDY" class="w-full px-5 py-3.5 rounded-full border-2 border-gray-200 text-base uppercase focus:outline-none focus:ring-4 focus:ring-brand-red/15 focus:border-brand-red transition-all duration-200" />
      </div>
      <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Note</label>
        <input type="text" name="note" placeholder="Optional" class="w-full px-5 py-3.5 rounded-full border-2 border-gray-200 text-base focus:outline-none focus:ring-4 focus:ring-brand-red/15 focus:border-brand-red transition-all duration-200" />
      </div>
    </div>
    <button class="mt-4 bg-brand-red hover:bg-brand-darkRed active:scale-[0.98] hover:scale-[1.02] text-white px-8 py-3.5 rounded-full font-black text-base shadow-lg shadow-brand-red/30 transition-all duration-300">Add Route</button>
  </form>

  <div class="fade-in bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    @forelse ($routes as $route)
      <div x-data="{ editing: false }" class="{{ !$loop->last ? 'border-b border-gray-100' : '' }}">
        <!-- View mode -->
        <div x-show="!editing" class="flex items-center justify-between px-6 py-5 hover:bg-gray-50/60 transition-colors duration-200">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-12 h-12 rounded-full bg-brand-red/10 text-brand-red flex items-center justify-center shrink-0">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 0 1 3 16.382V5.618a1 1 0 0 1 1.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0 0 21 18.382V7.618a1 1 0 0 0-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
            </div>
            <div class="min-w-0">
              <p class="font-bold text-gray-900 truncate">
                {{ $route->start_place }}
                @if ($route->route) <span class="text-gray-300">&rarr;</span> {{ $route->route }} @endif
                <span class="text-gray-300">&rarr;</span> {{ $route->end_place }}
              </p>
              <p class="text-xs text-gray-500 truncate">{{ $route->note ?: 'No note' }}</p>
            </div>
          </div>
          <div class="flex items-center gap-2 shrink-0">
            <button type="button" @click="editing = true" class="text-sm font-bold text-gray-600 border-2 border-gray-200 rounded-full px-5 py-2.5 hover:bg-gray-50 transition-colors duration-200">Edit</button>
            <form method="POST" action="{{ route('admin.routes.destroy', $route) }}" onsubmit="return confirm('Delete this route?');">
              @csrf @method('DELETE')
              <button class="w-10 h-10 flex items-center justify-center rounded-full border-2 border-red-100 text-brand-red hover:bg-red-50 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0-1 13a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7h12Z" /></svg>
              </button>
            </form>
          </div>
        </div>

        <!-- Edit mode -->
        <form x-show="editing" method="POST" action="{{ route('admin.routes.update', $route) }}" class="px-6 py-4 bg-gray-50/60">
          @csrf @method('PUT')
          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
            <input type="text" name="start_place" value="{{ $route->start_place }}" required placeholder="Start" class="w-full px-4 py-2.5 rounded-full border-2 border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-red/15 focus:border-brand-red uppercase" />
            <input type="text" name="route" value="{{ $route->route }}" placeholder="Route (optional)" class="w-full px-4 py-2.5 rounded-full border-2 border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-red/15 focus:border-brand-red uppercase" />
            <input type="text" name="end_place" value="{{ $route->end_place }}" required placeholder="End" class="w-full px-4 py-2.5 rounded-full border-2 border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-red/15 focus:border-brand-red uppercase" />
            <input type="text" name="note" value="{{ $route->note }}" placeholder="Note" class="w-full px-4 py-2.5 rounded-full border-2 border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-red/15 focus:border-brand-red" />
          </div>
          <div class="flex gap-3">
            <button type="button" @click="editing = false" class="text-sm font-bold text-gray-600 px-4 py-2.5">Cancel</button>
            <button type="submit" class="bg-brand-red hover:bg-brand-darkRed text-white text-sm font-bold rounded-full px-6 py-2.5 shadow-md shadow-brand-red/25 transition-all duration-200">Save</button>
          </div>
        </form>
      </div>
    @empty
      <p class="px-6 py-10 text-sm text-gray-400 text-center">No routes yet. Add one above.</p>
    @endforelse
  </div>
  <div class="mt-6">{{ $routes->links() }}</div>
@endsection
