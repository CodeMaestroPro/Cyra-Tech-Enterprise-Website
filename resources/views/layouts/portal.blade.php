<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#050810">
        <title>@yield('title', 'Client Portal') - {{ config('cyra.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-cyra-midnight font-sans antialiased text-cyra-text">
        @php($user = auth()->user())
        <div class="flex min-h-screen">
            <aside class="hidden w-64 shrink-0 border-r border-cyra-border bg-cyra-surface/60 lg:block">
                <div class="border-b border-cyra-border px-6 py-5">
                    <a href="{{ route('client-portal.dashboard') }}" class="text-lg font-bold tracking-tight text-cyra-text">
                        CYRA<span class="text-cyra-accent">TECH</span>
                    </a>
                    <p class="mt-1 text-xs uppercase tracking-wide text-cyra-muted">Client Portal</p>
                </div>
                <nav class="space-y-1 p-4" aria-label="Client portal navigation">
                    <a href="{{ route('client-portal.dashboard') }}" @class([
                        'block rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                        'bg-cyra-primary/15 text-cyra-text' => request()->routeIs('client-portal.dashboard'),
                        'text-cyra-muted hover:bg-cyra-navy/60 hover:text-cyra-text' => ! request()->routeIs('client-portal.dashboard'),
                    ])>
                        Dashboard
                    </a>
                    <a href="{{ route('contact', ['inquiry' => 'support']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-cyra-muted transition-colors hover:bg-cyra-navy/60 hover:text-cyra-text">
                        Support
                    </a>
                    <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-cyra-muted transition-colors hover:bg-cyra-navy/60 hover:text-cyra-text">
                        Cyra-Tech Website
                    </a>
                </nav>
            </aside>

            <div class="flex flex-1 flex-col">
                <header class="border-b border-cyra-border bg-cyra-surface/60 px-6 py-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm text-cyra-muted">Welcome back,</p>
                            <p class="text-lg font-semibold text-cyra-text">{{ $user?->name }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            @if (! empty($dashboard['account']['name'] ?? null))
                                <div class="hidden text-right sm:block">
                                    <p class="text-sm font-medium text-cyra-text">{{ $dashboard['account']['name'] }}</p>
                                    <p class="text-xs text-cyra-muted">{{ $dashboard['account']['account_manager'] ?? 'Account Team' }}</p>
                                </div>
                            @endif
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
