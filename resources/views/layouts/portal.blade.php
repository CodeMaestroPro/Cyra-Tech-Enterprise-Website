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
                    <x-brand.logo href="{{ route('client-portal.dashboard') }}" size="sm" variant="compact" />
                    <p class="mt-2 text-xs uppercase tracking-wide text-cyra-muted">Client Portal</p>
                </div>
                <nav class="space-y-1 p-4" aria-label="Client portal navigation">
                    <a href="{{ route('client-portal.dashboard') }}" @class([
                        'block rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200',
                        'bg-cyra-primary/15 text-cyra-text' => request()->routeIs('client-portal.dashboard'),
                        'text-cyra-muted hover:bg-cyra-navy/60 hover:text-cyra-text' => ! request()->routeIs('client-portal.dashboard'),
                    ])>
                        Dashboard
                    </a>
                    <a href="{{ route('contact', ['inquiry' => 'support']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-cyra-muted transition-all duration-200 hover:bg-cyra-navy/60 hover:text-cyra-text">
                        Support
                    </a>
                    <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-cyra-muted transition-all duration-200 hover:bg-cyra-navy/60 hover:text-cyra-text">
                        Cyra-Tech Website
                    </a>
                </nav>
            </aside>

            <div
                id="portal-mobile-navigation"
                class="fixed inset-0 z-50 hidden lg:hidden"
                data-portal-nav-panel
                aria-hidden="true"
            >
                <div class="absolute inset-0 bg-cyra-midnight/80 backdrop-blur-sm" data-portal-nav-backdrop></div>
                <div class="absolute inset-y-0 left-0 flex w-full max-w-xs flex-col border-r border-cyra-border bg-cyra-navy shadow-2xl transition-transform duration-300 ease-out -translate-x-full" data-portal-nav-drawer>
                    <div class="flex items-center justify-between border-b border-cyra-border px-4 py-4">
                        <x-brand.logo href="{{ route('client-portal.dashboard') }}" size="sm" variant="compact" />
                        <button type="button" class="rounded-lg border border-cyra-border p-2 text-cyra-muted hover:bg-cyra-surface hover:text-cyra-text" aria-label="Close portal navigation menu" data-portal-nav-close>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <nav class="space-y-1 p-4" aria-label="Client portal mobile navigation">
                        <a href="{{ route('client-portal.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-cyra-text" data-portal-nav-link>Dashboard</a>
                        <a href="{{ route('contact', ['inquiry' => 'support']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-cyra-muted" data-portal-nav-link>Support</a>
                        <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-cyra-muted" data-portal-nav-link>Cyra-Tech Website</a>
                    </nav>
                </div>
            </div>

            <div class="flex min-w-0 flex-1 flex-col">
                <header class="sticky top-0 z-30 border-b border-cyra-border bg-cyra-surface/80 backdrop-blur-md px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-lg border border-cyra-border p-2 text-cyra-muted transition-colors hover:bg-cyra-navy hover:text-cyra-text lg:hidden"
                                aria-controls="portal-mobile-navigation"
                                aria-expanded="false"
                                data-portal-nav-toggle
                            >
                                <span class="sr-only">Open portal navigation menu</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div>
                                <p class="text-sm text-cyra-muted">Welcome back,</p>
                                <p class="text-base font-semibold text-cyra-text sm:text-lg">{{ $user?->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4">
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

                <main id="main-content" class="cyra-page-enter flex-1 p-4 sm:p-6">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
