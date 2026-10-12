<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GAMANA - Hero UI</title>
  <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" />
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Alpine.js CDN (for the Active Towns interactive switcher) -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <!-- Google Font: Plus Jakarta Sans / Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
          },
          colors: {
            brand: {
              red: '#C81E1E',
              darkRed: '#A61515',
            }
          }
        }
      }
    }
  </script>
</head>

<body class="bg-white text-gray-900 font-sans antialiased overflow-x-hidden">

  <!-- ==================== HERO SECTION ==================== -->
  <section class="relative w-full">
    
    <!-- 1. FLOATING PILL NAVBAR -->
    <header class="fixed top-5 sm:top-7 left-1/2 -translate-x-1/2 w-[94%] max-w-6xl z-50 transition-all duration-300">
      <nav class="bg-white/95 backdrop-blur-md px-4 sm:px-6 py-2.5 sm:py-3 rounded-full shadow-lg shadow-black/10 border border-white/60 flex items-center justify-between">
        
        <!-- Brand Logo (Red Circle + Text) -->
        <a href="#" class="flex items-center gap-3 group">
          <img src="{{ asset('images/logo.png') }}" alt="Gamana TV Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain shadow-sm group-hover:scale-105 transition-transform duration-300" />
          <span class="font-black text-xl sm:text-2xl tracking-tighter text-gray-950">GAMANA</span>
        </a>

        <!-- Center Nav Items -->
        <div class="hidden lg:flex items-center gap-7 font-extrabold text-xs tracking-wider text-gray-900 uppercase">
          <a href="#about" class="hover:text-brand-red transition-colors duration-200">About</a>
          <a href="#towns" class="hover:text-brand-red transition-colors duration-200">Active Towns</a>
          <a href="#how-it-works" class="hover:text-brand-red transition-colors duration-200">How It Works</a>
          <a href="#contact" class="hover:text-brand-red transition-colors duration-200">Contact</a>
        </div>

        <!-- Right Side -->
        <div class="flex items-center gap-3">
          @auth
            @if (in_array(auth()->user()->role, ['admin', 'coordinator', 'developer']))
              <a href="{{ route('admin.buses.index') }}" class="hidden sm:flex items-center gap-2 border border-gray-200 hover:border-brand-red hover:text-brand-red text-gray-700 px-4 sm:px-5 h-9 sm:h-11 rounded-full font-bold text-xs sm:text-sm tracking-wide transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16V7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v9M4 16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M4 16h16M8 11h8M7 20h.01M17 20h.01" /></svg>
                Admin Panel
              </a>
            @endif
            <a href="{{ route('home') }}" class="bg-brand-red hover:bg-brand-darkRed active:scale-95 text-white w-28 sm:w-36 h-9 sm:h-11 rounded-full font-bold text-xs sm:text-sm tracking-wide shadow-md shadow-brand-red/25 transition-all duration-300 flex items-center justify-center gap-2">
              @if (auth()->user()->image)
                <img src="{{ auth()->user()->image }}" alt="{{ auth()->user()->name }}" class="w-6 h-6 rounded-full object-cover" />
              @endif
              <span class="truncate">{{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}</span>
            </a>
          @else
            <a href="{{ route('login') }}" class="bg-brand-red hover:bg-brand-darkRed active:scale-95 text-white w-28 sm:w-36 h-9 sm:h-11 rounded-full font-bold text-xs sm:text-sm tracking-wide shadow-md shadow-brand-red/25 transition-all duration-300 flex items-center justify-center">
              Login
            </a>
          @endauth
        </div>

      </nav>
    </header>

    <!-- 2. PANORAMIC STADIUM VIEW WITH BOTTOM VIGNETTE -->
    <div class="relative w-full h-screen overflow-hidden">
      <!-- Panoramic Landscape Image (Galle International Cricket Stadium Style) -->
      <img 
        src="https://images.unsplash.com/photo-1743612135710-7e758e284679?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
        alt="Galle Stadium Landscape" 
        class="w-full h-full object-cover object-bottom scale-100 hover:scale-105 transition-transform duration-1000 ease-out" 
      />

      <!-- Uniform Dark Overlay for Text Legibility -->
      <div class="absolute inset-0 bg-black/80 pointer-events-none"></div>

      <!-- Subtle Dark Gradient at Bottom (replicating image shading) -->
      <div class="absolute inset-x-0 bottom-0 h-36 bg-gradient-to-t from-black/75 via-black/25 to-transparent pointer-events-none"></div>

      <!-- Hero Text Overlay -->
      <div class="absolute inset-0 flex items-center justify-center px-4 sm:px-6">
        <div class="max-w-3xl text-center">
          <div class="flex items-center justify-center gap-3 mb-3">
            <span class="h-px w-6 sm:w-10 bg-brand-red"></span>
            <span class="text-brand-red font-bold uppercase text-[11px] sm:text-xs tracking-[0.3em] drop-shadow-[0_2px_6px_rgba(0,0,0,0.6)]">
              Sri Lanka's First Premium
            </span>
            <span class="h-px w-6 sm:w-10 bg-brand-red"></span>
          </div>
          <h1 class="text-white font-black uppercase leading-[1.15] tracking-tight drop-shadow-[0_2px_8px_rgba(0,0,0,0.6)] text-3xl sm:text-4xl md:text-5xl lg:text-[2.75rem] xl:text-5xl">
            Entertainment <span class="text-brand-red">Platform</span> for Public Transport
          </h1>
          <p class="text-white/80 font-medium text-xs sm:text-sm lg:text-base tracking-wide uppercase mt-5 drop-shadow-[0_2px_6px_rgba(0,0,0,0.6)]">
            Delivering curated content and high-impact advertising to passengers across major cities.
          </p>
          <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-8">
            <button class="bg-brand-red hover:bg-brand-darkRed active:scale-95 text-white px-8 py-3.5 rounded-full font-bold text-sm tracking-wide shadow-md shadow-brand-red/25 transition-all duration-300 uppercase">
              Advertise With Us
            </button>
            <button class="bg-white/10 backdrop-blur-sm hover:bg-white/20 active:scale-95 text-white border border-white/70 px-8 py-3.5 rounded-full font-bold text-sm tracking-wide transition-all duration-300 uppercase">
              See How It Works
            </button>
          </div>
        </div>
      </div>

      <!-- Scroll Down Indicator -->
      <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 z-10">
        <span class="text-white/70 text-[10px] font-bold uppercase tracking-[0.25em]">Scroll</span>
        <svg class="w-5 h-5 text-white/80 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>

    <!-- 3. STATS STRIP / METRICS SECTION -->
    <div class="bg-white border-b border-gray-100 py-8 sm:py-10 shadow-sm relative z-20">
      <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 items-center text-center divide-y md:divide-y-0 md:divide-x divide-gray-100">
          
          <!-- Metric 1 -->
          <div class="pt-4 md:pt-0">
            <h3 class="text-3xl sm:text-4xl lg:text-5xl font-black text-brand-red tracking-tight mb-1 hover:scale-105 transition-transform duration-300 cursor-default">
              5K+
            </h3>
            <p class="text-[11px] sm:text-xs font-bold text-gray-700 tracking-wider uppercase leading-tight max-w-[150px] mx-auto">
              Hours of Curated Content
            </p>
          </div>

          <!-- Metric 2 -->
          <div class="pt-4 md:pt-0">
            <h3 class="text-3xl sm:text-4xl lg:text-5xl font-black text-brand-red tracking-tight mb-1 hover:scale-105 transition-transform duration-300 cursor-default">
              99.9%
            </h3>
            <p class="text-[11px] sm:text-xs font-bold text-gray-700 tracking-wider uppercase leading-tight max-w-[160px] mx-auto">
              Uptime on Streaming Network
            </p>
          </div>

          <!-- Metric 3 -->
          <div class="pt-4 md:pt-0">
            <h3 class="text-3xl sm:text-4xl lg:text-5xl font-black text-brand-red tracking-tight mb-1 hover:scale-105 transition-transform duration-300 cursor-default">
              10K+
            </h3>
            <p class="text-[11px] sm:text-xs font-bold text-gray-700 tracking-wider uppercase leading-tight max-w-[150px] mx-auto">
              Daily Viewers
            </p>
          </div>

          <!-- Metric 4 -->
          <div class="pt-4 md:pt-0">
            <h3 class="text-3xl sm:text-4xl lg:text-5xl font-black text-brand-red tracking-tight mb-1 hover:scale-105 transition-transform duration-300 cursor-default">
              99%
            </h3>
            <p class="text-[11px] sm:text-xs font-bold text-gray-700 tracking-wider uppercase leading-tight max-w-[150px] mx-auto">
              Ad Engagement Rate
            </p>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- ==================== ABOUT US SECTION ==================== -->
<section id="about" class="relative bg-white py-16 sm:py-24 overflow-hidden">



    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
      <div class="max-w-2xl">
        <p class="text-brand-red text-xs sm:text-sm font-bold tracking-[0.25em] uppercase mb-3">
          A Bit
        </p>
        <h2 class="text-4xl sm:text-5xl lg:text-6xl font-black text-gray-900 tracking-tight mb-6">
          ABOUT US
        </h2>
        <p class="text-gray-600 text-base sm:text-lg leading-relaxed mb-8 font-medium">
          At Gamana TV, we aim to transform public transport into a dynamic media environment, enriching passenger journeys and giving brands unmatched access to Sri Lankans on the move
        </p>
        <button class="bg-brand-red hover:bg-brand-darkRed active:scale-95 text-white px-8 py-3.5 rounded-full font-bold text-sm tracking-wide shadow-md shadow-brand-red/25 transition-all duration-300 uppercase">
          Explore More
        </button>
      </div>
    </div>
</section>



  <!-- ==================== ACTIVE TOWNS SECTION ==================== -->
  <section
    id="towns"
    class="relative bg-gray-950 py-20 sm:py-28 overflow-hidden"
    x-data="{
      towns: [
        {
          name: 'COLOMBO',
          buses: 42,
          routes: 12,
          hours: '18h',
          desc: 'Gamana TV’s busiest hub — live screens run across the capital’s commuter routes, reaching commuters, shoppers, and office crowds all day long.',
          card: 'https://images.unsplash.com/photo-1546412414-8035e1776c9a?q=80&w=1200&auto=format&fit=crop',
          bg: 'https://images.unsplash.com/photo-1546412414-8035e1776c9a?q=80&w=2400&auto=format&fit=crop',
        },
        {
          name: 'KANDY',
          buses: 28,
          routes: 9,
          hours: '15h',
          desc: 'In the hill capital, our network connects daily commuters and tourists alike, keeping screens running through Kandy’s busy town routes.',
          card: 'https://images.unsplash.com/photo-1591797442444-039f23ddcc14?q=80&w=1200&auto=format&fit=crop',
          bg: 'https://images.unsplash.com/photo-1591797442444-039f23ddcc14?q=80&w=2400&auto=format&fit=crop',
        },
        {
          name: 'GALLE',
          buses: 21,
          routes: 8,
          hours: '14h',
          desc: 'Along the southern coast, Gamana TV reaches passengers moving between Galle’s coastal towns, pairing scenic routes with always-on content.',
          card: 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?q=80&w=1200&auto=format&fit=crop',
          bg: 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?q=80&w=2400&auto=format&fit=crop',
        },
      ],
      active: 0,
      next() { this.active = (this.active + 1) % this.towns.length },
      prev() { this.active = (this.active - 1 + this.towns.length) % this.towns.length },
    }"
  >

    <!-- Background Image (follows the active town) -->
    <template x-for="(town, i) in towns" :key="'bg-' + i">
      <img
        :src="town.bg"
        :alt="town.name + ' Background'"
        x-show="active === i"
        x-transition:enter="transition ease-in-out duration-700"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-40"
        x-transition:leave="transition ease-in-out duration-700"
        x-transition:leave-start="opacity-40"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 w-full h-full object-cover opacity-40 pointer-events-none select-none"
      />
    </template>
    <div class="absolute inset-0 bg-gradient-to-r from-gray-950 via-gray-950/80 to-gray-950/40"></div>

    <!-- Social Icons -->
    <div class="hidden lg:flex flex-col gap-4 absolute right-6 xl:right-10 top-1/2 -translate-y-1/2 z-20">
      <a href="#" class="w-10 h-10 rounded-full border border-white/30 flex items-center justify-center text-white hover:bg-white hover:text-gray-900 transition-colors duration-300">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12.07C22 6.48 17.52 2 12 2S2 6.48 2 12.07c0 5.02 3.66 9.18 8.44 9.93v-7.03H7.9v-2.9h2.54V9.85c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.9h-2.34V22c4.78-.75 8.44-4.91 8.44-9.93Z"/></svg>
      </a>
      <a href="#" class="w-10 h-10 rounded-full border border-white/30 flex items-center justify-center text-white hover:bg-white hover:text-gray-900 transition-colors duration-300">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.3 4.3 0 0 0 1.88-2.37 8.59 8.59 0 0 1-2.72 1.04 4.28 4.28 0 0 0-7.29 3.9 12.14 12.14 0 0 1-8.82-4.47 4.28 4.28 0 0 0 1.32 5.71 4.25 4.25 0 0 1-1.94-.54v.05a4.28 4.28 0 0 0 3.43 4.2 4.3 4.3 0 0 1-1.93.07 4.29 4.29 0 0 0 4 2.98A8.6 8.6 0 0 1 2 18.57a12.1 12.1 0 0 0 6.56 1.92c7.87 0 12.18-6.52 12.18-12.18 0-.19 0-.37-.01-.56A8.7 8.7 0 0 0 22.46 6Z"/></svg>
      </a>
      <a href="#" class="w-10 h-10 rounded-full border border-white/30 flex items-center justify-center text-white hover:bg-white hover:text-gray-900 transition-colors duration-300">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.72 3.72 0 0 1-1.38-.9 3.72 3.72 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41 1.27-.06 1.65-.07 4.85-.07M12 0C8.74 0 8.33.01 7.05.07c-1.28.06-2.15.26-2.91.56-.79.3-1.47.72-2.14 1.39A5.9 5.9 0 0 0 .61 4.16c-.3.76-.5 1.63-.56 2.9C0 8.33 0 8.74 0 12s.01 3.67.07 4.95c.06 1.28.26 2.15.56 2.91.3.79.72 1.47 1.39 2.14a5.9 5.9 0 0 0 2.14 1.39c.76.3 1.63.5 2.9.56C8.33 24 8.74 24 12 24s3.67-.01 4.95-.07c1.28-.06 2.15-.26 2.91-.56a5.9 5.9 0 0 0 2.14-1.39 5.9 5.9 0 0 0 1.39-2.14c.3-.76.5-1.63.56-2.9.06-1.29.07-1.7.07-4.96s-.01-3.67-.07-4.95c-.06-1.28-.26-2.15-.56-2.91a5.9 5.9 0 0 0-1.39-2.14A5.9 5.9 0 0 0 19.86.63c-.76-.3-1.63-.5-2.9-.56C15.67.01 15.26 0 12 0Zm0 5.84A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84ZM12 16a4 4 0 1 1 4-4 4 4 0 0 1-4 4Zm6.41-10.84a1.44 1.44 0 1 1-1.44-1.44 1.44 1.44 0 0 1 1.44 1.44Z"/></svg>
      </a>
      <a href="#" class="w-10 h-10 rounded-full border border-white/30 flex items-center justify-center text-white hover:bg-white hover:text-gray-900 transition-colors duration-300">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.86 0-2.14 1.45-2.14 2.94v5.67H9.34V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.38-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28ZM5.34 7.43a2.07 2.07 0 1 1 0-4.13 2.07 2.07 0 0 1 0 4.13ZM7.12 20.45H3.56V9h3.56v11.45ZM22.22 0H1.77C.8 0 0 .78 0 1.75v20.5C0 23.22.8 24 1.77 24h20.45c.98 0 1.78-.78 1.78-1.75V1.75C24 .78 23.2 0 22.22 0Z"/></svg>
      </a>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6">
      <div class="grid lg:grid-cols-2 gap-12 items-center">

        <!-- Left: Heading + Stats -->
        <div class="max-w-xl">
          <p class="text-brand-red text-xs sm:text-sm font-bold tracking-[0.25em] uppercase mb-3">
            Where We Operate
          </p>
          <h2 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-[1.05] mb-6">
            ACTIVE<br />TOWNS
          </h2>
          <div class="relative min-h-[84px] sm:min-h-[64px] mb-10">
            <template x-for="(town, i) in towns" :key="'desc-' + i">
              <p
                x-show="active === i"
                x-transition:enter="transition ease-out duration-400"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-400"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="absolute inset-0 text-gray-300 text-base sm:text-lg leading-relaxed font-medium"
                x-text="town.desc"
              ></p>
            </template>
          </div>

          <div class="grid grid-cols-3 gap-6 mb-10">
            <div>
              <div class="relative h-9 sm:h-10 mb-1">
                <template x-for="(town, i) in towns" :key="'buses-' + i">
                  <h3
                    x-show="active === i"
                    x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-400"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="absolute inset-x-0 top-0 text-2xl sm:text-3xl font-black text-white tracking-tight"
                    x-text="town.buses + '+'"
                  ></h3>
                </template>
              </div>
              <p class="text-[11px] sm:text-xs font-bold text-gray-400 tracking-wider uppercase leading-tight">
                Running Buses
              </p>
            </div>
            <div>
              <div class="relative h-9 sm:h-10 mb-1">
                <template x-for="(town, i) in towns" :key="'routes-' + i">
                  <h3
                    x-show="active === i"
                    x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-400"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="absolute inset-x-0 top-0 text-2xl sm:text-3xl font-black text-white tracking-tight"
                    x-text="town.routes + '+'"
                  ></h3>
                </template>
              </div>
              <p class="text-[11px] sm:text-xs font-bold text-gray-400 tracking-wider uppercase leading-tight">
                Total Routes
              </p>
            </div>
            <div>
              <div class="relative h-9 sm:h-10 mb-1">
                <template x-for="(town, i) in towns" :key="'hours-' + i">
                  <h3
                    x-show="active === i"
                    x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-400"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="absolute inset-x-0 top-0 text-2xl sm:text-3xl font-black text-white tracking-tight"
                    x-text="town.hours"
                  ></h3>
                </template>
              </div>
              <p class="text-[11px] sm:text-xs font-bold text-gray-400 tracking-wider uppercase leading-tight">
                Daily Run Hours
              </p>
            </div>
          </div>

          <button class="bg-brand-red hover:bg-brand-darkRed active:scale-95 text-white px-8 py-3.5 rounded-full font-bold text-sm tracking-wide shadow-md shadow-brand-red/25 transition-all duration-300 uppercase inline-flex items-center gap-2">
            Explore
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
          </button>
        </div>

        <!-- Right: Town Cards -->
        <div class="relative">

          <!-- Carousel Arrows -->
          <div class="flex items-center gap-3 mb-6">
            <button @click="prev()" class="w-9 h-9 rounded-full border border-white/30 flex items-center justify-center text-white hover:bg-white hover:text-gray-900 transition-colors duration-300">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
            </button>
            <button @click="next()" class="w-9 h-9 rounded-full border border-white/30 flex items-center justify-center text-white hover:bg-white hover:text-gray-900 transition-colors duration-300">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </button>
          </div>

          <div class="grid grid-cols-5 gap-4 sm:gap-6 items-end">

            <!-- Active Town Card (Large) -->
            <div class="col-span-3 relative rounded-3xl overflow-hidden shadow-2xl group h-80 sm:h-96">
              <template x-for="(town, i) in towns" :key="'active-' + i">
                <img
                  x-show="active === i"
                  :src="town.card"
                  :alt="town.name"
                  x-transition:enter="transition ease-in-out duration-500"
                  x-transition:enter-start="opacity-0 scale-105"
                  x-transition:enter-end="opacity-100 scale-100"
                  x-transition:leave="transition ease-in-out duration-500"
                  x-transition:leave-start="opacity-100 scale-100"
                  x-transition:leave-end="opacity-0 scale-105"
                  class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out"
                />
              </template>
              <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent pointer-events-none"></div>
              <template x-for="(town, i) in towns" :key="'overlay-' + i">
                <div
                  x-show="active === i"
                  x-transition:enter="transition ease-out duration-400 delay-100"
                  x-transition:enter-start="opacity-0 translate-y-2"
                  x-transition:enter-end="opacity-100 translate-y-0"
                  x-transition:leave="transition ease-in duration-200"
                  x-transition:leave-start="opacity-100 translate-y-0"
                  x-transition:leave-end="opacity-0 translate-y-2"
                  class="absolute bottom-0 left-0 right-0 p-5 sm:p-6"
                >
                  <h3 class="text-white text-xl sm:text-2xl font-black tracking-tight mb-3" x-text="town.name"></h3>
                  <div class="flex gap-4 text-white">
                    <div>
                      <p class="text-lg font-black leading-none" x-text="town.buses"></p>
                      <p class="text-[10px] font-bold text-gray-300 uppercase tracking-wider mt-1">Buses</p>
                    </div>
                    <div>
                      <p class="text-lg font-black leading-none" x-text="town.routes"></p>
                      <p class="text-[10px] font-bold text-gray-300 uppercase tracking-wider mt-1">Routes</p>
                    </div>
                    <div>
                      <p class="text-lg font-black leading-none" x-text="town.hours"></p>
                      <p class="text-[10px] font-bold text-gray-300 uppercase tracking-wider mt-1">Run Time</p>
                    </div>
                  </div>
                </div>
              </template>
            </div>

            <!-- Secondary Town Cards (click to make active) -->
            <div class="col-span-2 flex flex-col gap-4 sm:gap-6">
              <template x-for="(town, i) in towns" :key="'thumb-' + i">
                <button
                  type="button"
                  x-show="active !== i"
                  @click="active = i"
                  class="relative rounded-3xl overflow-hidden shadow-xl group h-36 sm:h-44 text-left focus:outline-none focus:ring-2 focus:ring-brand-red"
                >
                  <img
                    :src="town.card"
                    :alt="town.name"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out"
                  />
                  <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                  <div class="absolute bottom-0 left-0 right-0 p-4">
                    <h3 class="text-white text-base sm:text-lg font-black tracking-tight" x-text="town.name"></h3>
                    <p class="text-[10px] font-bold text-gray-300 uppercase tracking-wider">
                      <span x-text="town.buses"></span> Buses &middot; <span x-text="town.routes"></span> Routes
                    </p>
                  </div>
                </button>
              </template>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
  <!-- ==================== HOW IT WORKS SECTION ==================== -->
  <section id="how-it-works" class="relative bg-gray-50 py-16 sm:py-24">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 text-center">
      <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight mb-4">
        HOW IT WORKS
      </h2>
      <p class="text-brand-red text-xs sm:text-sm font-bold tracking-[0.2em] uppercase mb-16">
        &ldquo;Turn Daily Commutes Into Powerful Advertising Moments.&rdquo;
      </p>

      <div class="grid sm:grid-cols-3 gap-12 sm:gap-8">

        <!-- Step 1: Engage -->
        <div class="flex flex-col items-center">
          <div class="w-20 h-20 rounded-full bg-brand-red/10 flex items-center justify-center mb-6">
            <svg class="w-9 h-9 text-brand-red" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-1a4 4 0 0 0-3-3.87M9 20H4v-1a4 4 0 0 1 3-3.87m5-5.13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm6 1a4 4 0 1 0 0-8m-12 8a4 4 0 1 1 0-8" />
            </svg>
          </div>
          <h3 class="text-lg font-bold text-gray-900 mb-1">Engage</h3>
          <p class="text-gray-500 text-sm leading-relaxed max-w-[220px]">
            Passengers &amp; increase brand reach
          </p>
        </div>

        <!-- Step 2: Stream -->
        <div class="flex flex-col items-center">
          <div class="w-20 h-20 rounded-full bg-brand-red/10 flex items-center justify-center mb-6">
            <svg class="w-9 h-9 text-brand-red" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <rect x="3" y="5" width="18" height="13" rx="2" stroke-linecap="round" stroke-linejoin="round" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 9.5v4l3.5-2-3.5-2Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8" />
            </svg>
          </div>
          <h3 class="text-lg font-bold text-gray-900 mb-1">Stream</h3>
          <p class="text-gray-500 text-sm leading-relaxed max-w-[220px]">
            Content to transit screens
          </p>
        </div>

        <!-- Step 3: Deliver -->
        <div class="flex flex-col items-center">
          <div class="w-20 h-20 rounded-full bg-brand-red/10 flex items-center justify-center mb-6">
            <svg class="w-9 h-9 text-brand-red" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-6.1-7-11.5A7 7 0 0 1 19 9.5C19 14.9 12 21 12 21Z" />
              <circle cx="12" cy="9.5" r="2.3" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
          <h3 class="text-lg font-bold text-gray-900 mb-1">Deliver</h3>
          <p class="text-gray-500 text-sm leading-relaxed max-w-[220px]">
            Targeted ads by city
          </p>
        </div>

      </div>
    </div>
  </section>

  <!-- ==================== FOOTER ==================== -->
  <footer id="contact" class="relative bg-gray-950 pt-16 sm:pt-20 pb-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">

      <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-8 pb-12 border-b border-white/10">

        <!-- Brand Column -->
        <div class="lg:col-span-2">
          <a href="#" class="inline-flex items-center gap-3 mb-5">
            <img src="{{ asset('images/logo.png') }}" alt="Gamana TV Logo" class="w-10 h-10 object-contain" />
            <span class="font-black text-2xl tracking-tighter text-white">GAMANA</span>
          </a>
          <p class="text-gray-400 text-sm leading-relaxed max-w-sm mb-6">
            Sri Lanka's first premium entertainment platform for public transport &mdash; delivering curated content
            and high-impact advertising to passengers across major cities.
          </p>
          <div class="flex items-center gap-3">
            <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-brand-red hover:border-brand-red transition-colors duration-300">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12.07C22 6.48 17.52 2 12 2S2 6.48 2 12.07c0 5.02 3.66 9.18 8.44 9.93v-7.03H7.9v-2.9h2.54V9.85c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.9h-2.34V22c4.78-.75 8.44-4.91 8.44-9.93Z"/></svg>
            </a>
            <a href="#" aria-label="Twitter" class="w-9 h-9 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-brand-red hover:border-brand-red transition-colors duration-300">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.3 4.3 0 0 0 1.88-2.37 8.59 8.59 0 0 1-2.72 1.04 4.28 4.28 0 0 0-7.29 3.9 12.14 12.14 0 0 1-8.82-4.47 4.28 4.28 0 0 0 1.32 5.71 4.25 4.25 0 0 1-1.94-.54v.05a4.28 4.28 0 0 0 3.43 4.2 4.3 4.3 0 0 1-1.93.07 4.29 4.29 0 0 0 4 2.98A8.6 8.6 0 0 1 2 18.57a12.1 12.1 0 0 0 6.56 1.92c7.87 0 12.18-6.52 12.18-12.18 0-.19 0-.37-.01-.56A8.7 8.7 0 0 0 22.46 6Z"/></svg>
            </a>
            <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-brand-red hover:border-brand-red transition-colors duration-300">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.72 3.72 0 0 1-1.38-.9 3.72 3.72 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41 1.27-.06 1.65-.07 4.85-.07M12 0C8.74 0 8.33.01 7.05.07c-1.28.06-2.15.26-2.91.56-.79.3-1.47.72-2.14 1.39A5.9 5.9 0 0 0 .61 4.16c-.3.76-.5 1.63-.56 2.9C0 8.33 0 8.74 0 12s.01 3.67.07 4.95c.06 1.28.26 2.15.56 2.91.3.79.72 1.47 1.39 2.14a5.9 5.9 0 0 0 2.14 1.39c.76.3 1.63.5 2.9.56C8.33 24 8.74 24 12 24s3.67-.01 4.95-.07c1.28-.06 2.15-.26 2.91-.56a5.9 5.9 0 0 0 2.14-1.39 5.9 5.9 0 0 0 1.39-2.14c.3-.76.5-1.63.56-2.9.06-1.29.07-1.7.07-4.96s-.01-3.67-.07-4.95c-.06-1.28-.26-2.15-.56-2.91a5.9 5.9 0 0 0-1.39-2.14A5.9 5.9 0 0 0 19.86.63c-.76-.3-1.63-.5-2.9-.56C15.67.01 15.26 0 12 0Zm0 5.84A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84ZM12 16a4 4 0 1 1 4-4 4 4 0 0 1-4 4Zm6.41-10.84a1.44 1.44 0 1 1-1.44-1.44 1.44 1.44 0 0 1 1.44 1.44Z"/></svg>
            </a>
            <a href="#" aria-label="LinkedIn" class="w-9 h-9 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-brand-red hover:border-brand-red transition-colors duration-300">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.86 0-2.14 1.45-2.14 2.94v5.67H9.34V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.38-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28ZM5.34 7.43a2.07 2.07 0 1 1 0-4.13 2.07 2.07 0 0 1 0 4.13ZM7.12 20.45H3.56V9h3.56v11.45ZM22.22 0H1.77C.8 0 0 .78 0 1.75v20.5C0 23.22.8 24 1.77 24h20.45c.98 0 1.78-.78 1.78-1.75V1.75C24 .78 23.2 0 22.22 0Z"/></svg>
            </a>
          </div>
        </div>

        <!-- Company Links -->
        <div>
          <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-5">Company</h4>
          <ul class="space-y-3 text-sm">
            <li><a href="#" class="text-gray-400 hover:text-brand-red transition-colors duration-200">About Us</a></li>
            <li><a href="#" class="text-gray-400 hover:text-brand-red transition-colors duration-200">How It Works</a></li>
            <li><a href="#" class="text-gray-400 hover:text-brand-red transition-colors duration-200">Active Towns</a></li>
            <li><a href="#" class="text-gray-400 hover:text-brand-red transition-colors duration-200">Careers</a></li>
            <li><a href="#" class="text-gray-400 hover:text-brand-red transition-colors duration-200">Press &amp; Media</a></li>
          </ul>
        </div>

        <!-- Services Links -->
        <div>
          <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-5">Services</h4>
          <ul class="space-y-3 text-sm">
            <li><a href="#" class="text-gray-400 hover:text-brand-red transition-colors duration-200">Advertise With Us</a></li>
            <li><a href="#" class="text-gray-400 hover:text-brand-red transition-colors duration-200">Content Partnerships</a></li>
            <li><a href="#" class="text-gray-400 hover:text-brand-red transition-colors duration-200">Fleet Installation</a></li>
            <li><a href="#" class="text-gray-400 hover:text-brand-red transition-colors duration-200">Analytics &amp; Reach</a></li>
            <li><a href="#" class="text-gray-400 hover:text-brand-red transition-colors duration-200">FAQs</a></li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div>
          <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-5">Get In Touch</h4>
          <ul class="space-y-4 text-sm text-gray-400">
            <li class="flex items-start gap-3">
              <svg class="w-4 h-4 mt-0.5 text-brand-red shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-6.1-7-11.5A7 7 0 0 1 19 9.5C19 14.9 12 21 12 21Z" />
                <circle cx="12" cy="9.5" r="2.3" />
              </svg>
              <span>No. 45, Galle Road, Colombo 03, Sri Lanka</span>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-4 h-4 text-brand-red shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 0 1 2-2h2.28a1 1 0 0 1 .97.76l1 4a1 1 0 0 1-.5 1.11L7 10a12 12 0 0 0 7 7l1.13-1.75a1 1 0 0 1 1.11-.5l4 1a1 1 0 0 1 .76.97V19a2 2 0 0 1-2 2h-1C9.72 21 3 14.28 3 6V5Z" />
              </svg>
              <a href="tel:+94112345678" class="hover:text-brand-red transition-colors duration-200">+94 11 234 5678</a>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-4 h-4 text-brand-red shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Z" />
              </svg>
              <a href="mailto:hello@gamanatv.lk" class="hover:text-brand-red transition-colors duration-200">hello@gamanatv.lk</a>
            </li>
          </ul>
        </div>

      </div>

      <!-- Bottom Bar -->
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8">
        <p class="text-gray-500 text-xs text-center sm:text-left">
          &copy; {{ date('Y') }} Gamana TV. All rights reserved.
        </p>
        <div class="flex items-center gap-6 text-xs text-gray-500">
          <a href="#" class="hover:text-brand-red transition-colors duration-200">Privacy Policy</a>
          <a href="#" class="hover:text-brand-red transition-colors duration-200">Terms of Service</a>
          <a href="#" class="hover:text-brand-red transition-colors duration-200">Cookie Policy</a>
        </div>
      </div>

    </div>
  </footer>
</body>
</html>