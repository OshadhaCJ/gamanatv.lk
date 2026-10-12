<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GAMANA - Hero UI</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
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
          <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-brand-red shadow-sm group-hover:scale-105 transition-transform duration-300"></div>
          <span class="font-black text-xl sm:text-2xl tracking-tighter text-gray-950">GAMANA</span>
        </a>

        <!-- Center Nav Items -->
        <div class="hidden lg:flex items-center gap-7 font-extrabold text-xs tracking-wider text-gray-900 uppercase">
          <a href="#" class="hover:text-brand-red transition-colors duration-200">Gamana</a>
          <a href="#" class="hover:text-brand-red transition-colors duration-200">Gamana</a>
          <a href="#" class="hover:text-brand-red transition-colors duration-200">Gamana</a>
          <a href="#" class="hover:text-brand-red transition-colors duration-200">Gamana</a>
          <a href="#" class="hover:text-brand-red transition-colors duration-200">Gamana</a>
        </div>

        <!-- Right Pill Button -->
        <div class="flex items-center">
          <button class="bg-brand-red hover:bg-brand-darkRed active:scale-95 text-white w-28 sm:w-36 h-9 sm:h-11 rounded-full font-bold text-xs sm:text-sm tracking-wide shadow-md shadow-brand-red/25 transition-all duration-300 flex items-center justify-center">
            Explore
          </button>
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

      <!-- Subtle Dark Gradient at Bottom (replicating image shading) -->
      <div class="absolute inset-x-0 bottom-0 h-36 bg-gradient-to-t from-black/75 via-black/25 to-transparent pointer-events-none"></div>
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
  <section class="bg-gray-50 py-16 sm:py-24 relative z-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      <div class="max-w-2xl">
        <p class="text-brand-red text-xs sm:text-sm font-bold tracking-[0.25em] uppercase mb-3">
          A Bit
        </p>
        <h2 class="text-4xl sm:text-5xl lg:text-6xl font-black text-gray-900 tracking-tight mb-6">
          ABOUT US
        </h2>
        <p class="text-gray-500 text-base sm:text-lg leading-relaxed mb-8 font-medium">
          At Gamana TV, we aim to transform public transport into a dynamic media environment, enriching passenger journeys and giving brands unmatched access to Sri Lankans on the move
        </p>
        <button class="bg-brand-red hover:bg-brand-darkRed active:scale-95 text-white px-8 py-3.5 rounded-full font-bold text-sm tracking-wide shadow-md shadow-brand-red/25 transition-all duration-300 uppercase">
          Explore More
        </button>
      </div>
    </div>
  </section>

</body>
</html>