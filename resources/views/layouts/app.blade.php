<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Contracting Alliance Inc')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('img/logo2.png') }}">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #d1d3d4;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        .layout-container {
            width: 100%;
            padding-top: 80px;
            display: flex;
            justify-content: center;
        }
        .content {
            max-width: 1200px;
            width: 100%;
            padding: 20px;
            background: rgb(238, 237, 237);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-bottom: 30px;
        }

        /* ══ SESSION TIMEOUT MODAL ══ */
        .session-modal-overlay {
            display: none; position: fixed; inset: 0; z-index: 99999;
            background: rgba(0,0,0,.55); backdrop-filter: blur(3px);
            align-items: center; justify-content: center;
        }
        .session-modal-overlay.show { display: flex; }
        .session-modal-box {
            background: #fff; border-radius: 18px; padding: 32px 28px;
            max-width: 380px; width: 90%; text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,.2);
            animation: popIn .25s ease;
        }
        .session-modal-icon {
            width: 58px; height: 58px; border-radius: 50%; margin: 0 auto 18px;
            background: #fffbeb; border: 2px solid #fde68a;
            display: flex; align-items: center; justify-content: center; font-size: 24px;
        }
        .session-modal-title {
            font-size: 17px; font-weight: 700; color: #0f1117; margin-bottom: 8px;
            font-family: 'Poppins', sans-serif;
        }
        .session-modal-body {
            font-size: 13px; font-weight: 500; color: #6b7280;
            line-height: 1.6; margin-bottom: 22px; font-family: 'Poppins', sans-serif;
        }
        .session-countdown {
            font-size: 32px; font-weight: 800; color: #d97706;
            display: block; margin: 8px 0;
        }
        .session-modal-actions { display: flex; gap: 10px; }
        .session-btn-stay {
            flex: 1; padding: 11px; border-radius: 10px;
            background: #1855e0; color: #fff; border: none; cursor: pointer;
            font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600;
            transition: background .13s;
        }
        .session-btn-stay:hover { background: #1344c2; }
        .session-btn-logout {
            flex: 1; padding: 11px; border-radius: 10px;
            background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; cursor: pointer;
            font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600;
            transition: all .13s;
        }
        .session-btn-logout:hover { background: #fff0f0; border-color: #fbcfcf; color: #d92626; }

        @keyframes popIn { from{opacity:0;transform:scale(.92)} to{opacity:1;transform:scale(1)} }
    </style>
</head>
<body>

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Contenido --}}
    <div class="layout-container">
        <main class="content">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Extras -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- ══ SESSION TIMEOUT MODAL ══ --}}
    <div class="session-modal-overlay" id="sessionModal">
        <div class="session-modal-box">
            <div class="session-modal-icon">⏱️</div>
            <div class="session-modal-title">Your session is about to expire</div>
            <div class="session-modal-body">
                Due to inactivity, your session will close in
                <span class="session-countdown" id="sessionCountdown">60</span>
                seconds. Do you want to continue?
            </div>
            <div class="session-modal-actions">
                <button class="session-btn-stay" onclick="stayLoggedIn()">
                    ✓ &nbsp;Stay logged in
                </button>
                <button class="session-btn-logout" onclick="logoutNow()">
                    Sign out
                </button>
            </div>
        </div>
    </div>

    {{-- Form oculto para logout --}}
    <form id="sessionLogoutForm" action="{{ route('logout') }}" method="POST" style="display:none">
        @csrf
    </form>

    <script>
    /* ════════════════════════════════════════════
       SESSION TIMEOUT
       IDLE_MINUTES   = minutos de inactividad antes de mostrar el aviso
       COUNTDOWN_SECS = segundos para responder antes del logout automático
    ════════════════════════════════════════════ */
    (function() {
        const IDLE_MINUTES   = 59; // cambia a 0.1 para probar (≈ 6 segundos)
        const COUNTDOWN_SECS = 60;

        let idleTimer, countdownTimer, secondsLeft;
        const modal   = document.getElementById('sessionModal');
        const countEl = document.getElementById('sessionCountdown');

        function resetIdle() {
            clearTimeout(idleTimer);
            idleTimer = setTimeout(showWarning, IDLE_MINUTES * 60 * 1000);
        }

        function showWarning() {
            secondsLeft         = COUNTDOWN_SECS;
            countEl.textContent = secondsLeft;
            modal.classList.add('show');

            countdownTimer = setInterval(function() {
                secondsLeft--;
                countEl.textContent = secondsLeft;
                if (secondsLeft <= 0) {
                    clearInterval(countdownTimer);
                    logoutNow();
                }
            }, 1000);
        }

        window.stayLoggedIn = function() {
            clearInterval(countdownTimer);
            modal.classList.remove('show');
            fetch(window.location.href, { method: 'HEAD', credentials: 'same-origin' });
            resetIdle();
        };

        window.logoutNow = function() {
            clearInterval(countdownTimer);
            modal.classList.remove('show');
            document.getElementById('sessionLogoutForm').submit();
        };

        ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(function(evt) {
            document.addEventListener(evt, resetIdle, { passive: true });
        });

        resetIdle();
    })();
    </script>

</body>
</html>