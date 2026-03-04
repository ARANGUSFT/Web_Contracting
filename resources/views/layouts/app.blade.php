<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Contracting Alliance Inc')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('img/logo2.png') }}">
</head>
<body>

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Contenido --}}
    <div class="layout-container">
        <main class="content">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS (OBLIGATORIO) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Extras -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #d1d3d4;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .layout-container {
            width: 100%;
            padding-top: 80px;
            display: flex;
            justify-content: center;
        }

        .content {
            max-width: 1200px;
            width: 100%;
            padding: 20px;
            background: rgb(238, 237, 237);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-bottom: 30px;
        }
    </style>

</body>
</html>
