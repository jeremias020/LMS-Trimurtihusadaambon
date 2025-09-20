<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'Sistem Manajemen Pembelajaran SMK Kesehatan Trimurti Husada')">
    <meta property="og:title" content="@yield('title', 'LMS Trimurti Husada')" />
    <meta property="og:description" content="@yield('description', 'Platform pembelajaran digital untuk siswa dan guru')" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset('uploads/logo/logo-og.png') }}" />
    <meta property="og:site_name" content="LMS Trimurti Husada" />
    <title>@yield('title', 'LMS Trimurti Husada')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('uploads/logo/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('uploads/logo/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('uploads/logo/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('uploads/logo/favicon-16x16.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('css')
    
    <!-- Global CSS to prevent footer floating -->
    <style>
        /* Ensure proper page flow and prevent footer floating issues */
        html, body {
            overflow-x: hidden;
            width: 100%;
        }
        
        #app {
            width: 100%;
            overflow-x: hidden;
        }
        
        main {
            width: 100%;
            clear: both;
            display: block;
        }
        
        footer {
            display: block !important;
            position: static !important;
            width: 100% !important;
            clear: both !important;
            float: none !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        
        /* Clear floats globally */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Navigation -->
        @include('partials.header')

        <!-- Main Content -->
        <main class="py-4">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    @stack('js')
</body>
</html>
