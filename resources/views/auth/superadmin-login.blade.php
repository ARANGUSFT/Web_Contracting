<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Contracting Alliance Inc.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#003366',       // Deep blue for trust and professionalism
                        'primary-dark': '#002244', // Darker blue
                        secondary: '#D4AF37',     // Gold accent color
                        accent: '#FFD700',        // Brighter gold for highlights
                        dark: '#1a202c',
                        light: '#f7fafc'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-primary to-primary-dark min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="w-full max-w-lg bg-white rounded-xl shadow-2xl overflow-hidden">
        <!-- Header Section with Logo -->
        <div class="bg-primary p-6 text-center">
            <div class="flex flex-col items-center mb-4">
                <!-- Logo with company name -->
                <div class="text-white mb-2">
                    <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white tracking-wide">CONTRACTING</h1>
                <h2 class="text-2xl font-bold text-secondary">ALLIANCE INC.</h2>
                <p class="text-primary-100 mt-2 text-white opacity-90">Administrative Control Panel</p>
            </div>
        </div>

        <!-- Login Form Section -->
        <div class="p-8">
            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200 flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('superadmin.login') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </div>
                        <input type="email" name="email" required 
                            class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-secondary focus:border-secondary transition"
                            placeholder="admin@contractingalliance.com" autofocus>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input type="password" name="password" required 
                            class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-secondary focus:border-secondary transition"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" 
                            class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-primary hover:text-primary-dark">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <button type="submit" 
                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-secondary hover:bg-accent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Sign In
                </button>
            </form>
        </div>

        <!-- Footer Section -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="text-center text-sm text-gray-500">
                &copy; 2023 Contracting Alliance Inc. All rights reserved.
            </div>
        </div>
    </div>

</body>
</html>