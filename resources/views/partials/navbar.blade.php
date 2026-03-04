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
            $webUser = Auth::guard('web')->user();
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

            <!-- Dropdown de usuario con separador visual -->
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
                                'sales' => route('seller.profile.edit'),
                                'guest' => route('guest.profile.edit'),
                                'manager' => route('manager.profile.edit'),
                                'crew' => route('crew.profile.edit'),
                                'project_manager' => route('project.profile.edit'),
                                'company_admin' => route('admin.profile.edit'),
                                default => '#'
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

<!-- JavaScript para el toggle del menú y dropdown -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const navbarMenu = document.getElementById('navbarMenu');
    const dropdownBtn = document.getElementById('userDropdownBtn');
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

    // Cerrar menú móvil al hacer clic en un enlace
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
});
</script>

<!-- ============================================
     ESTILOS UNIFICADOS (fondo blanco, iconos azules)
     ============================================ -->
<style>
/* 🌟 NAVBAR GENERAL */
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
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* 📌 CONTENEDOR PRINCIPAL */
.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 90%;
    max-width: 1200px;
}

/* 🔹 LOGO */
.navbar-logo img {
    height: 22px;
    transition: transform 0.3s ease;
}

.navbar-logo img:hover {
    transform: scale(1.1);
}

/* 📌 MENÚ PRINCIPAL */
.navbar-menu {
    display: flex;
    align-items: center;
    gap: 30px; /* Más separación entre ítems */
    padding: 0;
    margin: 0;
    list-style: none;
}

.navbar-menu li {
    list-style: none;
}

.navbar-menu a {
    padding: 10px 18px;
    font-size: 14px;
    color: rgb(16, 32, 88);
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 6px;
    text-decoration: none;
    transition: background 0.2s ease, transform 0.2s ease;
}

.navbar-menu a:hover {
    background-color: rgba(0, 0, 0, 0.05);
    transform: translateY(-1px);
}

/* 🔹 DROPDOWN DE USUARIO */
.user-dropdown {
    position: relative;
    display: flex;
    align-items: center;
    /* Separación visual */
    margin-left: 20px;
    padding-left: 20px;
    border-left: 1px solid #dee2e6;
}

/* 🔻 Botón del Dropdown */
.dropdown-btn {
    background: none;
    border: none;
    color: rgb(16, 32, 88);
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 16px;
    border-radius: 40px;
    transition: background 0.2s;
}

.dropdown-btn:hover {
    background-color: #f0f2f5;
}

/* 📌 MENÚ RESPONSIVO */
.menu-toggle {
    display: none;
    background: none;
    border: none;
    color: rgb(16, 32, 88);
    font-size: 28px;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: background 0.2s;
}

.menu-toggle:hover {
    background-color: #f0f2f5;
}

/* 📌 ESTILO DEL DROPDOWN MENÚ */
.dropdown-menu {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: white;
    min-width: 220px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid #dee2e6;
    z-index: 1000;
    opacity: 0;
    transform: translateY(-8px);
    transition: opacity 0.2s ease, transform 0.2s ease;
    overflow: hidden;
    padding: 8px 0;
}

.dropdown-menu.show {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

/* 🔹 Opciones dentro del Dropdown */
.dropdown-menu a,
.logout-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #333;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: background 0.2s ease;
    width: 100%;
    text-align: left;
    border: none;
    background: none;
    cursor: pointer;
}

.dropdown-menu a:hover,
.logout-btn:hover {
    background: #f0f5ff;
}

/* 🚪 Botón de Logout */
.logout-btn {
    color: #dc3545;
}
.logout-btn:hover {
    background: #ffebee;
}

/* ============================================
     ICONOS AZULES (mismo color que el texto)
     ============================================ */
.navbar-menu a i {
    color: rgb(16, 32, 88);
    font-size: 1.2rem;
    transition: color 0.2s;
}

.navbar-menu a:hover i {
    color: #0d6efd; /* Un azul más brillante al hover */
}

.dropdown-btn i:first-child {
    color: rgb(16, 32, 88);
    font-size: 1.4rem;
}
.dropdown-btn i:last-child {
    color: #6c757d;
    transition: transform 0.2s;
}
.dropdown-btn:hover i:first-child {
    color: #0d6efd;
}

.dropdown-menu a i,
.logout-btn i {
    color: rgb(16, 32, 88);
    width: 20px;
    font-size: 1.2rem;
}
.dropdown-menu a:hover i,
.logout-btn:hover i {
    color: #0d6efd;
}
.logout-btn i {
    color: #dc3545;
}
.logout-btn:hover i {
    color: #b02a37;
}

/* Límite de ancho para el nombre */
.user-name {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* ============================================
     RESPONSIVE
     ============================================ */
@media (max-width: 768px) {
    .navbar-menu {
        display: none;
        flex-direction: column;
        position: absolute;
        top: 60px;
        left: 0;
        right: 0;
        background: white;
        padding: 20px;
        gap: 8px;
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.15);
        border-radius: 0 0 24px 24px;
        border-top: 1px solid #dee2e6;
        width: 100%;
        z-index: 999;
    }

    .navbar-menu.show {
        display: flex;
    }

    .navbar-menu a {
        width: 100%;
        padding: 14px 20px;
        border-radius: 12px;
    }

    .menu-toggle {
        display: block;
    }

    .user-dropdown {
        margin-left: 0;
        padding-left: 0;
        border-left: none;
        width: 100%;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #dee2e6;
        flex-direction: column;
        align-items: stretch;
    }

    .dropdown-btn {
        width: 100%;
        justify-content: space-between;
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 14px 20px;
    }

    .dropdown-menu {
        position: static;
        box-shadow: none;
        border-radius: 12px;
        margin-top: 8px;
        opacity: 1;
        transform: none;
        transition: none;
        border: 1px solid #dee2e6;
    }
}
</style>