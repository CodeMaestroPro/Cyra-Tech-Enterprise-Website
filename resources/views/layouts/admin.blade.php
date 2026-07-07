<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#050810">
        <title>@yield('title', 'Admin') - {{ config('cyra.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-cyra-midnight font-sans antialiased text-cyra-text">
        <div class="flex min-h-screen">
            <aside class="hidden w-72 border-r border-cyra-border bg-cyra-navy/80 p-6 lg:block">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyra-accent">Command Center</p>
                <h1 class="mt-2 text-xl font-bold">{{ config('cyra.name') }}</h1>
                <nav class="mt-8 space-y-2 text-sm text-cyra-muted" aria-label="Admin navigation">
                    <p class="rounded-lg bg-cyra-surface px-3 py-2 text-cyra-text">Dashboard</p>
                    <p class="px-3 py-2">Users &amp; Roles</p>
                    <p class="px-3 py-2">Enterprise Settings</p>
                </nav>
            </aside>

            <div class="flex flex-1 flex-col">
                <header class="border-b border-cyra-border bg-cyra-surface/60 px-6 py-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm text-cyra-muted">Good Morning,</p>
                            <p class="text-lg font-semibold text-cyra-text">{{ $user->name }} 👋</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <p class="text-sm font-medium text-cyra-text">{{ $user->name }}</p>
                                <p class="text-xs text-cyra-muted">{{ $user->getPrimaryRoleName() ?? 'Team Member' }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-ui.button type="submit" variant="secondary" size="sm">Sign Out</x-ui.button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="flex-1 p-6">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
