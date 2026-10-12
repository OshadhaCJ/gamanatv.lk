@extends('admin.layout')

@section('title', 'Field Incidents')

@section('content')
  <div class="fade-in mb-6">
    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight lg:hidden">Field Incidents</h2>
    <p class="text-gray-500 text-sm mt-1">Log what you saw out in the field — every entry is tagged with who reported it.</p>
  </div>

  <form method="POST" action="{{ route('admin.incidents.store') }}" class="fade-in bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
    @csrf
    <h3 class="font-black text-sm text-gray-900 mb-4">Log an Observation</h3>

    <div class="grid sm:grid-cols-2 gap-3 mb-3">
      <div>
        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Bus</label>
        <select name="bus_id" required class="w-full px-4 py-2.5 rounded-full border border-gray-200 text-sm">
          <option value="">Select bus</option>
          @foreach ($buses as $bus)
            <option value="{{ $bus->id }}">{{ $bus->number }}{{ $bus->name ? ' — '.$bus->name : '' }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">What did you see?</label>
        <select name="type" required class="w-full px-4 py-2.5 rounded-full border border-gray-200 text-sm">
          @foreach (\App\Models\BusIncident::LABELS as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="mb-4">
      <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Note (optional)</label>
      <input type="text" name="note" placeholder="Any extra detail..." class="w-full px-4 py-2.5 rounded-full border border-gray-200 text-sm" />
    </div>

    <button class="bg-brand-red hover:bg-brand-darkRed active:scale-[0.98] text-white px-6 py-2.5 rounded-full font-bold text-sm shadow-md shadow-brand-red/25 transition-all duration-300">
      Log Incident
    </button>
  </form>

  <div class="fade-in bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    @forelse ($incidents as $incident)
      <div class="flex items-center justify-between gap-3 px-6 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
        <div class="flex items-center gap-3 min-w-0">
          <div class="w-10 h-10 rounded-full bg-brand-red/10 text-brand-red flex items-center justify-center font-bold text-xs shrink-0">
            {{ strtoupper(substr($incident->reportedBy->name, 0, 1)) }}
          </div>
          <div class="min-w-0">
            <p class="font-bold text-gray-900 truncate">{{ $incident->bus->number }} &mdash; {{ \App\Models\BusIncident::LABELS[$incident->type] }}</p>
            <p class="text-xs text-gray-500 truncate">
              {{ $incident->note ?: 'No note' }} &middot; by {{ $incident->reportedBy->name }} &middot; {{ $incident->occurred_at->diffForHumans() }}
            </p>
          </div>
        </div>
      </div>
    @empty
      <p class="px-6 py-10 text-sm text-gray-400 text-center">No incidents logged yet.</p>
    @endforelse
  </div>
  <div class="mt-6">{{ $incidents->links() }}</div>
@endsection
