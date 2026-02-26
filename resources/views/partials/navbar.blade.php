<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-2">
    <div class="container">
        {{-- 🔹 LOGO --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" height="36" class="me-2">
        </a>

        {{-- 🔹 Botón Responsive --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        @php
            $webUser = Auth::guard('web')->user();
            $teamUser = Auth::guard('team')->user();
        @endphp

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- 🔹 USUARIOS WEB --}}
                @if($webUser)

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('calendar.view') }}">
                            <i class="bi bi-kanban"></i> Project Manager
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
                        <a class="nav-link" href="{{ $webUser->is_admin ? route('superadmin.chat.view') : route('user.chat.view') }}">
                            <i class="bi bi-chat-dots"></i> Chat
                        </a>
                    </li>

                {{-- 🔹 USUARIOS TEAM --}}
                @elseif($teamUser)

                    @switch($teamUser->role)

                        @case('sales')
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
                            @break

                        @case('guest')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('guest.dashboard') }}">
                                    <i class="bi bi-house-door"></i> Guest Panel
                                </a>
                            </li>
                            @break

                        @case('manager')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('manager.dashboard') }}">
                                    <i class="bi bi-bar-chart"></i> Manager Panel
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('manager.calendar') }}">
                                    <i class="bi bi-calendar-event"></i> My Schedule
                                </a>
                            </li>
                            @break

                        @case('crew')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('crew.dashboard') }}">
                                    <i class="bi bi-tools"></i> Crew Area
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('crew.calendar') }}">
                                    <i class="bi bi-calendar-event"></i> My Schedule
                                </a>
                            </li>
                            @break

                        @case('project_manager')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('project.dashboard') }}">
                                    <i class="bi bi-diagram-3"></i> Project Manager
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('project.calendar') }}">
                                    <i class="bi bi-calendar-event"></i> My Schedule
                                </a>
                            </li>
                            @break

                        @case('company_admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-building"></i> Admin Panel
                                </a>
                            </li>
                            @break

                    @endswitch

                @endif

            </ul>

            {{-- 🔹 User Dropdown --}}
            @if($webUser || $teamUser)
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        {{ $webUser ? $webUser->company_name : $teamUser->name }}
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            @if($webUser)
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    - Profile
                                </a>
                            @elseif($teamUser)
                                @switch($teamUser->role)
                                    @case('sales')
                                        <a class="dropdown-item" href="{{ route('seller.profile.edit') }}">- Profile</a>
                                        @break
                                    @case('guest')
                                        <a class="dropdown-item" href="{{ route('guest.profile.edit') }}">- Profile</a>
                                        @break
                                    @case('manager')
                                        <a class="dropdown-item" href="{{ route('manager.profile.edit') }}">- Profile</a>
                                        @break
                                    @case('crew')
                                        <a class="dropdown-item" href="{{ route('crew.profile.edit') }}">- Profile</a>
                                        @break
                                    @case('project_manager')
                                        <a class="dropdown-item" href="{{ route('project.profile.edit') }}">- Profile</a>
                                        @break
                                    @case('company_admin')
                                        <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">- Profile</a>
                                        @break
                                @endswitch
                            @endif
                        </li>

                        <li>
                            <form method="POST" action="{{ $webUser ? route('logout') : route('team.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    - Log Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endif

        </div>
    </div>
</nav>