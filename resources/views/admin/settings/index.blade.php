@extends('admin.layout')

@section('title', 'Settings')

@section('content')
  @php
    $groupLabels = [
      'google' => ['title' => 'Google Login', 'desc' => 'OAuth credentials used for "Continue with Google".'],
      'paniwida' => ['title' => 'SMS / WhatsApp Gateway (Paniwida.lk)', 'desc' => 'Credentials used to send OTP codes.'],
      'otp' => ['title' => 'OTP Delivery', 'desc' => 'How verification codes are sent during registration.'],
      'alerts' => ['title' => 'Alerts', 'desc' => 'Thresholds used to flag unusual bus activity.'],
      'webhook' => ['title' => 'Device Webhook', 'desc' => 'Shared password the in-bus devices must send with every status ping.'],
    ];
    $keyLabels = [
      'google_client_id' => 'Client ID',
      'google_client_secret' => 'Client Secret',
      'google_redirect_uri' => 'Redirect URI',
      'paniwida_api_url' => 'API URL',
      'paniwida_api_key' => 'API Key',
      'paniwida_sender_id' => 'Sender ID',
      'otp_preferred_method' => 'Preferred Method',
      'route_overrun_markup_percent' => 'Route Overrun Markup (%)',
      'webhook_password' => 'Webhook Password',
    ];
  @endphp

  <div class="fade-in max-w-3xl">
    <div class="mb-8">
      <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight lg:hidden">Settings</h2>
      <p class="text-gray-500 text-sm mt-1">Manage third-party credentials and app behaviour. Visible only to developers and admins.</p>
    </div>

    <form
      method="POST"
      action="{{ route('admin.settings.update') }}"
      x-data="{ submitting: false, reveal: {} }"
      @submit="submitting = true"
      class="space-y-8"
    >
      @csrf
      @method('PUT')

      @foreach ($groups as $group => $settings)
        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
          <h2 class="font-black text-lg text-gray-900">{{ $groupLabels[$group]['title'] ?? ucfirst($group) }}</h2>
          <p class="text-gray-500 text-sm mb-6">{{ $groupLabels[$group]['desc'] ?? '' }}</p>

          <div class="space-y-5">
            @foreach ($settings as $setting)
              <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                  {{ $keyLabels[$setting->key] ?? $setting->key }}
                </label>

                @if ($setting->key === 'route_overrun_markup_percent')
                  <div class="flex items-center gap-2 max-w-xs">
                    <input
                      type="number" min="0" max="500" step="1"
                      name="settings[{{ $setting->key }}]"
                      value="{{ $setting->value }}"
                      class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm"
                    />
                    <span class="text-sm font-bold text-gray-400">%</span>
                  </div>
                  <p class="text-[11px] text-gray-400 mt-1.5">A bus is flagged as overrunning once it's been running longer than its route duration + this markup.</p>
                @elseif ($setting->key === 'otp_preferred_method')
                  <select name="settings[{{ $setting->key }}]" class="w-full sm:w-96 px-4 py-3 rounded-xl border border-gray-200 text-sm">
                    <option value="sms" {{ $setting->value === 'sms' ? 'selected' : '' }}>SMS only</option>
                    <option value="whatsapp" {{ $setting->value === 'whatsapp' ? 'selected' : '' }}>WhatsApp only</option>
                    <option value="whatsapp_then_sms" {{ $setting->value === 'whatsapp_then_sms' ? 'selected' : '' }}>WhatsApp, fall back to SMS</option>
                  </select>
                @elseif ($setting->is_secret)
                  <div class="relative max-w-xl">
                    <input
                      :type="reveal['{{ $setting->key }}'] ? 'text' : 'password'"
                      name="settings[{{ $setting->key }}]"
                      placeholder="{{ $setting->value ? '••••••••••••' : 'Not set' }}"
                      autocomplete="new-password"
                      class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-200 text-sm"
                    />
                    <button
                      type="button"
                      @click="reveal['{{ $setting->key }}'] = !reveal['{{ $setting->key }}']"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 hover:text-brand-red"
                    >
                      <span x-text="reveal['{{ $setting->key }}'] ? 'Hide' : 'Show'"></span>
                    </button>
                  </div>
                  <p class="text-[11px] text-gray-400 mt-1.5">Leave blank to keep the current value.</p>
                @else
                  <input
                    type="text"
                    name="settings[{{ $setting->key }}]"
                    value="{{ $setting->value }}"
                    class="w-full max-w-xl px-4 py-3 rounded-xl border border-gray-200 text-sm"
                  />
                @endif
              </div>
            @endforeach
          </div>
        </section>
      @endforeach

      <div class="flex justify-end">
        <button
          type="submit"
          :disabled="submitting"
          class="inline-flex items-center gap-2 px-8 py-3 rounded-full font-bold text-sm text-white bg-brand-red hover:bg-brand-darkRed active:scale-[0.98] disabled:opacity-70 shadow-md shadow-brand-red/25 transition-all duration-300"
        >
          <svg x-show="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.37 0 0 5.37 0 12h4Z"></path>
          </svg>
          <span x-text="submitting ? 'Saving...' : 'Save Settings'"></span>
        </button>
      </div>
    </form>
  </div>
@endsection
