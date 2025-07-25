<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">

<!-- Ce layout utilise Grid pour centrer parfaitement le slot sans affecter sa largeur -->
<main class="min-h-screen grid place-items-center bg-gray-100 p-4">
    {{-- Le slot est maintenant libre de prendre la taille qu'il veut --}}
    {{ $slot }}
</main>

</body>
</html>
