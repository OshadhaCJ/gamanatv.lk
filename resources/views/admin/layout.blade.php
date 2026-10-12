<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin') - GAMANA</title>
  <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
          colors: { brand: { red: '#C81E1E', darkRed: '#A61515' } }
        }
      }
    }
  </script>
  <style>
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .fade-in { animation: fadeIn 0.35s ease-out both; }
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 9999px; }
  </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased h-screen flex overflow-hidden">

  @php
    $navItems = [
      ['route' => 'admin.dashboard', 'href' => route('admin.dashboard'), 'label' => 'Live Dashboard', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7Z'],
      ['route' => 'admin.buses.*', 'href' => route('admin.buses.index'), 'label' => 'Buses', 'icon' => 'M4 16V7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v9M4 16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M4 16h16M8 11h8M7 20h.01M17 20h.01'],
      ['route' => 'admin.attendance', 'href' => route('admin.attendance'), 'label' => 'Attendance', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
      ['route' => 'admin.incidents', 'href' => route('admin.incidents'), 'label' => 'Field Incidents', 'icon' => 'M15 10l4.553-2.276A1 1 0 0 1 21 8.618v6.764a1 1 0 0 1-1.447.894L15 14M5 18h8a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2Z'],
      ['route' => 'admin.owners.*', 'href' => route('admin.owners.index'), 'label' => 'Owners', 'icon' => 'M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z'],
      ['route' => 'admin.routes.*', 'href' => route('admin.routes.index'), 'label' => 'Routes', 'icon' => 'M9 20l-5.447-2.724A1 1 0 0 1 3 16.382V5.618a1 1 0 0 1 1.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0 0 21 18.382V7.618a1 1 0 0 0-.553-.894L15 4m0 13V4m0 0L9 7'],
      ['route' => 'admin.processing-units.*', 'href' => route('admin.processing-units.index'), 'label' => 'Processing Units', 'icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Zm1-9h8v6H8v-6Z'],
      ['route' => 'admin.storage-units.*', 'href' => route('admin.storage-units.index'), 'label' => 'Storage Units', 'icon' => 'M5 8h14M5 8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2M5 8a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-1a2 2 0 0 0-2-2M5 12v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6M9 16h6'],
      ['route' => 'admin.storage-swaps', 'href' => route('admin.storage-swaps'), 'label' => 'Storage Device Changes', 'icon' => 'M16 4h5v5M8 20H3v-5M20 4l-7 7M4 20l7-7'],
      ['route' => 'admin.logs', 'href' => route('admin.logs'), 'label' => 'Logs', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z', 'badge' => \App\Models\DevicePing::where('is_flagged', true)->count() ?: null],
    ];
  @endphp

  <!-- Sidebar -->
  <aside class="hidden lg:flex flex-col w-64 shrink-0 bg-gray-950 text-white h-screen overflow-y-auto">
    <a href="{{ route('home') }}" class="flex items-center gap-3 px-6 py-6">
      <img src="{{ asset('images/logo.png') }}" alt="Gamana TV" class="w-8 h-8 object-contain" />
      <div class="leading-tight">
        <span class="font-black text-lg tracking-tighter block">GAMANA</span>
        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Admin Panel</span>
      </div>
    </a>

    <nav class="flex-1 px-4 py-4 space-y-1">
      <p class="px-4 pt-2 pb-3 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Management</p>
      @foreach ($navItems as $item)
        @php $active = request()->routeIs($item['route']); @endphp
        <a
          href="{{ $item['href'] }}"
          class="group relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors duration-200 {{ $active ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}"
        >
          <span class="absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-brand-red transition-opacity duration-200 {{ $active ? 'opacity-100' : 'opacity-0' }}"></span>
          <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors duration-200 {{ $active ? 'bg-brand-red text-white' : 'bg-white/5 text-gray-400 group-hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" /></svg>
          </span>
          <span class="flex-1">{{ $item['label'] }}</span>
          @if (!empty($item['badge']))
            <span class="shrink-0 bg-brand-red text-white text-[10px] font-black rounded-full w-5 h-5 flex items-center justify-center">{{ $item['badge'] }}</span>
          @endif
        </a>
      @endforeach

      @if (in_array(auth()->user()->role, ['developer', 'admin']))
        <p class="px-4 pt-6 pb-3 text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">System</p>
        @php $settingsActive = request()->routeIs('admin.settings'); @endphp
        <a
          href="{{ route('admin.settings') }}"
          class="group relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors duration-200 {{ $settingsActive ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}"
        >
          <span class="absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-brand-red transition-opacity duration-200 {{ $settingsActive ? 'opacity-100' : 'opacity-0' }}"></span>
          <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors duration-200 {{ $settingsActive ? 'bg-brand-red text-white' : 'bg-white/5 text-gray-400 group-hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
          </span>
          Settings
        </a>
      @endif
    </nav>

    <div class="px-4 py-5 border-t border-white/10">
      <div class="flex items-center gap-3 px-2 mb-4">
        <div class="w-9 h-9 rounded-full bg-brand-red/20 text-brand-red flex items-center justify-center font-bold text-sm shrink-0 overflow-hidden">
          @if (auth()->user()->image)
            <img src="{{ auth()->user()->image }}" alt="" class="w-full h-full object-cover" />
          @else
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
          @endif
        </div>
        <div class="min-w-0">
          <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
          <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-400 hover:bg-white/5 hover:text-white transition-colors duration-200 flex items-center gap-3">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0-4-4m4 4H7m6 5v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1" />
          </svg>
          Log Out
        </button>
      </form>
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-1 min-w-0 h-screen flex flex-col overflow-hidden">
    <!-- Mobile header -->
    <header class="lg:hidden flex items-center justify-between bg-gray-950 text-white px-4 py-4">
      <a href="{{ route('home') }}" class="flex items-center gap-2">
        <img src="{{ asset('images/logo.png') }}" alt="Gamana TV" class="w-7 h-7 object-contain" />
        <span class="font-black text-sm tracking-tighter">GAMANA ADMIN</span>
      </a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-xs font-bold text-gray-300">Log Out</button>
      </form>
    </header>

    <!-- Mobile nav -->
    <nav class="lg:hidden flex items-center gap-2 bg-white border-b border-gray-100 px-4 py-2 overflow-x-auto">
      @foreach ($navItems as $item)
        <a href="{{ $item['href'] }}" class="shrink-0 px-4 py-2 rounded-full text-xs font-bold {{ request()->routeIs($item['route']) ? 'bg-brand-red text-white' : 'bg-gray-100 text-gray-600' }}">
          {{ $item['label'] }}
        </a>
      @endforeach
    </nav>

    <!-- Desktop top bar -->
    <header class="hidden lg:flex items-center justify-between px-10 py-5 bg-white border-b border-gray-100">
      <div>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em]">Gamana TV / Admin</p>
        <h1 class="text-lg font-black text-gray-900 tracking-tight">@yield('title', 'Dashboard')</h1>
      </div>
      <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-brand-red transition-colors duration-200">
        View Site
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5m0 0v5m0-5L10 14M6 5H5a1 1 0 0 0-1 1v13a1 1 0 0 0 1 1h13a1 1 0 0 0 1-1v-1" /></svg>
      </a>
    </header>

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10">
      @if (session('status'))
        <div class="fade-in mb-6 flex items-center gap-2 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm font-semibold text-green-700">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
          {{ session('status') }}
        </div>
      @endif

      @yield('content')
    </main>
  </div>

</body>
</html>
