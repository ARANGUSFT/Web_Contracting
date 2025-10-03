<!DOCTYPE html>
<html lang="en">
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
                        primary: '#003366',
                        'primary-light': '#1a4d80',
                        'primary-dark': '#002244',
                        dark: '#1a202c',
                        light: '#f7fafc',
                        sidebar: '#001a33'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Additional CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    

</head>
<body class="bg-gray-50 min-h-screen flex font-sans">
    <!-- Sidebar -->
    <div class="hidden md:flex md:flex-shrink-0">
        <div class="flex flex-col w-64 bg-gradient-to-b from-primary to-primary-dark border-r border-primary-dark">
            <!-- Logo Section -->
            <div class="flex flex-col items-center justify-center h-40 px-4 bg-primary-dark/30 py-6">
                <div class="h-20 w-20 bg-white/20 rounded-2xl flex items-center justify-center p-3 backdrop-blur-sm mb-3">
                    <img src="{{ asset('img/dd.png') }}" alt="Contracting Alliance Inc. Logo">
                </div>
                <span class="text-lg font-bold text-white text-center">Contracting Alliance Inc</span>
                <span class="text-xs text-white/70 mt-1">Administration Panel</span>
            </div>
            
            <!-- Navigation -->
            <div class="flex-1 overflow-y-auto py-4">
                <nav class="px-2 space-y-1">
                    <a href="{{ route('superadmin.users.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                        <i class="fas fa-tachometer-alt mr-3 text-gray-300 group-hover:text-white"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('superadmin.crew.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                        <i class="fas fa-users mr-3 text-gray-300 group-hover:text-white"></i>
                        Crew
                    </a>

                    <a href="{{ route('superadmin.photos.projects') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                        <i class="fas fa-images mr-3 text-gray-300 group-hover:text-white"></i>
                        Photos
                    </a>

                    <a href="{{ route('superadmin.chat.view') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                        <i class="fas fa-comments mr-3 text-gray-300 group-hover:text-white"></i>
                        Chat
                    </a>

                    <a href="{{ route('superadmin.subcontractors.insurances.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                        <i class="fas fa-shield-alt mr-3 text-gray-300 group-hover:text-white"></i>
                        Insurance
                    </a>
                    
                    <a href="{{ route('superadmin.invoices.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                        <i class="fas fa-file-invoice-dollar mr-3 text-gray-300 group-hover:text-white"></i>
                        Invoices
                    </a>
                </nav>
            </div>
            
            <!-- User Section -->
            <div class="p-4 border-t border-white/10">
                <div class="relative">
                    <button id="sidebar-user-menu-button" class="flex items-center w-full text-left focus:outline-none group">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center text-white group-hover:bg-white/20 transition-colors">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs font-medium text-gray-300">Administrator</p>
                        </div>
                        <i class="fas fa-chevron-down ml-auto text-xs text-gray-300 group-hover:text-white transition-colors"></i>
                    </button>
                    
                    <div id="sidebar-user-menu" class="hidden absolute bottom-full left-0 mb-2 w-full bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                        <form action="{{ route('superadmin.logout') }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center">
                                <i class="fas fa-sign-out-alt mr-2 text-primary"></i> 
                                <span class="text-primary">Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile sidebar backdrop -->
    <div id="mobile-sidebar-backdrop" class="fixed inset-0 z-40 bg-black bg-opacity-75 hidden"></div>

    <!-- Mobile sidebar -->
    <div id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 transform -translate-x-full transition duration-300 ease-in-out bg-gradient-to-b from-primary to-primary-dark">
        <div class="flex flex-col items-center justify-center h-40 px-4 bg-primary-dark/30 py-6">
            <div class="h-20 w-20 bg-white/20 rounded-2xl flex items-center justify-center p-3 backdrop-blur-sm mb-3">
                <img src="{{ asset('img/logo.png') }}" alt="Contracting Alliance Inc. Logo" class="h-14 w-auto object-contain" />
            </div>
            <span class="text-lg font-bold text-white text-center">Contracting Alliance</span>
            <span class="text-xs text-white/70 mt-1">Administration Panel</span>
        </div>
        <div class="flex-1 overflow-y-auto py-4">
            <nav class="px-2 space-y-1">
                <a href="{{ route('superadmin.users.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                    <i class="fas fa-tachometer-alt mr-3 text-gray-300 group-hover:text-white"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('superadmin.crew.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                    <i class="fas fa-users mr-3 text-gray-300 group-hover:text-white"></i>
                    Crew
                </a>

                <a href="{{ route('superadmin.photos.projects') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                    <i class="fas fa-images mr-3 text-gray-300 group-hover:text-white"></i>
                    Photos
                </a>

                <a href="{{ route('superadmin.chat.view') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                    <i class="fas fa-comments mr-3 text-gray-300 group-hover:text-white"></i>
                    Chat
                </a>

                <a href="{{ route('superadmin.subcontractors.insurances.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                    <i class="fas fa-shield-alt mr-3 text-gray-300 group-hover:text-white"></i>
                    Insurance
                </a>
                
                <a href="{{ route('superadmin.invoices.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                    <i class="fas fa-file-invoice-dollar mr-3 text-gray-300 group-hover:text-white"></i>
                    Invoices
                </a>
            </nav>
        </div>
        <div class="p-4 border-t border-white/10">
            <div class="relative">
                <button id="mobile-sidebar-user-menu-button" class="flex items-center w-full text-left focus:outline-none group">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center text-white group-hover:bg-white/20 transition-colors">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-medium text-gray-300">Administrator</p>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between px-4 py-3 sm:px-6">
                <!-- Mobile menu button -->
                <button id="mobile-sidebar-toggle" class="md:hidden text-gray-500 hover:text-primary focus:outline-none transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <!-- Page title and actions -->
                <div class="flex-1 flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-gray-900">@yield('title')</h1>
                    <div class="flex items-center space-x-4">
                        @yield('actions')
                        <!-- User dropdown for mobile -->
                        <div class="md:hidden relative">
                            <button id="mobile-user-menu-button" class="flex items-center space-x-1 focus:outline-none group">
                                <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-white text-sm group-hover:bg-primary-light transition-colors">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </button>
                            
                            <div id="mobile-user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                <form action="{{ route('superadmin.logout') }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center">
                                        <i class="fas fa-sign-out-alt mr-2 text-primary"></i> 
                                        <span class="text-primary">Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main content area -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t py-4">
            <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-600">
                &copy; {{ date('Y') }} Contracting Alliance Inc. All rights reserved.
            </div>
        </footer>
    </div>

    <script>
        // Toggle mobile sidebar
        document.getElementById('mobile-sidebar-toggle').addEventListener('click', function() {
            document.getElementById('mobile-sidebar').classList.toggle('-translate-x-full');
            document.getElementById('mobile-sidebar-backdrop').classList.toggle('hidden');
        });

        // Close mobile sidebar when clicking backdrop
        document.getElementById('mobile-sidebar-backdrop').addEventListener('click', function() {
            document.getElementById('mobile-sidebar').classList.add('-translate-x-full');
            this.classList.add('hidden');
        });

        // Toggle user dropdowns
        document.getElementById('sidebar-user-menu-button').addEventListener('click', function() {
            document.getElementById('sidebar-user-menu').classList.toggle('hidden');
        });

        document.getElementById('mobile-user-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-user-menu').classList.toggle('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#sidebar-user-menu-button')) {
                document.getElementById('sidebar-user-menu').classList.add('hidden');
            }
            if (!event.target.closest('#mobile-user-menu-button')) {
                document.getElementById('mobile-user-menu').classList.add('hidden');
            }
        });
    </script>
</body>
</html>