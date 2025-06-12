<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Contracting Alliance Inc.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#003366',
                        'primary-dark': '#002244',
                        secondary: '#D4AF37',
                        accent: '#FFD700',
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex font-sans">
    <!-- Sidebar -->
    <div class="hidden md:flex md:flex-shrink-0">
        <div class="flex flex-col w-64 bg-sidebar border-r border-primary-dark">
            <!-- Logo Section -->
            <div class="flex items-center justify-center h-16 px-4 bg-primary-dark">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-2 text-secondary" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-xl font-bold text-white">CONTRACTING ALLIANCE</span>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="flex-1 overflow-y-auto py-4">
                <nav class="px-2 space-y-1">
                    <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-primary hover:text-white">
                        <i class="fas fa-tachometer-alt mr-3 text-gray-400 group-hover:text-secondary"></i>
                        Dashboard
                    </a>
                    <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-primary hover:text-white">
                        <i class="fas fa-users mr-3 text-gray-400 group-hover:text-secondary"></i>
                        Groups
                    </a>
                    <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-primary hover:text-white">
                        <i class="fas fa-images mr-3 text-gray-400 group-hover:text-secondary"></i>
                        Photos
                    </a>
                    <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-primary hover:text-white">
                        <i class="fas fa-shield-alt mr-3 text-gray-400 group-hover:text-secondary"></i>
                        Insurance
                    </a>
                    <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-primary hover:text-white">
                        <i class="fas fa-file-invoice-dollar mr-3 text-gray-400 group-hover:text-secondary"></i>
                        Invoices
                    </a>
                </nav>
            </div>
            
            <!-- User Section -->
            <div class="p-4 border-t border-primary-dark">
                <div class="relative">
                    <button id="sidebar-user-menu-button" class="flex items-center w-full text-left focus:outline-none">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-primary-dark flex items-center justify-center text-white">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs font-medium text-gray-400">Administrator</p>
                        </div>
                        <i class="fas fa-chevron-down ml-auto text-xs text-gray-400"></i>
                    </button>
                    
                    <div id="sidebar-user-menu" class="hidden absolute bottom-full left-0 mb-2 w-full bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                        <form action="{{ route('superadmin.logout') }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center">
                                <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
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
    <div id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 transform -translate-x-full transition duration-300 ease-in-out bg-sidebar">
        <div class="flex items-center justify-center h-16 px-4 bg-primary-dark">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-2 text-secondary" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xl font-bold text-white">CONTRACTING ALLIANCE</span>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto py-4">
            <nav class="px-2 space-y-1">
                <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-primary hover:text-white">
                    <i class="fas fa-tachometer-alt mr-3 text-gray-400 group-hover:text-secondary"></i>
                    Dashboard
                </a>
                <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-primary hover:text-white">
                    <i class="fas fa-users mr-3 text-gray-400 group-hover:text-secondary"></i>
                    Groups
                </a>
                <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-primary hover:text-white">
                    <i class="fas fa-images mr-3 text-gray-400 group-hover:text-secondary"></i>
                    Photos
                </a>
                <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-primary hover:text-white">
                    <i class="fas fa-shield-alt mr-3 text-gray-400 group-hover:text-secondary"></i>
                    Insurance
                </a>
                <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-md text-gray-200 hover:bg-primary hover:text-white">
                    <i class="fas fa-file-invoice-dollar mr-3 text-gray-400 group-hover:text-secondary"></i>
                    Invoices
                </a>
            </nav>
        </div>
        <div class="p-4 border-t border-primary-dark">
            <div class="relative">
                <button id="mobile-sidebar-user-menu-button" class="flex items-center w-full text-left focus:outline-none">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-primary-dark flex items-center justify-center text-white">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-medium text-gray-400">Administrator</p>
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
                <button id="mobile-sidebar-toggle" class="md:hidden text-gray-500 hover:text-gray-600 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <!-- Page title and actions -->
                <div class="flex-1 flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-gray-900">@yield('title')</h1>
                    <div class="flex items-center space-x-4">
                        @yield('actions')
                        <!-- User dropdown for mobile -->
                        <div class="md:hidden relative">
                            <button id="mobile-user-menu-button" class="flex items-center space-x-1 focus:outline-none">
                                <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-white text-sm">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </button>
                            
                            <div id="mobile-user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                <form action="{{ route('superadmin.logout') }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
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