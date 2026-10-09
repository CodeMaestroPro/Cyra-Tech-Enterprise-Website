<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="theme-color" content="#0052ff">

        <title>@yield('title', 'Cyra-Tech Training Portal')</title>

        <x-brand.favicon />

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|plus-jakarta-sans:500,600,700,800&display=swap" rel="stylesheet" />

        <script>
            document.documentElement.setAttribute('data-cyra-theme', 'light');
            document.documentElement.style.colorScheme = 'light';
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="theme-training-portal min-h-screen font-sans antialiased">
        @yield('content')
        @stack('scripts')
    </body>
</html>
