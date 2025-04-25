<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-2">
    <div class="container">
        <!-- 🔹 LOGO -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" height="36" class="me-2">
        </a>

        <!-- 🔹 Botón Responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- 🔹 Menú -->
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if(Auth::guard('web')->check()) 
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('leads.index') }}">
                            <i class="bi bi-kanban"></i> Project MG
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('leads.index') }}">
                            <i class="bi bi-building"></i> CRM
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('leads.create') }}">
                            <i class="bi bi-person-plus"></i> Leads
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('teams.index') }}">
                            <i class="bi bi-people"></i> Manage Team
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('leads.index') }}">
                            <i class="bi bi-shield-check"></i> Insurance
                        </a>
                    </li>
                @elseif(Auth::guard('team')->check()) 
                    @php
                        $teamUser = Auth::guard('team')->user();
                    @endphp

                    @if($teamUser->role === 'sales')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('seller.dashboard') }}">
                                <i class="bi bi-kanban"></i> CRM
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('seller.create') }}">
                                <i class="bi bi-person-plus"></i> Create Lead
                            </a>
                        </li>
                    @elseif($teamUser->role === 'guest')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('guest.dashboard') }}">
                                <i class="bi bi-house-door"></i> Guest Panel
                            </a>
                        </li>
                    @elseif($teamUser->role === 'manager')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('manager.dashboard') }}">
                                <i class="bi bi-bar-chart"></i> Manager Panel
                            </a>
                        </li>
                    @elseif($teamUser->role === 'crew')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('crew.dashboard') }}">
                                <i class="bi bi-tools"></i> Crew Area
                            </a>
                        </li>
                    @elseif($teamUser->role === 'project_manager')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('project.dashboard') }}">
                                <i class="bi bi-diagram-3"></i> Project Manager
                            </a>
                        </li>
                    @elseif($teamUser->role === 'company_admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-building"></i> Admin Panel
                            </a>
                        </li>
                    @endif
                @endif
            </ul>

            <!-- 🔹 User Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                    @if(Auth::guard('web')->check()) 
                        {{ Auth::guard('web')->user()->company_name }}
                    @elseif(Auth::guard('team')->check()) 
                        {{ Auth::guard('team')->user()->name }}
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item" href="{{
                            Auth::guard('web')->check() ? route('profile.edit') :
                            (Auth::guard('team')->user()->role === 'sales' ? route('seller.profile.edit') :
                            (Auth::guard('team')->user()->role === 'guest' ? route('guest.profile.edit') :
                            (Auth::guard('team')->user()->role === 'manager' ? route('manager.profile.edit') :
                            (Auth::guard('team')->user()->role === 'crew' ? route('crew.profile.edit') :
                            (Auth::guard('team')->user()->role === 'project_manager' ? route('project.profile.edit') :
                            (Auth::guard('team')->user()->role === 'company_admin' ? route('admin.profile.edit') : '#')))))) }}">
                            👤 Perfil
                        </a>
                    </li>
                    
                    <li>
                        <form method="POST" action="{{ Auth::guard('web')->check() ? route('logout') : route('team.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">🚪 Cerrar Sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
