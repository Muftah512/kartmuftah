<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>{{ config('app.name','KartMuftah') }}</title>
</head>
<body class="font-sans antialiased">
    @include('layouts.navigation')  {{-- أو nav-component --}}
    <div class="min-h-screen bg-gray-100">
        {{ $slot }}
    </div>
</body>
</html>
