@extends('admin.layout')
@section('title', 'Owners')
@section('content')
  <div class="fade-in mb-6">
    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight lg:hidden">Owners</h2>
    <p class="text-gray-500 text-sm mt-1">Bus owners that can be linked to a bus record.</p>
  </div>

  <form method="POST" action="{{ route('admin.owners.store') }}" class="fade-in bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
    @csrf
    <h3 class="font-black text-base sm:text-lg text-gray-900 mb-5">Add New Owner</h3>
    <div class="flex flex-wrap gap-3 items-end">
      <div class="flex-1 min-w-[180px]">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Name</label>
        <input type="text" name="name" required placeholder="Full name" class="w-full px-5 py-3.5 rounded-full border-2 border-gray-200 text-base focus:outline-none focus:ring-4 focus:ring-brand-red/15 focus:border-brand-red transition-all duration-200" />
      </div>
      <div class="flex-1 min-w-[160px]">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Phone</label>
        <input type="text" name="phone" placeholder="07X XXX XXXX" class="w-full px-5 py-3.5 rounded-full border-2 border-gray-200 text-base focus:outline-none focus:ring-4 focus:ring-brand-red/15 focus:border-brand-red transition-all duration-200" />
      </div>
      <button class="bg-brand-red hover:bg-brand-darkRed active:scale-[0.98] hover:scale-[1.02] text-white px-8 py-3.5 rounded-full font-black text-base shadow-lg shadow-brand-red/30 transition-all duration-300">Add Owner</button>
    </div>
  </form>

  <div class="fade-in bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    @forelse ($owners as $owner)
      <div x-data="{ editing: false }" class="{{ !$loop->last ? 'border-b border-gray-100' : '' }}">
        <!-- View mode -->
        <div x-show="!editing" class="flex items-center justify-between px-6 py-5 hover:bg-gray-50/60 transition-colors duration-200">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-12 h-12 rounded-full bg-brand-red/10 text-brand-red flex items-center justify-center font-bold text-base shrink-0">
              {{ strtoupper(substr($owner->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
              <p class="font-bold text-gray-900 truncate">{{ $owner->name }}</p>
              <p class="text-xs text-gray-500">{{ $owner->phone ?: 'No phone number' }}</p>
            </div>
          </div>
          <div class="flex items-center gap-2 shrink-0">
            <button type="button" @click="editing = true" class="text-sm font-bold text-gray-600 border-2 border-gray-200 rounded-full px-5 py-2.5 hover:bg-gray-50 transition-colors duration-200">Edit</button>
            <form method="POST" action="{{ route('admin.owners.destroy', $owner) }}" onsubmit="return confirm('Delete {{ $owner->name }}?');">
              @csrf @method('DELETE')
              <button class="w-10 h-10 flex items-center justify-center rounded-full border-2 border-red-100 text-brand-red hover:bg-red-50 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0-1 13a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7h12Z" /></svg>
              </button>
            </form>
          </div>
        </div>

        <!-- Edit mode -->
        <form x-show="editing" method="POST" action="{{ route('admin.owners.update', $owner) }}" class="flex flex-wrap items-end gap-3 px-6 py-4 bg-gray-50/60">
          @csrf @method('PUT')
          <div class="flex-1 min-w-[160px]">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Name</label>
            <input type="text" name="name" value="{{ $owner->name }}" required class="w-full px-4 py-2.5 rounded-full border-2 border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-red/15 focus:border-brand-red" />
          </div>
          <div class="flex-1 min-w-[140px]">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Phone</label>
            <input type="text" name="phone" value="{{ $owner->phone }}" class="w-full px-4 py-2.5 rounded-full border-2 border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-red/15 focus:border-brand-red" />
          </div>
          <button type="button" @click="editing = false" class="text-sm font-bold text-gray-600 px-4 py-2.5">Cancel</button>
          <button type="submit" class="bg-brand-red hover:bg-brand-darkRed text-white text-sm font-bold rounded-full px-6 py-2.5 shadow-md shadow-brand-red/25 transition-all duration-200">Save</button>
        </form>
      </div>
    @empty
      <p class="px-6 py-10 text-sm text-gray-400 text-center">No owners yet. Add one above.</p>
    @endforelse
  </div>
  <div class="mt-6">{{ $owners->links() }}</div>
@endsection
