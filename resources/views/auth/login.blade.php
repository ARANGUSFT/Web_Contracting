<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contractor Login | Contracting Alliance Inc.</title>

  <!-- Tailwind CDN + config -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#003366',        // Deep blue
            'primary-light': '#1a4d80',
            'primary-dark': '#002244',
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
  <style>
    .modal-open { overflow: hidden; }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center p-4 font-sans">

  <div class="w-full max-w-6xl grid lg:grid-cols-5 rounded-3xl overflow-hidden shadow-brand bg-white">
    <!-- Panel izquierdo (2 columnas) -->
    <div class="relative lg:col-span-2 bg-gradient-to-br from-primary to-primary-dark p-8 text-white flex flex-col">
      <!-- Ornamentos simplificados -->
      <div class="absolute inset-0 opacity-5 pointer-events-none">
        <svg class="absolute top-0 right-0 w-48 h-48 text-white" viewBox="0 0 200 200" fill="currentColor"><circle cx="100" cy="100" r="100"/></svg>
      </div>

      <!-- Logo y título -->
      <div class="relative flex items-center gap-3 mb-8">
        <div class="h-14 w-14 bg-white/20 rounded-xl flex items-center justify-center p-2 backdrop-blur-sm">
          <img src="{{ asset('img/dd.png') }}" alt="Logo" class="h-10 w-auto object-contain" />
        </div>
        <div>
          <h2 class="text-xl font-bold">Contracting Alliance</h2>
          <p class="text-white/70 text-xs">Contractor Portal</p>
        </div>
      </div>

      <!-- Mensaje principal -->
      <div class="relative mb-6">
        <h1 class="text-3xl font-extrabold leading-tight">Welcome, Contractor</h1>
        <p class="text-white/80 text-lg mt-1">Your workspace is ready</p>
      </div>

      <!-- Características en 2 columnas -->
      <div class="relative grid grid-cols-2 gap-4 mt-4">
        <div class="flex items-start gap-2">
          <svg class="w-5 h-5 mt-0.5 text-white/80" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
          <span class="text-sm">Project management</span>
        </div>
        <div class="flex items-start gap-2">
          <svg class="w-5 h-5 mt-0.5 text-white/80" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
          <span class="text-sm">Documents</span>
        </div>
        <div class="flex items-start gap-2">
          <svg class="w-5 h-5 mt-0.5 text-white/80" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879.586.585.879 1.353.879 2.121s-.293 1.536-.879 2.121z" clip-rule="evenodd"/></svg>
          <span class="text-sm">Work orders</span>
        </div>
        <div class="flex items-start gap-2">
          <svg class="w-5 h-5 mt-0.5 text-white/80" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/></svg>
          <span class="text-sm">Real-time updates</span>
        </div>
      </div>

      <!-- Testimonio breve -->
      <div class="relative mt-auto pt-6 border-t border-white/20">
        <p class="text-sm text-white/70 italic">"The contractor portal saves me hours each week. Everything I need in one place."</p>
        <p class="text-xs text-white/50 mt-2">— Michael R., Site Supervisor</p>
      </div>
    </div>

    <!-- Panel derecho (3 columnas) -->
    <div class="lg:col-span-3 p-8 sm:p-10 bg-white">
      <!-- Versión móvil del branding -->
      <div class="lg:hidden flex flex-col items-center mb-8 pb-6 border-b border-slate-100">
        <div class="h-20 w-20 bg-primary/10 rounded-xl flex items-center justify-center p-2 mb-4">
          <img src="{{ asset('img/logo2.png') }}" alt="Logo" class="h-14 w-auto object-contain" />
        </div>
        <h1 class="text-xl font-bold text-primary">Contracting Alliance</h1>
        <p class="text-sm text-slate-500">Contractor Portal</p>
      </div>

      <div class="max-w-lg mx-auto lg:mx-0">
        <!-- Encabezado con botón de registro destacado -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
          <div>
            <h1 class="text-3xl font-bold text-slate-900">Contractor Login</h1>
            <p class="text-slate-500 mt-1">Access your personalized dashboard</p>
          </div>
          @if (Route::has('register'))
            <a href="{{ route('register') }}" 
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg border-2 border-primary text-primary font-semibold hover:bg-primary hover:text-white transition-colors duration-200 shadow-sm hover:shadow-md">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
              Create account
            </a>
          @endif
        </div>

        <!-- Mensajes de error -->
        @if ($errors->any())
          <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 flex items-start">
            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            <div class="text-sm">{{ $errors->first() }}</div>
          </div>
        @endif

        <!-- Formulario -->
        <form action="{{ route('login') }}" method="POST" class="space-y-6">
          @csrf

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email address</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" /><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" /></svg>
              </div>
              <input id="email" type="email" name="email" required autofocus value="{{ old('email') }}"
                    class="pl-10 w-full border border-slate-200 rounded-xl px-4 py-3.5 focus:ring-4 focus:ring-primary/20 focus:border-primary transition placeholder-slate-400 shadow-inner-lg"
                    placeholder="contractor@example.com" />
            </div>
            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>

          <!-- Password -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
              <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary hover:text-primary-dark transition-colors">Forgot password?</a>
            </div>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
              </div>
              <input id="password" type="password" name="password" required
                    class="pl-10 w-full border border-slate-200 rounded-xl px-4 py-3.5 focus:ring-4 focus:ring-primary/20 focus:border-primary transition placeholder-slate-400 shadow-inner-lg"
                    placeholder="••••••••" />
              <button type="button" aria-label="Show password" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600" onclick="togglePwd()">
                <svg id="eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </button>
            </div>
            @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>

          <!-- Remember me -->
          <div class="flex items-center">
            <label class="inline-flex items-center">
              <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-slate-300 rounded" />
              <span class="ml-2 text-sm text-slate-700">Remember this device</span>
            </label>
          </div>

          <!-- Submit -->
          <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-4 rounded-xl text-base font-semibold text-white bg-gradient-to-r from-primary to-primary-light hover:opacity-95 focus:ring-4 focus:ring-primary/30 transition transform hover:-translate-y-0.5 shadow-md">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Sign in to dashboard
          </button>

          <!-- Enlace a Política de Privacidad (modal) -->
          <div class="text-center">
            <button type="button" onclick="openModal()" class="text-sm text-slate-500 hover:text-primary transition-colors underline underline-offset-2">
              Privacy Policy
            </button>
          </div>
        </form>

        <div class="mt-10 pt-6 border-t border-slate-100 text-center text-sm text-slate-500">&copy; {{ date('Y') }} Contracting Alliance Inc. All rights reserved.</div>
      </div>
    </div>
  </div>

    <!-- Privacy Policy Modal -->
  <div id="privacyModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    
    <!-- Background Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
      
      <div class="relative bg-white w-full max-w-4xl rounded-xl shadow-2xl overflow-hidden">

        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b">
          <h2 class="text-2xl font-bold">Privacy Policy</h2>
          <button onclick="closePrivacyModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
            &times;
          </button>
        </div>

        <!-- Content (Scrollable) -->
        <div class="px-8 py-6 overflow-y-auto max-h-[70vh] text-sm text-gray-700 space-y-6">

          <p><strong>Contracting Alliance Inc.</strong><br>
          Last Updated: {{ date('F d, Y') }}</p>

          <p>
          This Privacy Policy describes how Contracting Alliance Inc. ("Company", "we", "our", or "us") collects, uses, stores, and protects personal and business information obtained through our digital platform.
          </p>

          <h3 class="font-semibold text-lg">1. Information We Collect</h3>

          <p>We may collect the following categories of information:</p>

          <ul class="list-disc pl-6 space-y-1">
            <li>First and last name</li>
            <li>Email address</li>
            <li>Phone number</li>
            <li>Company name</li>
            <li>Years of experience</li>
            <li>Profile photo</li>
            <li>Business documents and certifications</li>
            <li>Tax documentation (W9, EIN)</li>
            <li>Uploaded project files and photos</li>
            <li>Account activity logs</li>
            <li>IP address and device information</li>
          </ul>

          <h3 class="font-semibold text-lg">2. How We Use Information</h3>

          <ul class="list-disc pl-6 space-y-1">
            <li>To verify contractor and subcontractor credentials</li>
            <li>To approve or reject account registrations</li>
            <li>To manage platform operations</li>
            <li>To maintain security and prevent fraud</li>
            <li>To comply with legal and tax obligations</li>
            <li>To improve system performance</li>
          </ul>

          <p>We do not sell personal information.</p>

          <h3 class="font-semibold text-lg">3. Account Approval</h3>

          <p>
          Contractor accounts must be manually approved. Subcontractors may be created by administrators or apply for review. We reserve the right to approve, deny, suspend, or terminate accounts at our discretion.
          </p>

          <h3 class="font-semibold text-lg">4. Data Storage and Security</h3>

          <p>
          Data is hosted through HostGator servers. We implement reasonable technical and administrative safeguards including authentication controls, restricted access, encrypted password storage, and activity monitoring.
          </p>

          <p>
          No online system is completely secure, and we cannot guarantee absolute security.
          </p>

          <h3 class="font-semibold text-lg">5. California Residents (CCPA)</h3>

          <p>
          If you are a California resident, you may request:
          </p>

          <ul class="list-disc pl-6 space-y-1">
            <li>Access to personal information collected</li>
            <li>Deletion of personal data (subject to legal exceptions)</li>
            <li>Information regarding data categories collected</li>
            <li>Confirmation that data is not sold</li>
          </ul>

          <p>
          To exercise these rights, contact us at:<br>
          <strong>infocontractingalliance@gmail.com</strong>
          </p>

          <h3 class="font-semibold text-lg">6. Data Retention</h3>

          <p>
          We retain information while accounts remain active and as required for legal, tax, and compliance purposes.
          </p>

          <h3 class="font-semibold text-lg">7. Account Deletion</h3>

          <p>
          Users may request account deletion by contacting us. Certain records may be retained as required by law.
          </p>

          <h3 class="font-semibold text-lg">8. Changes to This Policy</h3>

          <p>
          We may update this Privacy Policy at any time. Continued use of the platform constitutes acceptance of the updated terms.
          </p>

          <h3 class="font-semibold text-lg">9. Contact Information</h3>

          <p>
          Contracting Alliance Inc.<br>
          Email: infocontractingalliance@gmail.com
          </p>

        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t flex justify-end">
          <button onclick="closePrivacyModal()" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
            Close
          </button>
        </div>

      </div>
    </div>
  </div>

<script>
  // Función para mostrar/ocultar contraseña
  function togglePwd() {
    const pwdInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye');
    if (pwdInput.type === 'password') {
      pwdInput.type = 'text';
      eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
    } else {
      pwdInput.type = 'password';
      eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }
  }

  // Modal functions
  const modal = document.getElementById('privacyModal');
  const body = document.body;

  function openModal() {
    modal.classList.remove('hidden');
    body.classList.add('modal-open'); // Previene scroll del fondo
  }

  function closePrivacyModal() {
    modal.classList.add('hidden');
    body.classList.remove('modal-open');
  }

  // Cerrar modal al hacer clic en el overlay (fondo oscuro)
  document.addEventListener('DOMContentLoaded', function() {
    // Seleccionamos el overlay por su clase (es único)
    const overlay = document.querySelector('#privacyModal > .fixed.inset-0.bg-black');
    if (overlay) {
      overlay.addEventListener('click', closePrivacyModal);
    }

    // También prevenimos que el clic dentro del contenido del modal cierre el modal
    const modalContent = document.querySelector('#privacyModal .relative.bg-white');
    if (modalContent) {
      modalContent.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    }
  });

  // Opcional: cerrar con tecla Escape
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
      closePrivacyModal();
    }
  });
</script>
</body>
</html>