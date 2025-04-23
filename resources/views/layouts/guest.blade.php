<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" />


    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(120deg, #004A99, #00c6ff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .auth-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }

        .auth-header {
            background-color: #004A99;
            color: #fff;
            padding: 2rem;
            text-align: center;
        }

        .auth-body {
            padding: 2rem;
        }

        .logo-img {
            height: 60px;
            margin-bottom: 10px;
        }

        .footer-note {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #fff;
            opacity: 0.8;
        }
    </style>

    @stack('css')
</head>
<body>

    <div class="container">
        <div class="auth-container mx-auto">
            <div class="auth-header">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-img" height="100px" width="300px">
            </div>
            <div class="auth-body">
                {{ $slot }}
            </div>
        </div>

        <div class="footer-note">
            &copy; {{ date('Y') }} Contracting Alliance Inc. All rights reserved.
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    @stack('scripts')
</body>
</html>
