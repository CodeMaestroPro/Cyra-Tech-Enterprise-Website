<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ config('cyra.tagline') }}">
        <meta name="theme-color" content="#000B26">

        <title>@yield('title', config('cyra.name'))</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|plus-jakarta-sans:500,600,700,800&display=swap" rel="stylesheet" />

        <x-theme.init-script />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="theme-public min-h-screen bg-cyra-midnight font-sans antialiased text-cyra-text @yield('body_class')">
        @unless (request()->routeIs('login'))
            @isset($publicNavigation)
                <x-navigation.header :navigation="$publicNavigation" />
            @endisset
        @endunless

        <div class="@unless(request()->routeIs('login')) cyra-page-enter @endunless">
            @yield('content')
        </div>

        @unless (request()->routeIs('login'))
            @isset($publicNavigation)
                <x-navigation.footer :navigation="$publicNavigation" />
            @endisset
        @endunless
    </body>
</html>
