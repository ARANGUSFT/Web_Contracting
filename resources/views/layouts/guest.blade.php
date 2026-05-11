<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap (for components in slot content) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/logo2.png') }}">

    <style>
    *, *::before, *::after { box-sizing: border-box; }

    :root {
        --primary:     #255b88;
        --primary-dk:  #003366;
        --primary-lt:  #e8f2fb;
        --accent:      #1a7abf;
        --surf:        #ffffff;
        --bg:          #f0f5fa;
        --ink:         #0f172a;
        --ink2:        #475569;
        --ink3:        #94a3b8;
        --bd:          #e2e8f0;
        --red:         #dc2626;
    }

    html, body {
        min-height: 100%;
        font-family: 'Inter', 'Segoe UI', sans-serif;
        background: linear-gradient(140deg, var(--primary) 0%, var(--primary-dk) 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 32px 16px;
        position: relative;
        overflow-x: hidden;
    }

    /* Background texture */
    body::before {
        content: '';
        position: fixed; inset: 0; pointer-events: none;
        background-image:
            linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
        background-size: 48px 48px;
    }

    /* Glow blobs */
    body::after {
        content: '';
        position: fixed; top: -100px; right: -100px;
        width: 400px; height: 400px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 65%);
        pointer-events: none;
    }

    /* ══ CARD ══ */
    .auth-card {
        position: relative;
        background: var(--surf);
        border-radius: 20px;
        box-shadow: 0 24px 64px rgba(0,20,60,.35);
        overflow: hidden;
        width: 100%;
        max-width: 480px;
        animation: cardIn .45s ease both;
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: none; }
    }

    /* ══ HEADER ══ */
    .auth-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dk) 100%);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        position: relative;
        overflow: hidden;
    }

    /* Header grid texture */
    .auth-header::before {
        content: '';
        position: absolute; inset: 0; pointer-events: none;
        background-image:
            linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
        background-size: 32px 32px;
    }
    /* Header glow */
    .auth-header::after {
        content: '';
        position: absolute; right: -40px; top: -40px;
        width: 160px; height: 160px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .header-logo {
        position: relative;
        display: flex; align-items: center; gap: 10px; min-width: 0;
    }
    .header-logo img {
        display: block;
        height: 36px;
        max-width: 200px;
        width: auto;
        object-fit: contain;
        flex-shrink: 0;
    }

    /* Language dropdown */
    .lang-btn {
        position: relative;
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
        color: rgba(255,255,255,.85); border-radius: 999px;
        padding: 6px 14px; font-size: 12px; font-weight: 600;
        cursor: pointer; transition: background .15s;
        font-family: 'Inter', sans-serif; flex-shrink: 0;
        white-space: nowrap;
    }
    .lang-btn:hover { background: rgba(255,255,255,.2); color: #fff; }
    .lang-btn .bi { font-size: 13px; }

    /* Bootstrap dropdown override */
    .dropdown-menu {
        border-radius: 12px;
        border: 1px solid var(--bd);
        box-shadow: 0 8px 24px rgba(0,0,0,.12);
        padding: 6px;
        min-width: 140px;
    }
    .dropdown-item {
        border-radius: 8px;
        font-size: 13px; font-weight: 500;
        padding: 8px 12px;
        transition: background .13s;
    }
    .dropdown-item:hover { background: var(--primary-lt); color: var(--primary); }

    /* ══ BODY ══ */
    .auth-body { padding: 28px 28px 24px; }

    /* ══ FOOTER NOTE ══ */
    .auth-footer {
        margin-top: 20px;
        text-align: center;
        font-size: 12px; font-weight: 500;
        color: rgba(255,255,255,.45);
        position: relative;
    }

    /* ══ FORM OVERRIDES (for slot content) ══ */
    .auth-body .form-label {
        font-size: 12px; font-weight: 600;
        color: var(--ink2); margin-bottom: 6px;
    }
    .auth-body .form-control,
    .auth-body .form-select {
        padding: 11px 14px;
        border-radius: 10px;
        border: 1.5px solid var(--bd);
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        background: #f8fafc;
        color: var(--ink);
        transition: border-color .15s, box-shadow .15s, background .15s;
    }
    .auth-body .form-control::placeholder { color: #b8c4cf; }
    .auth-body .form-control:focus,
    .auth-body .form-select:focus {
        border-color: var(--primary);
        background: var(--surf);
        box-shadow: 0 0 0 3px rgba(37,91,136,.1);
        outline: none;
    }
    .auth-body .input-group-text {
        background: #f1f5f9;
        border: 1.5px solid var(--bd);
        border-radius: 10px 0 0 10px;
        color: var(--ink3);
    }
    .auth-body .input-group .form-control { border-radius: 0 10px 10px 0; }

    .auth-body .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dk) 100%);
        border: none; padding: 12px;
        border-radius: 10px; font-weight: 600;
        font-size: 14px; font-family: 'Inter', sans-serif;
        box-shadow: 0 4px 14px rgba(0,51,102,.25);
        transition: all .15s; width: 100%;
    }
    .auth-body .btn-primary:hover {
        background: linear-gradient(135deg, #1d4d76 0%, #002244 100%);
        box-shadow: 0 6px 20px rgba(0,51,102,.35);
        transform: translateY(-1px);
    }
    .auth-body .btn-primary:active { transform: translateY(0); }

    .auth-body .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    .auth-body a.forgot-password,
    .auth-body .forgot-password {
        color: var(--accent); text-decoration: none;
        font-size: 13px; font-weight: 600; transition: color .13s;
    }
    .auth-body a.forgot-password:hover { color: var(--primary-dk); }

    .auth-body .invalid-feedback { font-size: 11.5px; font-weight: 500; }
    .auth-body .form-control.is-invalid { border-color: var(--red); background: #fef2f2; }

    /* Dividers */
    .auth-divider {
        display: flex; align-items: center; gap: 12px;
        margin: 20px 0; font-size: 12px; font-weight: 600; color: var(--ink3);
    }
    .auth-divider::before, .auth-divider::after {
        content: ''; flex: 1; height: 1px; background: var(--bd);
    }

    /* ══ RESPONSIVE ══ */
    @media (max-width: 520px) {
        body { padding: 20px 12px; }
        .auth-card { border-radius: 16px; }
        .auth-header { padding: 16px 18px; }
        .header-logo img { height: 30px; }
        .auth-body { padding: 22px 18px 20px; }
        .lang-btn .lang-text { display: none; }
        .lang-btn { padding: 6px 10px; }
    }
    </style>

    @stack('css')
</head>
<body>

    {{-- Grid glow bottom-left --}}
    <div style="position:fixed;bottom:-80px;left:-80px;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(0,102,204,.3) 0%,transparent 65%);pointer-events:none"></div>

    <div class="auth-card">

        {{-- Header --}}
        <div class="auth-header">
            <div class="header-logo">
                <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}">
            </div>

            <div class="dropdown">
                <button class="lang-btn dropdown-toggle" type="button"
                        id="langDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-translate"></i>
                    <span class="lang-text">{{ strtoupper(app()->getLocale()) }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                    <li><a class="dropdown-item">
                        <i class="bi bi-check2 me-2 text-primary"></i> English
                    </a></li>
                </ul>
            </div>
        </div>

        {{-- Slot content --}}
        <div class="auth-body">
            {{ $slot }}
        </div>

    </div>

    <div class="auth-footer">
        &copy; {{ date('Y') }} Contracting Alliance Inc. &nbsp;·&nbsp; {{ __('All rights reserved') }}.
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // Spinner on submit
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function () {
                const btn = this.querySelector('button[type="submit"]');
                if (btn && !btn.disabled) {
                    const sp = document.createElement('span');
                    sp.classList.add('spinner-border', 'spinner-border-sm', 'me-2');
                    sp.setAttribute('role', 'status');
                    sp.setAttribute('aria-hidden', 'true');
                    btn.prepend(sp);
                    btn.disabled = true;
                }
            });
        });

        // Intl phone inputs
        document.querySelectorAll('input[type="tel"]').forEach(input => {
            if (window.intlTelInput) {
                window.intlTelInput(input, {
                    initialCountry: 'auto',
                    geoIpLookup: cb => {
                        fetch('https://ipapi.co/json')
                            .then(r => r.json())
                            .then(d => cb(d.country_code))
                            .catch(() => cb('us'));
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