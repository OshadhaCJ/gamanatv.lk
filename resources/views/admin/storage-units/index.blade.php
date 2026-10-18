@extends('admin.layout')
@section('title', 'Storage Units')
@section('content')
  <div class="fade-in mb-6">
    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight lg:hidden">Storage Units</h2>
    <p class="text-gray-500 text-sm mt-1">Storage units that can be assigned to a bus (one device per bus).</p>
  </div>

  <form method="POST" action="{{ route('admin.storage-units.store') }}" class="fade-in bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
    @csrf
    <h3 class="font-black text-base sm:text-lg text-gray-900 mb-5">Add New Storage Unit</h3>
    <div class="flex flex-wrap gap-3 items-end">
      <div class="flex-1 min-w-[180px]">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Pen ID</label>
        <input type="text" name="pen_id" required inputmode="numeric" pattern="[0-9]*" placeholder="123" class="w-full px-5 py-3.5 rounded-full border-2 border-gray-200 text-base focus:outline-none focus:ring-4 focus:ring-brand-red/15 focus:border-brand-red transition-all duration-200" />
      </div>
      <button class="bg-brand-red hover:bg-brand-darkRed active:scale-[0.98] hover:scale-[1.02] text-white px-8 py-3.5 rounded-full font-black text-base shadow-lg shadow-brand-red/30 transition-all duration-300">Add Storage Unit</button>
    </div>
  </form>

  <div class="fade-in bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    @forelse ($units as $unit)
      <div x-data="{ editing: false }" class="{{ !$loop->last ? 'border-b border-gray-100' : '' }}">
        <!-- View mode -->
        <div x-show="!editing" class="flex items-center justify-between px-6 py-5 hover:bg-gray-50/60 transition-colors duration-200">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-12 h-12 rounded-full bg-brand-red/10 text-brand-red flex items-center justify-center shrink-0">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 19h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Zm1-9h8v6H8v-6Z" /></svg>
            </div>
            <div class="min-w-0">
              <p class="font-bold text-gray-900 truncate">{{ $unit->pen_id }}</p>
              <p class="text-xs text-gray-500">
                Last with: <span class="font-semibold text-gray-600">{{ $unit->lastProcessingUnit?->device_id ?: '—' }}</span>
                @if ($unit->last_paired_at)
                  &middot; changed {{ $unit->last_paired_at->diffForHumans() }}
                @endif
              </p>
            </div>
          </div>
          <div class="flex items-center gap-3 shrink-0">
            <span class="text-[11px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $unit->bus_count ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500' }}">
              {{ $unit->bus_count ? 'Assigned' : 'Unassigned' }}
            </span>
            <button type="button" @click="editing = true" class="text-sm font-bold text-gray-600 border-2 border-gray-200 rounded-full px-5 py-2.5 hover:bg-gray-50 transition-colors duration-200">Edit</button>
            <form method="POST" action="{{ route('admin.storage-units.destroy', $unit) }}" onsubmit="return confirm('Delete storage unit {{ $unit->pen_id }}?');">
              @csrf @method('DELETE')
              <button class="w-10 h-10 flex items-center justify-center rounded-full border-2 border-red-100 text-brand-red hover:bg-red-50 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0-1 13a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7h12Z" /></svg>
              </button>
            </form>
          </div>
        </div>

        <!-- Edit mode -->
        <form x-show="editing" method="POST" action="{{ route('admin.storage-units.update', $unit) }}" class="flex flex-wrap items-end gap-3 px-6 py-4 bg-gray-50/60">
          @csrf @method('PUT')
          <div class="flex-1 min-w-[160px]">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Pen ID</label>
            <input type="text" name="pen_id" value="{{ $unit->pen_id }}" required inputmode="numeric" pattern="[0-9]*" class="w-full px-4 py-2.5 rounded-full border-2 border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-red/15 focus:border-brand-red" />
          </div>
          <button type="button" @click="editing = false" class="text-sm font-bold text-gray-600 px-4 py-2.5">Cancel</button>
          <button type="submit" class="bg-brand-red hover:bg-brand-darkRed text-white text-sm font-bold rounded-full px-6 py-2.5 shadow-md shadow-brand-red/25 transition-all duration-200">Save</button>
        </form>
      </div>
    @empty
      <p class="px-6 py-10 text-sm text-gray-400 text-center">No storage units yet. Add one above.</p>
    @endforelse
  </div>
  <div class="mt-6">{{ $units->links() }}</div>
@endsection
