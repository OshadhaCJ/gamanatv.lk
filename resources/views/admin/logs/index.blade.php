@extends('admin.layout')

@section('title', 'Logs')

@section('content')
  <div class="fade-in mb-6">
    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight lg:hidden">Logs</h2>
    <p class="text-gray-500 text-sm mt-1">
      Raw status pings from every device.
      @if ($flaggedCount)
        <span class="text-brand-red font-bold">{{ $flaggedCount }} flagged for review.</span>
      @endif
    </p>
  </div>

  <form method="GET" class="fade-in mb-6 flex flex-wrap items-center gap-3">
    <div class="relative max-w-xs">
      <svg class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M11 19a8 8 0 1 1 0-16 8 8 0 0 1 0 16Z" />
      </svg>
      <input
        type="text" name="search" value="{{ $search }}" placeholder="Search device or pen ID..."
        class="w-full pl-11 pr-4 py-2.5 rounded-full border border-gray-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand-red/40 focus:border-brand-red transition-all duration-200"
      />
    </div>
    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-full px-4 py-2.5 cursor-pointer">
      <input type="checkbox" name="flagged" value="1" {{ $flaggedOnly ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-gray-300 text-brand-red focus:ring-brand-red/40" />
      Flagged only
    </label>
  </form>

  @if ($pings->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
      <p class="text-gray-500 text-sm">No log entries found.</p>
    </div>
  @else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-x-auto">
      <table class="w-full text-sm min-w-[900px]">
        <thead>
          <tr class="text-left text-[11px] font-bold text-gray-400 uppercase tracking-wide border-b border-gray-100">
            <th class="px-5 py-3">Recorded At</th>
            <th class="px-5 py-3">Bus</th>
            <th class="px-5 py-3">Device ID</th>
            <th class="px-5 py-3">Pen ID</th>
            <th class="px-5 py-3">Status</th>
            <th class="px-5 py-3">Duration</th>
            <th class="px-5 py-3">Flag</th>
            <th class="px-5 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @foreach ($pings as $ping)
            <tr class="{{ $ping->is_flagged ? 'bg-red-50/50' : 'hover:bg-gray-50/60' }} transition-colors duration-200">
              <td class="px-5 py-3 text-gray-600 whitespace-nowrap">{{ $ping->recorded_at->format('Y-m-d H:i:s') }}</td>
              <td class="px-5 py-3">
                @if ($ping->bus)
                  <a href="{{ route('admin.buses.show', $ping->bus) }}" class="font-bold text-gray-900 hover:text-brand-red">{{ $ping->bus->number }}</a>
                @else
                  <span class="text-gray-400 italic">Unmatched</span>
                @endif
              </td>
              <td class="px-5 py-3 text-gray-600">{{ $ping->device_id }}</td>
              <td class="px-5 py-3 text-gray-600">{{ $ping->pen_id }}</td>
              <td class="px-5 py-3">
                <span class="text-[11px] font-black uppercase tracking-wide px-2.5 py-1 rounded-full
                  {{ match($ping->status) {
                    'play' => 'bg-green-50 text-green-600',
                    'on' => 'bg-blue-50 text-blue-600',
                    'pause' => 'bg-amber-50 text-amber-600',
                    'off' => 'bg-gray-100 text-gray-500',
                    default => 'bg-gray-50 text-gray-400',
                  } }}">
                  {{ $ping->status }}
                </span>
              </td>
              <td class="px-5 py-3 text-gray-600">{{ $ping->duration_seconds !== null ? $ping->duration_seconds.'s' : '—' }}</td>
              <td class="px-5 py-3 max-w-xs">
                @if ($ping->is_flagged)
                  <p class="text-xs font-bold text-brand-red">{{ $ping->flag_reason }}</p>
                @else
                  <span class="text-gray-300">—</span>
                @endif
              </td>
              <td class="px-5 py-3 text-right">
                @if ($ping->is_flagged)
                  <form method="POST" action="{{ route('admin.logs.resolve', $ping) }}">
                    @csrf
                    <button class="text-xs font-bold text-gray-600 border border-gray-200 rounded-full px-3 py-1.5 hover:bg-gray-50 transition-colors duration-200">
                      Mark Reviewed
                    </button>
                  </form>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-6">{{ $pings->links() }}</div>
  @endif
@endsection
