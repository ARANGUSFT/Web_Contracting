<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="{{ asset('img/logo2.png') }}">

    <!-- Custom CSS -->
    <style>
        
        :root {
            --primary-color: #255b88;
            --secondary-color: #003366;
        }
        body {
            background: linear-gradient(120deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
        }
        .auth-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }
        .auth-header {
            background-color: var(--primary-color);
            color: #fff;
            padding: 1.25rem 1.25rem; /* más compacto para móvil */
            position: relative;
        }
        /* NUEVO: contenedor interno para logo + idioma en flex */
        .auth-header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }
        .logo-wrap { min-width: 0; }
        /* Logo totalmente responsive: no fuerzas altura fija */
        .logo-img {
            display: block;
            max-width: 70vw;      /* evita desbordes en pantallas pequeñas */
            height: auto;         /* mantiene proporción */
            max-height: 60px;     /* tope en desktop */
            object-fit: contain;  /* por si llega a tener caja más grande */
        }
        /* Botón de idioma con buen contraste sobre header */
        .btn-language {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #fff;
            border-radius: 20px;
            padding: 0.4rem 0.9rem;
            backdrop-filter: saturate(120%) blur(2px);
        }
        .btn-language:hover { background: rgba(255, 255, 255, 0.3); }

        .auth-body { padding: 2rem; }
        .footer-note {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #fff;
            opacity: 0.9;
        }
        .form-control {
            padding: 0.8rem 1rem;
            border-radius: 8px;
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 74, 153, 0.25);
        }
        .input-group-text { background-color: #f8f9fa; border-radius: 8px 0 0 8px; }
        .btn-primary { background-color: var(--primary-color); border: none; padding: 0.8rem; border-radius: 8px; font-weight: 600; transition: all 0.3s; }
        .btn-primary:hover { background-color: #003a7a; }
        .form-check-input:checked { background-color: var(--primary-color); border-color: var(--primary-color); }
        .forgot-password { color: var(--primary-color); text-decoration: none; }
        .forgot-password:hover { color: #003a7a; text-decoration: underline; }

        /* Ajustes finos para móvil */
        @media (max-width: 576px) {
            .auth-header { padding: 0.9rem 0.9rem; }
            .logo-img { max-height: 46px; max-width: 65vw; }
            .btn-language { padding: 0.35rem 0.7rem; font-size: .875rem; }
            .auth-body { padding: 1.25rem; }
            /* Opcional: oculta el texto del botón, deja solo icono en XS */
            .btn-language .lang-text { display: none; }
        }
    </style>

    @stack('css')
</head>
<body>

    <div class="container">
        <div class="auth-container mx-auto">
            <div class="auth-header">
                <div class="auth-header-inner">
                    <div class="logo-wrap">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-img img-fluid">
                    </div>
                    
                    <div class="dropdown">
                        <button class="btn btn-language dropdown-toggle d-inline-flex align-items-center" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-translate me-2"></i>
                            <span class="lang-text">{{ strtoupper(app()->getLocale()) }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li><a class="dropdown-item">English</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="auth-body">
                {{ $slot }}
            </div>
        </div>

        <div class="footer-note">
            &copy; {{ date('Y') }} Contracting Alliance Inc. {{ __('All rights reserved') }}.
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Desactiva envío doble y muestra spinner en botones submit
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        const spinner = document.createElement('span');
                        spinner.classList.add('spinner-border', 'spinner-border-sm', 'me-2');
                        spinner.setAttribute('role', 'status');
                        spinner.setAttribute('aria-hidden', 'true');
                        submitBtn.prepend(spinner);
                        submitBtn.disabled = true;
                    }
                });
            });

            // Inicializar inputs de teléfono internacional
            document.querySelectorAll('input[type="tel"]').forEach(input => {
                if (window.intlTelInput) {
                    window.intlTelInput(input, {
                        initialCountry: 'auto',
                        geoIpLookup: function(callback) {
                            fetch('https://ipapi.co/json')
                                .then(res => res.json())
                                .then(data => callback(data.country_code))
                                .catch(() => callback('us'));
                        },
                        utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js'
                    });
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
