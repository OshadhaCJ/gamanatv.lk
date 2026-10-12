<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Account - GAMANA</title>
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
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(18px) scale(0.98); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes shake {
      10%, 90% { transform: translateX(-1px); }
      20%, 80% { transform: translateX(2px); }
      30%, 50%, 70% { transform: translateX(-4px); }
      40%, 60% { transform: translateX(4px); }
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .auth-card { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
    .auth-shake { animation: shake 0.45s cubic-bezier(0.36, 0.07, 0.19, 0.97) both; }
    .auth-spinner { animation: spin 0.6s linear infinite; }
  </style>
</head>
<body class="bg-gray-950 text-gray-900 font-sans antialiased min-h-screen flex items-center justify-center p-4 sm:p-8">

  <div class="auth-card w-full max-w-5xl bg-white rounded-[2rem] shadow-2xl overflow-hidden grid lg:grid-cols-2">

    <!-- Left: Form -->
    <div class="p-8 sm:p-12 lg:p-14 flex flex-col justify-center">
      <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-10">
        <img src="{{ asset('images/logo.png') }}" alt="Gamana TV" class="w-8 h-8 object-contain" />
        <span class="font-black text-lg tracking-tighter text-gray-950">GAMANA</span>
      </a>

      <h1 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight mb-2">Create your account</h1>
      <p class="text-gray-500 text-sm mb-8">Please enter your details to get started.</p>

      @if ($errors->any())
        <div class="auth-shake mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-brand-red">
          <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <a
        href="{{ route('google.redirect') }}"
        class="w-full flex items-center justify-center gap-3 px-5 py-3.5 rounded-full border border-gray-200 text-sm font-bold text-gray-800 hover:bg-gray-50 hover:border-gray-300 active:scale-[0.99] transition-all duration-200"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24">
          <path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.67-.22-2.45H12v4.64h6.46a5.52 5.52 0 0 1-2.4 3.62v3h3.88c2.27-2.09 3.58-5.17 3.58-8.81Z"/>
          <path fill="#34A853" d="M12 24c3.24 0 5.96-1.07 7.94-2.92l-3.88-3c-1.08.72-2.45 1.15-4.06 1.15-3.12 0-5.77-2.11-6.71-4.94H1.28v3.1A12 12 0 0 0 12 24Z"/>
          <path fill="#FBBC05" d="M5.29 14.29A7.2 7.2 0 0 1 4.91 12c0-.8.14-1.57.38-2.29v-3.1H1.28A12 12 0 0 0 0 12c0 1.94.46 3.77 1.28 5.39l4.01-3.1Z"/>
          <path fill="#EA4335" d="M12 4.77c1.76 0 3.34.6 4.58 1.79l3.44-3.44C17.95 1.19 15.24 0 12 0A12 12 0 0 0 1.28 6.61l4.01 3.1C6.23 6.88 8.88 4.77 12 4.77Z"/>
        </svg>
        Continue with Google
      </a>

      <div class="flex items-center gap-3 my-6">
        <span class="h-px flex-1 bg-gray-200"></span>
        <span class="text-xs text-gray-400 uppercase tracking-wider">or</span>
        <span class="h-px flex-1 bg-gray-200"></span>
      </div>

      <form
        method="POST"
        action="{{ route('register.store') }}"
        class="space-y-4"
        x-data="{ loading: false }"
        @submit="loading = true"
      >
        @csrf

        <input
          type="text" name="name" value="{{ old('name') }}" placeholder="Full Name" required
          class="w-full px-5 py-3.5 rounded-full border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-red/40 focus:border-brand-red transition-all duration-200"
        />

        <input
          type="email" name="email" value="{{ old('email') }}" placeholder="Email" required
          class="w-full px-5 py-3.5 rounded-full border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-red/40 focus:border-brand-red transition-all duration-200"
        />

        <input
          type="tel" name="phone" value="{{ old('phone') }}" placeholder="Phone Number (e.g. 94771234567)" required
          class="w-full px-5 py-3.5 rounded-full border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-red/40 focus:border-brand-red transition-all duration-200"
        />

        <input
          type="password" name="password" placeholder="Password" required
          class="w-full px-5 py-3.5 rounded-full border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-red/40 focus:border-brand-red transition-all duration-200"
        />

        <input
          type="password" name="password_confirmation" placeholder="Confirm Password" required
          class="w-full px-5 py-3.5 rounded-full border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-red/40 focus:border-brand-red transition-all duration-200"
        />

        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-brand-red hover:bg-brand-darkRed active:scale-[0.99] disabled:opacity-70 disabled:cursor-not-allowed text-white py-3.5 rounded-full font-bold text-sm tracking-wide shadow-md shadow-brand-red/25 transition-all duration-300 uppercase flex items-center justify-center gap-2"
        >
          <svg x-show="loading" class="auth-spinner w-4 h-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.37 0 0 5.37 0 12h4Z"></path>
          </svg>
          <span x-text="loading ? 'Sending...' : 'Send Verification Code'"></span>
        </button>
      </form>

      <p class="text-center text-sm text-gray-500 mt-8">
        Already have an account?
        <a href="{{ route('login') }}" class="font-bold text-gray-900 hover:text-brand-red transition-colors duration-200">Log In</a>
      </p>
    </div>

    <!-- Right: Illustration -->
    <div class="hidden lg:block relative">
      <img
        src="https://images.unsplash.com/photo-1588416499018-d8c621e4c3fc?q=80&w=1200&auto=format&fit=crop"
        alt="Gamana TV on the move"
        class="absolute inset-0 w-full h-full object-cover"
      />
      <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-black/10"></div>
      <div class="absolute bottom-10 left-10 right-10 text-white">
        <p class="text-brand-red font-bold uppercase text-xs tracking-[0.25em] mb-2">Join Gamana TV</p>
        <h2 class="text-2xl font-black tracking-tight leading-snug">
          Be part of Sri Lanka's first premium transit entertainment network.
        </h2>
      </div>
    </div>

  </div>

</body>
</html>
