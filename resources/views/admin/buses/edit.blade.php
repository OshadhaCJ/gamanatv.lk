@extends('admin.layout')

@section('title', 'Edit Bus')

@section('content')
  <div class="fade-in max-w-5xl mx-auto pb-24">
    <div class="flex items-center gap-3 mb-8">
      <a href="{{ route('admin.buses.index') }}" class="w-9 h-9 rounded-full border border-gray-200 bg-white flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
      </a>
      <div>
        <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Edit Bus — {{ $bus->number }}</h2>
        <p class="text-gray-500 text-sm">Update the details for this bus.</p>
      </div>
    </div>

    @if ($errors->any())
      <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-brand-red">
        <ul class="list-disc list-inside space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @php
      $errorStepFields = [
        1 => ['city_id'],
        2 => ['number', 'name'],
        3 => ['owner_id', 'drivers.*', 'conductors.*'],
        4 => ['schedule_type', 'fixed_time', 'rotation_description'],
        5 => ['front_image', 'side_image', 'tv_front_image', 'tv_back_image'],
        6 => ['routes_per_day', 'route_duration_minutes', 'starting_place_fixed', 'route_id'],
        7 => ['tv_brand', 'tv_size', 'input_type'],
        8 => ['processing_unit_id', 'storage_unit_id'],
        9 => ['holiday_note'],
      ];
      $initialStep = 1;
      foreach ($errorStepFields as $stepNum => $fields) {
        if ($errors->hasAny($fields)) {
          $initialStep = $stepNum;
          break;
        }
      }
    @endphp

    <form
      method="POST"
      action="{{ route('admin.buses.update', $bus) }}"
      enctype="multipart/form-data"
      x-data="{
        submitting: false,
        step: {{ $initialStep }},
        totalSteps: 9,
        stepLabels: ['City', 'Bus Details', 'Contact Details', 'Schedule', 'Images', 'Route Operations', 'TV Details', 'Devices', 'Holiday Note'],
        nextStep() { if (this.step < this.totalSteps) this.step++; window.scrollTo({ top: 0, behavior: 'smooth' }); },
        prevStep() { if (this.step > 1) this.step--; window.scrollTo({ top: 0, behavior: 'smooth' }); },
      }"
      @submit="if (step < totalSteps) { $event.preventDefault(); nextStep(); } else { submitting = true; }"
    >
      @csrf
      @method('PUT')

      @include('admin.buses._form', ['provinces' => $provinces, 'bus' => $bus])

      <div class="sticky bottom-4 mt-8 flex items-center justify-between gap-3 bg-white/90 backdrop-blur border border-gray-100 shadow-lg rounded-full p-2">
        <template x-if="step === 1">
          <a href="{{ route('admin.buses.index') }}" class="px-6 py-2.5 rounded-full font-bold text-sm text-gray-600 hover:bg-gray-50 transition-colors duration-200">
            Cancel
          </a>
        </template>
        <template x-if="step > 1">
          <button type="button" @click="prevStep()" class="px-6 py-2.5 rounded-full font-bold text-sm text-gray-600 hover:bg-gray-50 transition-colors duration-200">
            Back
          </button>
        </template>

        <span class="text-xs font-bold text-gray-400" x-text="'Step ' + step + ' of ' + totalSteps"></span>

        <template x-if="step < totalSteps">
          <button type="submit" class="inline-flex items-center gap-2 px-8 py-2.5 rounded-full font-bold text-sm text-white bg-brand-red hover:bg-brand-darkRed active:scale-[0.98] shadow-md shadow-brand-red/25 transition-all duration-300">
            Next
          </button>
        </template>
        <template x-if="step === totalSteps">
          <button
            type="submit"
            :disabled="submitting"
            class="inline-flex items-center gap-2 px-8 py-2.5 rounded-full font-bold text-sm text-white bg-brand-red hover:bg-brand-darkRed active:scale-[0.98] disabled:opacity-70 shadow-md shadow-brand-red/25 transition-all duration-300"
          >
            <svg x-show="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.37 0 0 5.37 0 12h4Z"></path>
            </svg>
            <span x-text="submitting ? 'Saving...' : 'Update Bus'"></span>
          </button>
        </template>
      </div>
    </form>
  </div>
@endsection
