<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verify Phone - GAMANA</title>
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
    @keyframes popIn {
      from { opacity: 0; transform: scale(0.9); }
      to { opacity: 1; transform: scale(1); }
    }
    .auth-card { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
    .auth-shake { animation: shake 0.45s cubic-bezier(0.36, 0.07, 0.19, 0.97) both; }
    .auth-spinner { animation: spin 0.6s linear infinite; }
    .auth-pop { animation: popIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) both; }
  </style>
</head>
<body class="bg-gray-950 text-gray-900 font-sans antialiased min-h-screen flex items-center justify-center p-4 sm:p-8">

  <div class="auth-card w-full max-w-md bg-white rounded-[2rem] shadow-2xl overflow-hidden p-8 sm:p-12">

    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-10">
      <img src="{{ asset('images/logo.png') }}" alt="Gamana TV" class="w-8 h-8 object-contain" />
      <span class="font-black text-lg tracking-tighter text-gray-950">GAMANA</span>
    </a>

    <div class="auth-pop w-14 h-14 rounded-full bg-brand-red/10 flex items-center justify-center mb-6">
      <svg class="w-6 h-6 text-brand-red" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 0 1 2-2h2.28a1 1 0 0 1 .97.76l1 4a1 1 0 0 1-.5 1.11L7 10a12 12 0 0 0 7 7l1.13-1.75a1 1 0 0 1 1.11-.5l4 1a1 1 0 0 1 .76.97V19a2 2 0 0 1-2 2h-1C9.72 21 3 14.28 3 6V5Z" />
      </svg>
    </div>

    <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Verify your phone</h1>
    <p class="text-gray-500 text-sm mb-8">
      We've sent a 6-digit code to <span class="font-bold text-gray-900">{{ $phone }}</span>.
      It expires in 10 minutes.
    </p>

    @if ($errors->any())
      <div class="auth-shake mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-brand-red">
        <ul class="list-disc list-inside space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if (session('status'))
      <div class="auth-pop mb-6 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
        {{ session('status') }}
      </div>
    @endif

    <form
      method="POST"
      action="{{ route('verify.store') }}"
      class="space-y-4"
      x-data="{ loading: false }"
      @submit="loading = true"
    >
      @csrf

      <input
        type="text" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" placeholder="6-digit code" required autofocus
        class="w-full px-5 py-3.5 rounded-full border border-gray-200 text-center text-lg tracking-[0.5em] font-bold focus:outline-none focus:ring-2 focus:ring-brand-red/40 focus:border-brand-red transition-all duration-200"
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
        <span x-text="loading ? 'Verifying...' : 'Verify & Continue'"></span>
      </button>
    </form>

    <form
      method="POST"
      action="{{ route('verify.resend') }}"
      class="mt-6 text-center"
      x-data="{ sending: false }"
      @submit="sending = true"
    >
      @csrf
      <button
        type="submit"
        :disabled="sending"
        class="text-sm font-bold text-gray-900 hover:text-brand-red disabled:opacity-60 transition-colors duration-200"
      >
        <span x-text="sending ? 'Sending...' : 'Didn\'t get a code? Resend'"></span>
      </button>
    </form>
  </div>

</body>
</html>
