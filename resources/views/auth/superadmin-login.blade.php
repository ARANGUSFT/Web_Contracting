<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login | Contracting Alliance Inc.</title>

  <!-- Tailwind CDN + config -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#003366',        // Deep blue
            'primary-light': '#1a4d80', // Lighter blue
            'primary-dark': '#002244', // Darker blue
            slate: {
              950: '#0b1220'
            }
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif']
          },
          boxShadow: {
            brand: '0 30px 60px -20px rgba(0,0,0,.25)',
            'inner-lg': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)'
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center p-4 font-sans">

  <div class="w-full max-w-5xl grid lg:grid-cols-2 rounded-3xl overflow-hidden shadow-brand bg-white">
    <!-- Panel de marca (izquierda) -->
    <div class="relative hidden lg:flex flex-col justify-between bg-gradient-to-br from-primary to-primary-dark p-10">
      <!-- Ornamentos -->
      <div class="absolute inset-0 opacity-10 pointer-events-none">
        <svg class="absolute -top-6 -left-6 w-64 h-64 text-white" viewBox="0 0 200 200" fill="currentColor" aria-hidden="true"><circle cx="100" cy="100" r="100"/></svg>
        <svg class="absolute bottom-8 right-8 w-40 h-40 text-white" viewBox="0 0 200 200" fill="currentColor" aria-hidden="true"><rect width="200" height="200" rx="32"/></svg>
      </div>

      <!-- Branding superior - Solo con la imagen -->
      <div class="relative flex justify-center">
        <div class="flex flex-col items-center">
          <div class="h-24 w-24 bg-white/20 rounded-2xl flex items-center justify-center p-3 backdrop-blur-sm mb-4">
            <img src="{{ asset('img/logo.png') }}" alt="Contracting Alliance Inc. Logo" class="h-16 w-auto object-contain" />
          </div>
          <h2 class="text-2xl font-bold text-white text-center mt-2">Contracting Alliance</h2>
          <p class="text-white/80 text-sm mt-1">Administrative System</p>
        </div>
      </div>

      <!-- Contenido central -->
      <div class="relative flex-1 flex flex-col justify-center items-center text-center mt-8">
        <h2 class="text-2xl font-bold text-white max-w-md leading-tight mb-4">Secure Admin Portal</h2>
        <p class="text-white/80 max-w-md">Access your management dashboard with enterprise-grade security.</p>
        
        <div class="mt-10 space-y-4">
          <div class="flex items-center gap-3 text-white/80">
            <svg class="w-5 h-5 flex-shrink-0 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            <span class="text-sm">Advanced security protocols</span>
          </div>
          <div class="flex items-center gap-3 text-white/80">
            <svg class="w-5 h-5 flex-shrink-0 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            <span class="text-sm">Real-time monitoring</span>
          </div>
          <div class="flex items-center gap-3 text-white/80">
            <svg class="w-5 h-5 flex-shrink-0 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            <span class="text-sm">Role-based access control</span>
          </div>
        </div>
      </div>

      <!-- Información inferior -->
      <div class="relative text-center">
        <div class="flex items-center justify-center gap-3 text-white/70 text-sm mt-8 pt-6 border-t border-white/20">
          <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.2 6.5 10.266a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/></svg>
          <span>Authorized access only</span>
        </div>
      </div>
    </div>

    <!-- Panel de formulario (derecha) -->
    <div class="relative p-8 sm:p-12">
      <!-- Branding compacto para móvil -->
      <div class="lg:hidden flex flex-col items-center mb-8 pb-6 border-b border-slate-100">
        <div class="h-20 w-20 bg-primary/10 rounded-xl flex items-center justify-center p-2 mb-4">
          <img src="{{ asset('img/logo.png') }}" alt="Contracting Alliance Inc. Logo" class="h-14 w-auto object-contain" />
        </div>
        <div class="text-center">
          <h1 class="text-xl font-bold text-primary">Contracting Alliance</h1>
          <p class="text-sm text-slate-500 mt-1">Administrative Portal</p>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary-dark border border-primary/20 mt-3">Secure Login</span>
      </div>

      <div class="max-w-md mx-auto lg:mx-0">
        <div class="text-center lg:text-left">
          <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Admin Login</h1>
          <p class="mt-2 text-slate-500">Enter your credentials to continue</p>
        </div>

        @if ($errors->any())
          <div class="mt-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 flex items-start">
            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <div class="text-sm">{{ $errors->first() }}</div>
          </div>
        @endif

        <form action="{{ route('superadmin.login') }}" method="POST" class="mt-8 space-y-6" novalidate>
          @csrf

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email address</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
              </div>
              <input id="email" type="email" name="email" required autofocus value="{{ old('email') }}"
                    class="pl-10 w-full border border-slate-200 rounded-xl px-4 py-3.5 focus:ring-4 focus:ring-primary/20 focus:border-primary transition placeholder-slate-400 shadow-inner-lg"
                    placeholder="admin@contractingalliance.com" />
            </div>
            @error('email')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Password -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
              <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary hover:text-primary-dark transition-colors">Forgot password?</a>
            </div>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
              </div>
              <input id="password" type="password" name="password" required
                    class="pl-10 w-full border border-slate-200 rounded-xl px-4 py-3.5 focus:ring-4 focus:ring-primary/20 focus:border-primary transition placeholder-slate-400 shadow-inner-lg"
                    placeholder="••••••••" />
              <button type="button" aria-label="Show password" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors" onclick="togglePwd()">
                <svg id="eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </button>
            </div>
            @error('password')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Remember me -->
          <div class="flex items-center">
            <label class="inline-flex items-center">
              <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-slate-300 rounded" />
              <span class="ml-2 text-sm text-slate-700">Remember this device</span>
            </label>
          </div>

          <!-- Submit -->
          <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-4 rounded-xl text-base font-semibold text-white bg-gradient-to-r from-primary to-primary-light hover:opacity-95 focus:outline-none focus:ring-4 focus:ring-primary/30 transition transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Sign in to dashboard
          </button>

        </form>

        <div class="mt-10 pt-6 border-t border-slate-100 text-center text-sm text-slate-500">&copy; {{ date('Y') }} Contracting Alliance Inc. All rights reserved.</div>
      </div>
    </div>
  </div>

  <script>
    function togglePwd() {
      const input = document.getElementById('password');
      const eye = document.getElementById('eye');
      const isPassword = input.getAttribute('type') === 'password';
      input.setAttribute('type', isPassword ? 'text' : 'password');
      eye.innerHTML = isPassword
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.548-4.26M15 12a3 3 0 00-4.243-2.829M9.88 9.88L4.12 4.12M6.1 6.1L3 3m5.064 12.936A9.956 9.956 0 0012 19c4.478 0 8.268-2.943 9.542-7a9.97 9.97 0 00-4.043-5.197" />'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }
  </script>
</body>
</html>