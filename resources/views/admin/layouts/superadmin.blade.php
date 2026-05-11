<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') · Contracting Alliance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --ink:    #0f1117; --ink2:  #3c4353; --ink3:  #8c95a6;
        --bg:     #f4f5f8; --surf:  #ffffff;
        --bd:     #e4e7ed; --bd2:   #eef0f4;
        --blue:   #1855e0; --blt:   #eef2ff; --bbd:   #c7d4fb;
        --grn:    #0d9e6a; --glt:   #edfaf4; --gbd:   #9fe6c8;
        --red:    #d92626; --rlt:   #fff0f0; --rbd:   #fbcfcf;
        --amb:    #d97706; --alt:   #fffbeb; --abd:   #fde68a;
        --sidebar-w: 240px; --header-h: 62px;
        --r: 8px; --rlg: 13px; --rxl: 18px;
    }

    html, body {
        height: 100%; font-family: 'Montserrat', sans-serif;
        background: var(--bg); color: var(--ink);
        font-size: 14px; line-height: 1.5; overflow-x: hidden;
    }
    a { color: inherit; text-decoration: none; }

    .layout { display: flex; min-height: 100vh; }

    /* ══ SIDEBAR ══ */
    .sidebar {
        width: var(--sidebar-w); background: var(--ink);
        display: flex; flex-direction: column;
        position: fixed; top: 0; left: 0; height: 100vh;
        z-index: 200; transition: transform .25s ease; overflow: hidden;
    }
    .sidebar::before {
        content: ''; position: absolute; inset: 0; pointer-events: none;
        background-image:
            linear-gradient(rgba(255,255,255,.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.02) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .sidebar::after {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
        background: linear-gradient(180deg, #4f80ff 0%, var(--blue) 50%, transparent 100%);
    }

    .sb-logo {
        position: relative; height: var(--header-h);
        display: flex; align-items: center; padding: 0 18px;
        border-bottom: 1px solid rgba(255,255,255,.07); flex-shrink: 0;
    }
    .sb-logo-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; overflow: hidden;
    }
    .sb-logo-icon img { height: 20px; width: auto; object-fit: contain; }
    .sb-logo-text { margin-left: 11px; }
    .sb-logo-name { font-size: 14px; font-weight: 800; color: #fff; letter-spacing: -.3px; line-height: 1.1; }
    .sb-logo-sub  { font-size: 9.5px; font-weight: 700; color: rgba(255,255,255,.3); text-transform: uppercase; letter-spacing: 1px; margin-top: 2px; }

    .sb-nav { flex: 1; overflow-y: auto; padding: 12px 10px; scrollbar-width: none; }
    .sb-nav::-webkit-scrollbar { display: none; }
    .sb-section-label {
        font-size: 9.5px; font-weight: 800; color: rgba(255,255,255,.2);
        text-transform: uppercase; letter-spacing: 1.2px; padding: 12px 10px 6px;
    }
    .sb-item { position: relative; border-radius: var(--r); margin-bottom: 2px; }
    .sb-item.active { background: rgba(255,255,255,.08); }
    .sb-item.active::before {
        content: ''; position: absolute; left: 0; top: 6px; bottom: 6px; width: 3px;
        background: var(--blue); border-radius: 0 3px 3px 0;
    }
    .sb-link {
        display: flex; align-items: center; gap: 10px; padding: 10px 12px;
        color: rgba(255,255,255,.55); border-radius: var(--r);
        font-size: 12.5px; font-weight: 600;
        transition: color .15s, background .15s; cursor: pointer; white-space: nowrap;
    }
    .sb-link:hover { color: rgba(255,255,255,.9); background: rgba(255,255,255,.05); }
    .sb-item.active .sb-link { color: #fff; }
    .sb-link-icon {
        width: 18px; height: 18px; display: flex; align-items: center; justify-content: center;
        font-size: 13px; flex-shrink: 0; color: rgba(255,255,255,.35); transition: color .15s;
    }
    .sb-item.active .sb-link-icon, .sb-link:hover .sb-link-icon { color: rgba(255,255,255,.75); }
    .sb-item.active .sb-link-icon { color: #8aadff; }
    .sb-sub { display: none; padding: 3px 0 4px 30px; }
    .sb-sub.open { display: block; }
    .sb-sub-link {
        display: flex; align-items: center; gap: 7px; padding: 7px 10px;
        color: rgba(255,255,255,.45); font-size: 12px; font-weight: 600;
        border-radius: 6px; transition: color .13s, background .13s;
    }
    .sb-sub-link:hover { color: rgba(255,255,255,.85); background: rgba(255,255,255,.05); }
    .sb-sub-link.active { color: #8aadff; }
    .sb-chevron { margin-left: auto; font-size: 9px; transition: transform .2s; opacity: .4; }
    .sb-chevron.open { transform: rotate(180deg); }

    .sb-user {
        position: relative; padding: 14px 16px;
        border-top: 1px solid rgba(255,255,255,.07);
        display: flex; align-items: center; gap: 10px; flex-shrink: 0;
    }
    .sb-user-av {
        width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
        background: var(--blue); display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 800; color: #fff;
    }
    .sb-user-name { font-size: 12.5px; font-weight: 700; color: #fff; line-height: 1.2; }
    .sb-user-role { font-size: 10.5px; color: rgba(255,255,255,.35); font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
    .sb-logout {
        margin-left: auto; background: none; border: none; cursor: pointer;
        color: rgba(255,255,255,.3); font-size: 14px; transition: color .13s; padding: 4px;
    }
    .sb-logout:hover { color: var(--red); }

    /* ══ MAIN ══ */
    .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; min-width: 0; }

    .header {
        height: var(--header-h); background: var(--surf); border-bottom: 1px solid var(--bd);
        display: flex; align-items: center; padding: 0 24px;
        position: sticky; top: 0; z-index: 100; gap: 14px;
    }
    .header-menu-btn {
        display: none; background: none; border: none; cursor: pointer;
        color: var(--ink3); font-size: 18px; padding: 4px; transition: color .13s;
    }
    .header-menu-btn:hover { color: var(--ink); }
    .header-title { font-size: 15px; font-weight: 800; color: var(--ink); letter-spacing: -.3px; white-space: nowrap; }
    .header-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
    .header-actions { display: flex; align-items: center; gap: 8px; }
    .header-bell {
        position: relative; width: 36px; height: 36px; border-radius: 9px;
        background: none; border: 1px solid var(--bd);
        display: flex; align-items: center; justify-content: center;
        color: var(--ink3); font-size: 14px; cursor: pointer; transition: all .13s;
    }
    .header-bell:hover { background: var(--bg); color: var(--ink); }
    .header-bell-badge {
        position: absolute; top: -4px; right: -4px; width: 18px; height: 18px; border-radius: 50%;
        background: var(--red); color: #fff; font-size: 9px; font-weight: 800;
        display: flex; align-items: center; justify-content: center; border: 2px solid var(--surf);
    }
    .header-user {
        display: flex; align-items: center; gap: 9px; padding: 6px 12px 6px 6px;
        border: 1px solid var(--bd); border-radius: var(--rlg); background: var(--surf); cursor: default;
    }
    .header-user-av {
        width: 28px; height: 28px; border-radius: 7px; background: var(--blue);
        display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; color: #fff;
    }
    .header-user-name { font-size: 12.5px; font-weight: 700; color: var(--ink); }
    .header-user-role { font-size: 10px; font-weight: 600; color: var(--ink3); text-transform: uppercase; letter-spacing: .4px; }

    .page-content { flex: 1; padding: 0; overflow-x: hidden; }

    .footer {
        padding: 14px 24px; border-top: 1px solid var(--bd2); background: var(--surf);
        display: flex; align-items: center; justify-content: center;
        font-size: 11.5px; font-weight: 600; color: var(--ink3);
    }

    /* ══ NOTIFICATION SIDEBAR ══ */
    .notif-sidebar {
        position: fixed; top: 0; right: -400px; width: 360px; height: 100vh;
        background: var(--surf); border-left: 1px solid var(--bd); z-index: 300;
        display: flex; flex-direction: column; transition: right .25s ease;
        box-shadow: -4px 0 24px rgba(0,0,0,.08);
    }
    .notif-sidebar.open { right: 0; }
    .notif-head {
        padding: 18px 20px; background: var(--ink);
        display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
    }
    .notif-head-title { font-size: 14px; font-weight: 800; color: #fff; }
    .notif-head-meta { display: flex; align-items: center; gap: 8px; margin-top: 6px; }
    .notif-head-count {
        font-size: 11px; font-weight: 700; background: rgba(255,255,255,.1); color: rgba(255,255,255,.7);
        padding: 2px 9px; border-radius: 9999px;
    }
    .notif-mark-btn {
        font-size: 11px; font-weight: 700; cursor: pointer; background: rgba(255,255,255,.08);
        color: rgba(255,255,255,.6); border: 1px solid rgba(255,255,255,.12);
        padding: 2px 9px; border-radius: 9999px; transition: all .13s;
    }
    .notif-mark-btn:hover { background: rgba(255,255,255,.14); color: #fff; }
    .notif-close-btn {
        background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
        color: rgba(255,255,255,.5); width: 30px; height: 30px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: 13px; transition: all .13s;
    }
    .notif-close-btn:hover { background: rgba(255,255,255,.14); color: #fff; }
    .notif-body { flex: 1; overflow-y: auto; padding: 14px; }
    .notif-item { padding: 12px 14px; border-radius: var(--rlg); border: 1px solid var(--bd2); margin-bottom: 8px; transition: border-color .13s; }
    .notif-item.unread { background: var(--blt); border-color: var(--bbd); border-left: 3px solid var(--blue); }
    .notif-item:hover { border-color: var(--blue); }
    .notif-item-title { font-size: 12.5px; font-weight: 700; color: var(--ink); margin-bottom: 3px; }
    .notif-item-msg   { font-size: 12px; font-weight: 500; color: var(--ink3); line-height: 1.4; }
    .notif-item-time  { font-size: 11px; font-weight: 600; color: var(--ink3); margin-top: 6px; }

    /* ══ BACKDROP ══ */
    .backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 199; backdrop-filter: blur(2px); }
    .backdrop.show { display: block; }

    /* ══ SCROLLBAR ══ */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: var(--bg); }
    ::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }
    ::-webkit-scrollbar-thumb:hover { background: #adb2be; }

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
        box-shadow: 0 20px 60px rgba(0,0,0,.2); animation: popIn .25s ease;
    }
    .session-modal-icon {
        width: 58px; height: 58px; border-radius: 50%; margin: 0 auto 18px;
        background: var(--alt); border: 2px solid var(--abd);
        display: flex; align-items: center; justify-content: center; font-size: 24px;
    }
    .session-modal-title { font-size: 17px; font-weight: 800; color: var(--ink); margin-bottom: 8px; }
    .session-modal-body  { font-size: 13px; font-weight: 500; color: var(--ink3); line-height: 1.6; margin-bottom: 22px; }
    .session-countdown   { font-size: 32px; font-weight: 800; color: var(--amb); display: block; margin: 8px 0; }
    .session-modal-actions { display: flex; gap: 10px; }
    .session-btn-stay {
        flex: 1; padding: 11px; border-radius: 10px;
        background: var(--blue); color: #fff; border: none; cursor: pointer;
        font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700; transition: background .13s;
    }
    .session-btn-stay:hover { background: #1344c2; }
    .session-btn-logout {
        flex: 1; padding: 11px; border-radius: 10px;
        background: var(--bg); color: var(--ink2); border: 1px solid var(--bd); cursor: pointer;
        font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700; transition: all .13s;
    }
    .session-btn-logout:hover { background: var(--rlt); border-color: var(--rbd); color: var(--red); }

    /* ══ SWAL MONTSERRAT ══ */
    .swal-montserrat { font-family: 'Montserrat', sans-serif !important; }
    .swal-montserrat .swal2-title { font-size: 18px !important; font-weight: 800 !important; }
    .swal-montserrat .swal2-html-container { font-size: 13px !important; color: #8c95a6 !important; }
    .swal-montserrat .swal2-confirm,
    .swal-montserrat .swal2-cancel {
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 700 !important; font-size: 13px !important;
        border-radius: 10px !important; padding: 10px 20px !important;
    }

    /* ══ RESPONSIVE ══ */
    @media (max-width: 1024px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.open { transform: translateX(0); }
        .main { margin-left: 0; }
        .header-menu-btn { display: flex; }
        .header-user-role { display: none; }
    }
    @media (max-width: 640px) {
        .header-user .header-user-name { display: none; }
        .notif-sidebar { width: 100%; right: -100%; }
    }

    @keyframes popIn   { from{opacity:0;transform:scale(.92)} to{opacity:1;transform:scale(1)} }
    @keyframes slideIn { from{opacity:0;transform:translateX(20px)} to{opacity:1;transform:none} }
    </style>
</head>

<body>
<div class="layout">

    {{-- ══ SIDEBAR ══ --}}
    <aside class="sidebar" id="sidebar">

       <a href="{{ route('superadmin.users.index') }}" class="sb-logo">
            <div class="sb-logo-icon">
                <img src="{{ asset('img/dd.png') }}" alt="Logo">
            </div>
            <div class="sb-logo-text">
                <div class="sb-logo-name">Contracting Alliance</div>
                <div class="sb-logo-sub">Admin Panel</div>
            </div>
        </a>

        <nav class="sb-nav">

          
            <div class="sb-item {{ request()->routeIs('superadmin.calendar.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.calendar.index') }}" class="sb-link">
                    <span class="sb-link-icon"><i class="fas fa-calendar-days"></i></span> Calendar
                </a>
            </div>
            <div class="sb-item {{ request()->routeIs('superadmin.crew.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.crew.index') }}" class="sb-link">
                    <span class="sb-link-icon"><i class="fas fa-users"></i></span> Crew
                </a>
            </div>
            <div class="sb-item {{ request()->routeIs('superadmin.photos.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.photos.projects') }}" class="sb-link">
                    <span class="sb-link-icon"><i class="fas fa-images"></i></span> Photos
                </a>
            </div>
            <div class="sb-item {{ request()->routeIs('superadmin.chat.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.chat.view') }}" class="sb-link">
                    <span class="sb-link-icon"><i class="fas fa-comments"></i></span> Chat
                </a>
            </div>
            <div class="sb-item {{ request()->routeIs('superadmin.subcontractors.insurances.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.subcontractors.insurances.index') }}" class="sb-link">
                    <span class="sb-link-icon"><i class="fas fa-shield-alt"></i></span> Insurance
                </a>
            </div>

            <div class="sb-section-label">Configuration</div>

            <div class="sb-item {{ request()->routeIs('superadmin.items.*','superadmin.item-categories.*') ? 'active' : '' }}" id="items-parent">
                <div class="sb-link" onclick="toggleSub('items-sub','items-chevron')">
                    <span class="sb-link-icon"><i class="fas fa-boxes"></i></span>
                    Items
                    <i class="fas fa-chevron-down sb-chevron {{ request()->routeIs('superadmin.items.*','superadmin.item-categories.*') ? 'open' : '' }}" id="items-chevron"></i>
                </div>
                <div class="sb-sub {{ request()->routeIs('superadmin.items.*','superadmin.item-categories.*') ? 'open' : '' }}" id="items-sub">
                    <a href="{{ route('superadmin.items.index') }}" class="sb-sub-link {{ request()->routeIs('superadmin.items.*') ? 'active' : '' }}">
                        <i class="fas fa-list-ul" style="font-size:10px"></i> All Items
                    </a>
                    <a href="{{ route('superadmin.item-categories.index') }}" class="sb-sub-link {{ request()->routeIs('superadmin.item-categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags" style="font-size:10px"></i> Categories
                    </a>
                </div>
            </div>

            <div class="sb-item {{ request()->routeIs('superadmin.locations.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.locations.index') }}" class="sb-link">
                    <span class="sb-link-icon"><i class="fas fa-map-marker-alt"></i></span> Company Locations
                </a>
            </div>

            <div class="sb-section-label">Operations</div>

            <div class="sb-item {{ request()->routeIs('superadmin.invoices.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.invoices.index') }}" class="sb-link">
                    <span class="sb-link-icon"><i class="fas fa-file-invoice-dollar"></i></span> Invoices
                </a>
            </div>

           <div class="sb-item {{ request()->routeIs('superadmin.weekly-accounting.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.weekly-accounting.index') }}" class="sb-link">
                    <span class="sb-link-icon"><i class="fas fa-sack-dollar"></i></span> Weekly Accounting
                </a>
            </div>

            <div class="sb-section-label">People</div>

            <div class="sb-item {{ request()->routeIs('superadmin.users.contractors','superadmin.contractors.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.users.contractors') }}" class="sb-link">
                    <span class="sb-link-icon"><i class="fas fa-hard-hat"></i></span> Contractors
                </a>
            </div>
            <div class="sb-item {{ request()->routeIs('superadmin.subcontractors.*') && !request()->routeIs('superadmin.subcontractors.insurances.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.subcontractors.index') }}" class="sb-link">
                    <span class="sb-link-icon"><i class="fas fa-people-carry-box"></i></span> Crew Managers
                </a>
            </div>
            <div class="sb-item {{ request()->routeIs('superadmin.users.pending') ? 'active' : '' }}">
                <a href="{{ route('superadmin.users.pending') }}" class="sb-link">
                    <span class="sb-link-icon"><i class="fas fa-user-clock"></i></span>
                    Pending Approval
                    @php $pending = \App\Models\User::where('is_admin',false)->whereNull('approved_at')->count(); @endphp
                    @if($pending > 0)
                    <span style="margin-left:auto;background:var(--red);color:#fff;font-size:9.5px;font-weight:800;padding:2px 7px;border-radius:9999px;min-width:20px;text-align:center">
                        {{ $pending }}
                    </span>
                    @endif
                </a>
            </div>

        </nav>

        <div class="sb-user">
            <div class="sb-user-av">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            <div>
                <div class="sb-user-name">{{ auth()->user()->name }}</div>
                <div class="sb-user-role">Administrator</div>
            </div>
            <form action="{{ route('superadmin.logout') }}" method="POST" id="logoutForm" style="margin-left:auto">
                @csrf
                <button type="button" class="sb-logout" id="logoutBtn" title="Sign out">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>

    </aside>

    {{-- ══ MAIN ══ --}}
    <div class="main">

        <header class="header">
            <button class="header-menu-btn" id="menu-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <span class="header-title">@yield('title')</span>

            @hasSection('actions')
            <div style="display:flex;align-items:center;gap:8px">@yield('actions')</div>
            @endif

            <div class="header-right">
                <div class="header-actions">
                    <button class="header-bell" id="bell-btn" onclick="toggleNotif()">
                        <i class="fas fa-bell"></i>
                        <span class="header-bell-badge" id="bell-badge">3</span>
                    </button>
                </div>
                <div class="header-user">
                    <div class="header-user-av">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                    <div>
                        <div class="header-user-name">{{ auth()->user()->name }}</div>
                        <div class="header-user-role">Admin</div>
                    </div>
                </div>
            </div>
        </header>

        <main class="page-content">@yield('content')</main>

        <footer class="footer">
            &copy; {{ date('Y') }} Contracting Alliance Inc. · All rights reserved.
        </footer>

    </div>

</div>

{{-- ══ NOTIFICATION SIDEBAR ══ --}}
<div class="notif-sidebar" id="notif-sidebar">
    <div class="notif-head">
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between">
                <div class="notif-head-title">Notifications</div>
                <button class="notif-close-btn" onclick="closeNotif()"><i class="fas fa-times"></i></button>
            </div>
            <div class="notif-head-meta">
                <span class="notif-head-count">Unread: <span id="unread-count">3</span></span>
                <button class="notif-mark-btn" id="mark-all-btn">Mark all read</button>
            </div>
        </div>
    </div>
    <div class="notif-body" id="notif-body">
        <div class="notif-item unread">
            <div class="notif-item-title">New Message</div>
            <div class="notif-item-msg">You have a new message regarding a project update.</div>
            <div class="notif-item-time">2 minutes ago</div>
        </div>
        <div class="notif-item">
            <div class="notif-item-title">Invoice Approved</div>
            <div class="notif-item-msg">Invoice #INV-2024-001 has been approved and processed.</div>
            <div class="notif-item-time">1 hour ago</div>
        </div>
        <div class="notif-item unread">
            <div class="notif-item-title">System Update</div>
            <div class="notif-item-msg">Scheduled maintenance tonight at 2:00 AM.</div>
            <div class="notif-item-time">3 hours ago</div>
        </div>
    </div>
</div>

{{-- Backdrop --}}
<div class="backdrop" id="backdrop" onclick="closeAll()"></div>

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
                <i class="fas fa-check" style="font-size:11px;margin-right:5px"></i> Stay logged in
            </button>
            <button class="session-btn-logout" onclick="logoutNow()">
                <i class="fas fa-sign-out-alt" style="font-size:11px;margin-right:5px"></i> Sign out
            </button>
        </div>
    </div>
</div>

<form id="sessionLogoutForm" action="{{ route('superadmin.logout') }}" method="POST" style="display:none">
    @csrf
</form>

<script>
/* ── SIDEBAR ── */
function toggleSidebar() {
    const sb = document.getElementById('sidebar'), bd = document.getElementById('backdrop');
    const open = sb.classList.contains('open');
    sb.classList.toggle('open', !open); bd.classList.toggle('show', !open);
}

/* ── NOTIFICATIONS ── */
function toggleNotif() {
    const ns = document.getElementById('notif-sidebar'), bd = document.getElementById('backdrop');
    const open = ns.classList.contains('open');
    ns.classList.toggle('open', !open); bd.classList.toggle('show', !open);
}
function closeNotif() {
    document.getElementById('notif-sidebar').classList.remove('open');
    document.getElementById('backdrop').classList.remove('show');
}
function closeAll() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('notif-sidebar').classList.remove('open');
    document.getElementById('backdrop').classList.remove('show');
}

document.getElementById('mark-all-btn').addEventListener('click', function() {
    document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
    document.getElementById('bell-badge').style.display = 'none';
    document.getElementById('unread-count').textContent = '0';
});

function toggleSub(subId, chevronId) {
    document.getElementById(subId).classList.toggle('open');
    document.getElementById(chevronId).classList.toggle('open');
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAll(); });

(function() {
    const count = document.querySelectorAll('.notif-item.unread').length;
    const badge = document.getElementById('bell-badge');
    if (count === 0) badge.style.display = 'none';
    else badge.textContent = count;
})();

/* ── LOGOUT CONFIRMATION ── */
document.getElementById('logoutBtn').addEventListener('click', function() {
    Swal.fire({
        title: 'Sign out?',
        text: 'You will be redirected to the login page.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d92626',
        cancelButtonColor: '#3c4353',
        confirmButtonText: '<i class="fas fa-sign-out-alt" style="margin-right:6px;font-size:11px"></i> Yes, sign out',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass: { popup: 'swal-montserrat' }
    }).then(result => {
        if (result.isConfirmed) document.getElementById('logoutForm').submit();
    });
});

/* ── SESSION TIMEOUT ── */
(function() {
    const IDLE_MINUTES   = 59;
    const COUNTDOWN_SECS = 60;

    let idleTimer, countdownTimer, secondsLeft;
    const modal   = document.getElementById('sessionModal');
    const countEl = document.getElementById('sessionCountdown');

    function resetIdle() {
        clearTimeout(idleTimer);
        idleTimer = setTimeout(showWarning, IDLE_MINUTES * 60 * 1000);
    }
    function showWarning() {
        secondsLeft = COUNTDOWN_SECS;
        countEl.textContent = secondsLeft;
        modal.classList.add('show');
        countdownTimer = setInterval(function() {
            secondsLeft--;
            countEl.textContent = secondsLeft;
            if (secondsLeft <= 0) { clearInterval(countdownTimer); logoutNow(); }
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
    ['mousemove','keydown','click','scroll','touchstart'].forEach(evt =>
        document.addEventListener(evt, resetIdle, { passive: true }));
    resetIdle();
})();
</script>

</body>
</html>