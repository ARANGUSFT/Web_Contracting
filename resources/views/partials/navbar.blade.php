<nav class="navbar">
    <div class="container">
        <!-- 🔹 LOGO -->
        <div class="navbar-logo">
            <img src="{{ asset('img/logo.png') }}" alt="Logo">
        </div>

        <!-- 🔹 Botón Menú Móvil -->
        <button class="menu-toggle" onclick="toggleMobileMenu()">☰</button>

        <!-- 🔹 Menú de Navegación -->
        <ul class="navbar-menu" id="navbarMenu">
            @if(Auth::guard('web')->check()) 
                <!-- Menú para Administradores -->
                <li><a href="{{ route('leads.index') }}"><span class="icon">🛠</span><span class="text">Project MG</span></a></li>
                <li><a href="{{ route('leads.index') }}"><span class="icon">🏢</span><span class="text">CRM</span></a></li>
                <li><a href="{{ route('leads.create') }}"><span class="icon">📋</span><span class="text">Leads</span></a></li>
                <li><a href="{{ route('leads.index') }}"><span class="icon">📊</span><span class="text">Payment Report</span></a></li>
                <li><a href="{{ route('teams.index') }}"><span class="icon">👥</span><span class="text">Manage Team</span></a></li>
                <li><a href="{{ route('leads.index') }}"><span class="icon">🛡️</span><span class="text">Insurance</span></a></li>
                @elseif(Auth::guard('team')->check()) 
                <!-- Menú para Vendedores -->
                <li><a href="{{ route('seller.dashboard') }}"><span class="icon">📌</span><span class="text">Mis Leads</span></a></li>
                <li><a href="{{ route('seller.dashboard') }}"><span class="icon">📌</span><span class="text">Create Lead</span></a></li>

            @endif
        </ul>

        <!-- 🔹 Dropdown de Usuario -->
        <div class="user-dropdown">
            <button class="dropdown-btn" onclick="toggleDropdown()">
                <span>
                    @if(Auth::guard('web')->check()) 
                        {{ Auth::guard('web')->user()->name }}
                    @elseif(Auth::guard('team')->check()) 
                        {{ Auth::guard('team')->user()->name }}
                    @endif
                </span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <div class="dropdown-menu" id="userDropdown">
                <a href="{{ route('profile.edit') }}">👤 Perfil</a>
                <form method="POST" action="{{ Auth::guard('web')->check() ? route('logout') : route('team.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">🚪 Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </div>
</nav>
