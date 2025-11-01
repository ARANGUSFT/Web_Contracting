<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | @yield('title')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = {
            important: true,
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f7ff',
                            100: '#e0f0ff',
                            200: '#bae0ff',
                            300: '#7cc4ff',
                            400: '#36a2ff',
                            500: '#0d8aff',
                            600: '#0066cc',
                            700: '#0052a3',
                            800: '#003d7a',
                            900: '#002952',
                        },
                        dark: '#1a202c',
                        light: '#f8fafc',
                        sidebar: '#1e293b'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                        'card': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                    }
                }
            }
        }
    </script>
    
    <!-- Additional CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #0066cc;
            --primary-light: #e0f0ff;
            --sidebar-width: 260px;
            --header-height: 70px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles - Siempre expandido */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            z-index: 100;
        }
        
        .logo-container {
            height: var(--header-height);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0 20px;
        }
        
        /* Nuevo diseño del logo - más profesional */
        .logo-wrapper {
            display: flex;
            align-items: center;
            padding: 8px 0;
        }
        
        .logo-image-container {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }
        
        .logo-image-container:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }
        
        .logo-text-container {
            margin-left: 12px;
            overflow: hidden;
        }
        
        .company-name {
            font-size: 17px;
            font-weight: 700;
            color: white;
            line-height: 1.2;
            letter-spacing: -0.2px;
        }
        
        .panel-name {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 2px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        .nav-item {
            position: relative;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.2s ease;
        }
        
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--primary-color);
            border-radius: 0 4px 4px 0;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
        }
        
        .nav-link.active {
            color: white;
            font-weight: 500;
        }
        
        .nav-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
        }
        
        .nav-text {
            white-space: nowrap;
        }
        
        /* Main Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
        }
        
        /* Header Styles */
        .header {
            height: var(--header-height);
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            z-index: 99;
            position: sticky;
            top: 0;
        }
        
        .header-search {
            max-width: 400px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        /* Mobile Sidebar */
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            z-index: 1050;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .mobile-sidebar.open {
            transform: translateX(0);
        }
        
        /* Notification Styles */
        .notification-sidebar {
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            max-width: 380px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            transition: right 0.3s ease-in-out;
            z-index: 1050;
            display: flex;
            flex-direction: column;
        }

        .notification-sidebar.active {
            right: 0;
        }

        .notification-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background: linear-gradient(to bottom, var(--primary-color), #004d99);
            color: white;
        }

        .notification-content {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }

        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            background: white;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid var(--primary-color);
            transition: all 0.2s ease;
        }
        
        .notification-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .notification-item.unread {
            background: #f0f9ff;
            border-left-color: #ef4444;
        }

        .notification-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .notification-message {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .notification-time {
            color: #9ca3af;
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        /* Backdrop */
        .notification-backdrop,
        .mobile-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }

        /* Responsive Adjustments */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .notification-sidebar {
                width: 100%;
                max-width: 100%;
                right: -100%;
            }
        }
        
        @media (max-width: 768px) {
            .header-search {
                display: none;
            }
            
            .notification-sidebar {
                max-width: 100%;
            }
            
            .user-info-desktop {
                display: none;
            }
        }
        
        @media (max-width: 480px) {
            .mobile-sidebar {
                width: 100%;
            }
            
            .notification-header {
                padding: 1rem;
            }
            
            .notification-item {
                padding: 0.75rem;
            }
            
            .logo-text-container {
                margin-left: 10px;
            }
            
            .company-name {
                font-size: 16px;
            }
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Mejoras para contenido responsivo */
        .content-container {
            max-width: 100%;
            overflow-x: auto;
        }
        
        /* Mejora para evitar desbordamientos en móviles */
        img, table, iframe {
            max-width: 100%;
        }
        
        /* Estilos para el menú activo dinámico */
        .nav-item.active .nav-link {
            color: white;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Mobile backdrop -->
    <div id="mobile-backdrop" class="mobile-backdrop"></div>

    <!-- Sidebar for desktop -->
    <aside class="sidebar fixed top-0 left-0 h-screen flex flex-col hidden lg:flex">
        <!-- Logo - Diseño Mejorado -->
        <div class="logo-container flex items-center">
            <div class="logo-wrapper">
                <div class="logo-image-container">
                    <img src="{{ asset('img/dd.png') }}" alt="Contracting Alliance Inc. Logo" class="h-6 w-auto">
                </div>
                <div class="logo-text-container">
                    <div class="company-name">Contracting Alliance</div>
                    <div class="panel-name">ADMIN PANEL</div>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto py-4">
            <nav class="space-y-1 px-2">
                <div class="nav-item" data-route="superadmin.users.index">
                    <a href="{{ route('superadmin.users.index') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>
                
                <div class="nav-item" data-route="superadmin.crew.index">
                    <a href="{{ route('superadmin.crew.index') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-text">Crew</span>
                    </a>
                </div>

                <div class="nav-item" data-route="superadmin.photos.projects">
                    <a href="{{ route('superadmin.photos.projects') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <span class="nav-text">Photos</span>
                    </a>
                </div>

                <div class="nav-item" data-route="superadmin.chat.view">
                    <a href="{{ route('superadmin.chat.view') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <span class="nav-text">Chat</span>
                    </a>
                </div>

                <div class="nav-item" data-route="superadmin.subcontractors.insurances.index">
                    <a href="{{ route('superadmin.subcontractors.insurances.index') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span class="nav-text">Insurance</span>
                    </a>
                </div>
                
                <div class="nav-item" data-route="superadmin.invoices.index">
                    <a href="{{ route('superadmin.invoices.index') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <span class="nav-text">Invoices</span>
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- User Section -->
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center">
                <div class="user-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-white/70">Administrator</p>
                </div>
                <div class="ml-auto">
                    <form action="{{ route('superadmin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-white/70 hover:text-white transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile sidebar -->
    <aside id="mobile-sidebar" class="mobile-sidebar flex flex-col">
        <div class="logo-container flex items-center justify-between">
            <div class="logo-wrapper">
                <div class="logo-image-container">
                    <img src="{{ asset('img/dd.png') }}" alt="Contracting Alliance Inc. Logo" class="h-6 w-auto">
                </div>
                <div class="logo-text-container">
                    <div class="company-name">Contracting Alliance</div>
                </div>
            </div>
            <button id="close-mobile-sidebar" class="text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto py-4">
            <nav class="space-y-1 px-2">
                <a href="{{ route('superadmin.users.index') }}" class="nav-link" data-route="superadmin.users.index">
                    <div class="nav-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <span class="nav-text">Dashboard</span>
                </a>
                
                <a href="{{ route('superadmin.crew.index') }}" class="nav-link" data-route="superadmin.crew.index">
                    <div class="nav-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="nav-text">Crew</span>
                </a>

                <a href="{{ route('superadmin.photos.projects') }}" class="nav-link" data-route="superadmin.photos.projects">
                    <div class="nav-icon">
                        <i class="fas fa-images"></i>
                    </div>
                    <span class="nav-text">Photos</span>
                </a>

                <a href="{{ route('superadmin.chat.view') }}" class="nav-link" data-route="superadmin.chat.view">
                    <div class="nav-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <span class="nav-text">Chat</span>
                </a>

                <a href="{{ route('superadmin.subcontractors.insurances.index') }}" class="nav-link" data-route="superadmin.subcontractors.insurances.index">
                    <div class="nav-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span class="nav-text">Insurance</span>
                </a>
                
                <a href="{{ route('superadmin.invoices.index') }}" class="nav-link" data-route="superadmin.invoices.index">
                    <div class="nav-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <span class="nav-text">Invoices</span>
                </a>
            </nav>
        </div>
        
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center">
                <div class="user-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-white/70">Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content min-h-screen flex flex-col">
        <!-- Header -->
        <header class="header flex items-center justify-between px-4 lg:px-6">
            <div class="flex items-center">
                <button id="mobile-menu-toggle" class="lg:hidden text-gray-500 hover:text-primary-600 mr-3">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h1 class="text-xl font-semibold text-gray-800">@yield('title')</h1>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Search Bar -->
            
                
                <!-- Notification Bell -->
                <div class="relative">
                    <button id="notification-bell" class="relative text-gray-500 hover:text-primary-600 transition-colors">
                        <i class="fas fa-bell text-xl"></i>
                        <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                    </button>
                </div>
                
                <!-- User Menu for Desktop -->
                <div class="user-info-desktop hidden lg:flex items-center space-x-3">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                    <div class="user-avatar">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main content area -->
        <main class="flex-1 p-4 lg:p-6 content-container">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t py-4">
            <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-600">
                &copy; {{ date('Y') }} Contracting Alliance Inc. All rights reserved.
            </div>
        </footer>
    </div>

    <!-- Notification Backdrop -->
    <div id="notification-backdrop" class="notification-backdrop"></div>

    <!-- Notification Sidebar -->
    <div id="notification-sidebar" class="notification-sidebar">
        <div class="notification-header">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Notifications</h2>
                <button id="close-notifications" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="flex items-center mt-2 space-x-2 text-sm">
                <span class="bg-white/20 px-2 py-1 rounded">Unread: <span id="unread-count">3</span></span>
                <button id="mark-all-read" class="bg-white/20 hover:bg-white/30 px-2 py-1 rounded transition-colors">
                    Mark all read
                </button>
            </div>
        </div>
        
        <div class="notification-content">
            <!-- Sample notifications -->
            <div class="notification-item unread">
                <div class="notification-title">New Message Received</div>
                <div class="notification-message">You have a new message from John Doe regarding the project update.</div>
                <div class="notification-time">2 minutes ago</div>
            </div>
            
            <div class="notification-item">
                <div class="notification-title">Invoice Approved</div>
                <div class="notification-message">Invoice #INV-2024-001 has been approved and processed.</div>
                <div class="notification-time">1 hour ago</div>
            </div>
            
            <div class="notification-item unread">
                <div class="notification-title">System Update</div>
                <div class="notification-message">Scheduled maintenance will occur tonight at 2:00 AM.</div>
                <div class="notification-time">3 hours ago</div>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu functionality
        document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
            document.getElementById('mobile-sidebar').classList.add('open');
            document.getElementById('mobile-backdrop').style.display = 'block';
            document.body.style.overflow = 'hidden';
        });

        document.getElementById('close-mobile-sidebar').addEventListener('click', function() {
            document.getElementById('mobile-sidebar').classList.remove('open');
            document.getElementById('mobile-backdrop').style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        document.getElementById('mobile-backdrop').addEventListener('click', function() {
            document.getElementById('mobile-sidebar').classList.remove('open');
            this.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        // Notification functionality
        document.addEventListener('DOMContentLoaded', function() {
            const notificationBell = document.getElementById('notification-bell');
            const notificationSidebar = document.getElementById('notification-sidebar');
            const notificationBackdrop = document.getElementById('notification-backdrop');
            const closeNotifications = document.getElementById('close-notifications');
            const markAllReadBtn = document.getElementById('mark-all-read');
            const notificationBadge = document.getElementById('notification-badge');
            const unreadCountSpan = document.getElementById('unread-count');

            // Open notification sidebar
            notificationBell.addEventListener('click', function(e) {
                e.preventDefault();
                notificationSidebar.classList.add('active');
                notificationBackdrop.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });

            // Close notification sidebar
            function closeNotificationSidebar() {
                notificationSidebar.classList.remove('active');
                notificationBackdrop.style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            closeNotifications.addEventListener('click', closeNotificationSidebar);
            notificationBackdrop.addEventListener('click', closeNotificationSidebar);

            // Mark all as read functionality
            markAllReadBtn.addEventListener('click', function() {
                const unreadNotifications = document.querySelectorAll('.notification-item.unread');
                unreadNotifications.forEach(notification => {
                    notification.classList.remove('unread');
                });
                
                // Update badge count
                notificationBadge.classList.add('hidden');
                unreadCountSpan.textContent = '0';
            });

            // Update badge count based on unread notifications
            function updateBadgeCount() {
                const unreadCount = document.querySelectorAll('.notification-item.unread').length;
                if (unreadCount > 0) {
                    notificationBadge.textContent = unreadCount;
                    notificationBadge.classList.remove('hidden');
                    unreadCountSpan.textContent = unreadCount;
                } else {
                    notificationBadge.classList.add('hidden');
                }
            }

            // Initialize badge count
            updateBadgeCount();

            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeNotificationSidebar();
                    document.getElementById('mobile-sidebar').classList.remove('open');
                    document.getElementById('mobile-backdrop').style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Función para establecer el menú activo basado en la ruta actual
            function setActiveMenu() {
                // Obtener la ruta actual
                const currentPath = window.location.pathname;
                
                // Remover clase activa de todos los elementos del menú
                document.querySelectorAll('.nav-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                
                // Buscar el elemento del menú que coincide con la ruta actual
                let activeFound = false;
                
                // Primero intentamos coincidencia exacta con las rutas
                document.querySelectorAll('.nav-link').forEach(link => {
                    if (link.href === window.location.href) {
                        link.classList.add('active');
                        if (link.closest('.nav-item')) {
                            link.closest('.nav-item').classList.add('active');
                        }
                        activeFound = true;
                    }
                });
                
                // Si no encontramos coincidencia exacta, buscamos por nombre de ruta
                if (!activeFound) {
                    const routeName = getCurrentRouteName();
                    if (routeName) {
                        const menuItem = document.querySelector(`[data-route="${routeName}"]`);
                        if (menuItem) {
                            menuItem.classList.add('active');
                            if (menuItem.classList.contains('nav-link')) {
                                menuItem.classList.add('active');
                            } else {
                                const link = menuItem.querySelector('.nav-link');
                                if (link) {
                                    link.classList.add('active');
                                }
                            }
                        }
                    }
                }
                
                // Si aún no encontramos nada, marcamos Dashboard como activo por defecto
                if (!activeFound) {
                    const dashboardItem = document.querySelector('[data-route="superadmin.users.index"]');
                    if (dashboardItem) {
                        dashboardItem.classList.add('active');
                        const link = dashboardItem.querySelector('.nav-link');
                        if (link) {
                            link.classList.add('active');
                        }
                    }
                }
            }
            
            // Función auxiliar para obtener el nombre de la ruta actual
            function getCurrentRouteName() {
                // Esta función debería devolver el nombre de la ruta Laravel actual
                // En un entorno real, esto podría pasarse desde el backend
                // Por ahora, intentaremos inferirlo de la URL
                const path = window.location.pathname;
                
                // Mapeo de rutas basado en patrones comunes
                if (path.includes('/crew')) return 'superadmin.crew.index';
                if (path.includes('/photos')) return 'superadmin.photos.projects';
                if (path.includes('/chat')) return 'superadmin.chat.view';
                if (path.includes('/insurances')) return 'superadmin.subcontractors.insurances.index';
                if (path.includes('/invoices')) return 'superadmin.invoices.index';
                
                // Por defecto, asumimos que es el dashboard
                return 'superadmin.users.index';
            }
            
            // Establecer el menú activo al cargar la página
            setActiveMenu();
        });
    </script>
</body>
</html>