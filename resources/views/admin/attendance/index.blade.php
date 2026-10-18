@extends('admin.layout')

@section('title', 'Attendance')

@section('content')
  <div class="fade-in mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight lg:hidden">Attendance</h2>
      <p class="text-gray-500 text-sm mt-1">
        Mark each bus for <span class="font-bold text-gray-900">{{ $date->format('l, F j, Y') }}</span>{{ $isToday ? ' (today)' : '' }}.
        A bus auto-marks itself "Running" the moment a device ping comes in.
      </p>
    </div>
    <form method="GET" class="flex items-center gap-2">
      <input type="date" name="date" value="{{ $date->toDateString() }}" onchange="this.form.submit()" class="px-5 py-3 rounded-full border-2 border-gray-200 text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-brand-red/15 focus:border-brand-red transition-all duration-200" />
    </form>
  </div>

  <div class="fade-in bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    @foreach ($buses as $bus)
      @php $attendance = $bus->attendances->first(); @endphp
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-5 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
        <div class="min-w-0">
          <p class="font-bold text-gray-900">{{ $bus->number }}{{ $bus->name ? ' — '.$bus->name : '' }}</p>
          <p class="text-xs text-gray-500">{{ $bus->city->name }}</p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
          @if ($attendance)
            <span class="text-[11px] font-black uppercase tracking-wide px-3 py-1.5 rounded-full
              {{ match($attendance->status) {
                'running' => 'bg-green-50 text-green-600',
                'holiday' => 'bg-blue-50 text-blue-600',
                'couldnt_contact' => 'bg-red-50 text-brand-red',
              } }}">
              {{ str_replace('_', ' ', $attendance->status) }}
            </span>
            <span class="text-xs text-gray-400">
              {{ $attendance->auto_marked ? 'Auto-marked' : 'By '.($attendance->markedBy?->name ?? '—') }}
            </span>
          @elseif ($isToday && now()->hour >= 12)
            <span class="text-[11px] font-black uppercase tracking-wide px-3 py-1.5 rounded-full bg-amber-50 text-amber-600">
              Not marked — past noon
            </span>
          @else
            <span class="text-[11px] font-black uppercase tracking-wide px-3 py-1.5 rounded-full bg-gray-100 text-gray-400">
              Not marked yet
            </span>
          @endif

          <form method="POST" action="{{ route('admin.attendance.store') }}" class="flex items-center gap-1.5">
            @csrf
            <input type="hidden" name="bus_id" value="{{ $bus->id }}" />
            <input type="hidden" name="date" value="{{ $date->toDateString() }}" />
            <button type="submit" name="status" value="running" class="text-xs font-bold text-green-700 border-2 border-green-200 rounded-full px-4 py-2 hover:bg-green-50 hover:scale-105 transition-all duration-200">Running</button>
            <button type="submit" name="status" value="holiday" class="text-xs font-bold text-blue-700 border-2 border-blue-200 rounded-full px-4 py-2 hover:bg-blue-50 hover:scale-105 transition-all duration-200">Holiday</button>
            <button type="submit" name="status" value="couldnt_contact" class="text-xs font-bold text-brand-red border-2 border-red-200 rounded-full px-4 py-2 hover:bg-red-50 hover:scale-105 transition-all duration-200">Couldn't Contact</button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
@endsection
