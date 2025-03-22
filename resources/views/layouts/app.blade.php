<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mi Aplicación')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Contenedor Principal -->
    <div class="layout-container">
        <main class="content">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



    <style>
        /* 🎨 Estilos Generales */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #dddedf;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* 🏗️ Contenedor del Layout */
        .layout-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            padding-top: 80px; /* Espacio para el navbar fijo */
        }

        /* 📌 Contenedor del contenido principal */
        .content {
            max-width: 1050px;  /* Controla el ancho del contenido */
            width: 100%;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-bottom: 30px;
        }

    </style>

    
</body>
</html>
