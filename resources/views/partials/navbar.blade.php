<nav class="navbar">
    <div class="container">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="navbar-logo">
            <img src="{{ asset('img/logo.png') }}" alt="Logo">
        </a>

        <!-- Botón menú responsive -->
        <button class="menu-toggle" id="menuToggle">
            <i class="bi bi-list"></i>
        </button>

        @php
            $webUser  = Auth::guard('web')->user();
            $teamUser = Auth::guard('team')->user();
        @endphp

        <!-- Menú principal -->
        <ul class="navbar-menu" id="navbarMenu">
            @if($webUser)
                <li><a href="{{ route('calendar.view') }}"><i class="bi bi-kanban"></i> Project Manager</a></li>
                <li><a href="{{ route('leads.index') }}"><i class="bi bi-building"></i> CRM</a></li>
                <li><a href="{{ route('leads.create') }}"><i class="bi bi-person-plus"></i> Leads</a></li>
                <li><a href="{{ route('teams.index') }}"><i class="bi bi-people"></i> Manage Team</a></li>
                <li><a href="{{ $webUser->is_admin ? route('superadmin.chat.view') : route('user.chat.view') }}"><i class="bi bi-chat-dots"></i> Chat</a></li>
            @elseif($teamUser)
                @switch($teamUser->role)
                    @case('sales')
                        <li><a href="{{ route('seller.dashboard') }}"><i class="bi bi-kanban"></i> CRM</a></li>
                        <li><a href="{{ route('seller.create') }}"><i class="bi bi-person-plus"></i> Create Lead</a></li>
                        @break
                    @case('guest')
                        <li><a href="{{ route('guest.dashboard') }}"><i class="bi bi-house-door"></i> Guest Panel</a></li>
                        @break
                    @case('manager')
                        <li><a href="{{ route('manager.dashboard') }}"><i class="bi bi-bar-chart"></i> Manager Panel</a></li>
                        <li><a href="{{ route('manager.calendar') }}"><i class="bi bi-calendar-event"></i> My Schedule</a></li>
                        @break
                    @case('crew')
                        <li><a href="{{ route('crew.dashboard') }}"><i class="bi bi-tools"></i> Crew Area</a></li>
                        <li><a href="{{ route('crew.calendar') }}"><i class="bi bi-calendar-event"></i> My Schedule</a></li>
                        @break
                    @case('project_manager')
                        <li><a href="{{ route('project.dashboard') }}"><i class="bi bi-diagram-3"></i> Project Manager</a></li>
                        <li><a href="{{ route('project.calendar') }}"><i class="bi bi-calendar-event"></i> My Schedule</a></li>
                        @break
                    @case('company_admin')
                        <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-building"></i> Admin Panel</a></li>
                        @break
                @endswitch
            @endif

            <!-- Dropdown de usuario -->
            @if($webUser || $teamUser)
            <li class="user-dropdown">
                <button class="dropdown-btn" id="userDropdownBtn">
                    <i class="bi bi-person-circle"></i>
                    <span class="user-name">{{ $webUser ? $webUser->company_name : $teamUser->name }}</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="dropdown-menu" id="userDropdownMenu">
                    @php
                        $profileRoute = '#';
                        if ($webUser) {
                            $profileRoute = route('profile.edit');
                        } elseif ($teamUser) {
                            $profileRoute = match($teamUser->role) {
                                'sales'           => route('seller.profile.edit'),
                                'guest'           => route('guest.profile.edit'),
                                'manager'         => route('manager.profile.edit'),
                                'crew'            => route('crew.profile.edit'),
                                'project_manager' => route('project.profile.edit'),
                                'company_admin'   => route('admin.profile.edit'),
                                default           => '#'
                            };
                        }
                    @endphp
                    <a href="{{ $profileRoute }}"><i class="bi bi-person"></i> Profile</a>
                    <form method="POST" action="{{ $webUser ? route('logout') : route('team.logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Log Out</button>
                    </form>
                </div>
            </li>
            @endif
        </ul>
    </div>
</nav>

{{-- ══ SESSION TIMEOUT MODAL ══
     Funciona para web users (contratistas) y team users (todos los roles).
     La ruta de logout se resuelve dinámicamente igual que en el navbar.
--}}
@if($webUser || $teamUser)

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

{{-- Form oculto — misma lógica que el botón de logout del navbar --}}
<form id="sessionLogoutForm"
      action="{{ $webUser ? route('logout') : route('team.logout') }}"
      method="POST" style="display:none">
    @csrf
</form>

@endif

<!-- JavaScript para el toggle del menú y dropdown -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const navbarMenu = document.getElementById('navbarMenu');
    const dropdownBtn  = document.getElementById('userDropdownBtn');
    const dropdownMenu = document.getElementById('userDropdownMenu');

    if (menuToggle && navbarMenu) {
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            navbarMenu.classList.toggle('show');
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-list');
                icon.classList.toggle('bi-x-lg');
            }
        });
    }

    if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
            const arrow = this.querySelector('.bi-chevron-down');
            if (arrow) {
                arrow.style.transform = dropdownMenu.classList.contains('show') ? 'rotate(180deg)' : '';
            }
        });

        document.addEventListener('click', function(e) {
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
                const arrow = dropdownBtn.querySelector('.bi-chevron-down');
                if (arrow) arrow.style.transform = '';
            }
        });
    }

    if (navbarMenu) {
        navbarMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    navbarMenu.classList.remove('show');
                    const icon = menuToggle?.querySelector('i');
                    if (icon) {
                        icon.classList.remove('bi-x-lg');
                        icon.classList.add('bi-list');
                    }
                }
            });
        });
    }

    /* ════════════════════════════════════════════
       SESSION TIMEOUT
       IDLE_MINUTES   = minutos de inactividad antes de mostrar el aviso
       COUNTDOWN_SECS = segundos para responder antes del logout automático
       Para probar: IDLE_MINUTES = 0.1 (~6 segundos)
    ════════════════════════════════════════════ */
    const sessionModal = document.getElementById('sessionModal');
    if (!sessionModal) return; // no hay usuario logueado, no hace nada

    const IDLE_MINUTES   = 59;
    const COUNTDOWN_SECS = 60;

    let idleTimer, countdownTimer, secondsLeft;
    const countEl = document.getElementById('sessionCountdown');

    function resetIdle() {
        clearTimeout(idleTimer);
        idleTimer = setTimeout(showWarning, IDLE_MINUTES * 60 * 1000);
    }

    function showWarning() {
        secondsLeft         = COUNTDOWN_SECS;
        countEl.textContent = secondsLeft;
        sessionModal.classList.add('show');

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
        sessionModal.classList.remove('show');
        fetch(window.location.href, { method: 'HEAD', credentials: 'same-origin' });
        resetIdle();
    };

    window.logoutNow = function() {
        clearInterval(countdownTimer);
        sessionModal.classList.remove('show');
        document.getElementById('sessionLogoutForm').submit();
    };

    ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(function(evt) {
        document.addEventListener(evt, resetIdle, { passive: true });
    });

    resetIdle();
});
</script>

<!-- ============================================
     ESTILOS NAVBAR + SESSION MODAL
     ============================================ -->
<style>
/* ══ NAVBAR ══ */
.navbar {
    background: #ffffff;
    padding: 8px 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 8px rgba(0,0,0,.1);
}
.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 90%;
    max-width: 1200px;
}
.navbar-logo img {
    height: 22px;
    transition: transform .3s ease;
}
.navbar-logo img:hover { transform: scale(1.1); }

.navbar-menu {
    display: flex; align-items: center; gap: 30px;
    padding: 0; margin: 0; list-style: none;
}
.navbar-menu li { list-style: none; }
.navbar-menu a {
    padding: 10px 18px; font-size: 14px; color: rgb(16,32,88);
    display: flex; align-items: center; gap: 10px;
    border-radius: 6px; text-decoration: none;
    transition: background .2s ease, transform .2s ease;
}
.navbar-menu a:hover { background-color: rgba(0,0,0,.05); transform: translateY(-1px); }

.user-dropdown {
    position: relative; display: flex; align-items: center;
    margin-left: 20px; padding-left: 20px; border-left: 1px solid #dee2e6;
}
.dropdown-btn {
    background: none; border: none; color: rgb(16,32,88); font-size: 14px;
    cursor: pointer; display: flex; align-items: center; gap: 5px;
    padding: 8px 16px; border-radius: 40px; transition: background .2s;
}
.dropdown-btn:hover { background-color: #f0f2f5; }
.menu-toggle {
    display: none; background: none; border: none; color: rgb(16,32,88);
    font-size: 28px; cursor: pointer; padding: 8px;
    border-radius: 8px; transition: background .2s;
}
.menu-toggle:hover { background-color: #f0f2f5; }
.dropdown-menu {
    display: none; position: absolute; top: calc(100% + 8px); right: 0;
    background: white; min-width: 220px; border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,.1); border: 1px solid #dee2e6;
    z-index: 1000; opacity: 0; transform: translateY(-8px);
    transition: opacity .2s ease, transform .2s ease; overflow: hidden; padding: 8px 0;
}
.dropdown-menu.show { display: block; opacity: 1; transform: translateY(0); }
.dropdown-menu a, .logout-btn {
    display: flex; align-items: center; gap: 12px; padding: 12px 20px;
    color: #333; text-decoration: none; font-size: 14px; font-weight: 500;
    transition: background .2s ease; width: 100%; text-align: left;
    border: none; background: none; cursor: pointer;
}
.dropdown-menu a:hover, .logout-btn:hover { background: #f0f5ff; }
.logout-btn { color: #dc3545; }
.logout-btn:hover { background: #ffebee; }

.navbar-menu a i { color: rgb(16,32,88); font-size: 1.2rem; transition: color .2s; }
.navbar-menu a:hover i { color: #0d6efd; }
.dropdown-btn i:first-child { color: rgb(16,32,88); font-size: 1.4rem; }
.dropdown-btn i:last-child  { color: #6c757d; transition: transform .2s; }
.dropdown-btn:hover i:first-child { color: #0d6efd; }
.dropdown-menu a i, .logout-btn i { color: rgb(16,32,88); width: 20px; font-size: 1.2rem; }
.dropdown-menu a:hover i, .logout-btn:hover i { color: #0d6efd; }
.logout-btn i { color: #dc3545; }
.logout-btn:hover i { color: #b02a37; }
.user-name { max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

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
}
.session-modal-body {
    font-size: 13px; font-weight: 500; color: #6b7280; line-height: 1.6; margin-bottom: 22px;
}
.session-countdown {
    font-size: 32px; font-weight: 800; color: #d97706; display: block; margin: 8px 0;
}
.session-modal-actions { display: flex; gap: 10px; }
.session-btn-stay {
    flex: 1; padding: 11px; border-radius: 10px;
    background: #1855e0; color: #fff; border: none; cursor: pointer;
    font-size: 13px; font-weight: 600; transition: background .13s;
}
.session-btn-stay:hover { background: #1344c2; }
.session-btn-logout {
    flex: 1; padding: 11px; border-radius: 10px;
    background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; cursor: pointer;
    font-size: 13px; font-weight: 600; transition: all .13s;
}
.session-btn-logout:hover { background: #fff0f0; border-color: #fbcfcf; color: #d92626; }

/* ══ RESPONSIVE ══ */
@media (max-width: 768px) {
    .navbar-menu {
        display: none; flex-direction: column; position: absolute;
        top: 60px; left: 0; right: 0; background: white;
        padding: 20px; gap: 8px;
        box-shadow: 0 20px 30px -10px rgba(0,0,0,.15);
        border-radius: 0 0 24px 24px; border-top: 1px solid #dee2e6;
        width: 100%; z-index: 999;
    }
    .navbar-menu.show { display: flex; }
    .navbar-menu a { width: 100%; padding: 14px 20px; border-radius: 12px; }
    .menu-toggle { display: block; }
    .user-dropdown {
        margin-left: 0; padding-left: 0; border-left: none;
        width: 100%; margin-top: 12px; padding-top: 12px;
        border-top: 1px solid #dee2e6; flex-direction: column; align-items: stretch;
    }
    .dropdown-btn {
        width: 100%; justify-content: space-between;
        background-color: #f8f9fa; border-radius: 12px; padding: 14px 20px;
    }
    .dropdown-menu {
        position: static; box-shadow: none; border-radius: 12px; margin-top: 8px;
        opacity: 1; transform: none; transition: none; border: 1px solid #dee2e6;
    }
}

@keyframes popIn { from{opacity:0;transform:scale(.92)} to{opacity:1;transform:scale(1)} }
</style>