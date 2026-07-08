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
        @php($user = auth()->user())
        <div class="flex min-h-screen">
            @isset($adminNavigation)
                <x-navigation.admin-sidebar :navigation="$adminNavigation" />
            @endisset

            <div class="flex flex-1 flex-col">
                <header class="border-b border-cyra-border bg-cyra-surface/60 px-6 py-4">
                    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                        <label class="relative block w-full max-w-md">
                            <span class="sr-only">Search Command Center</span>
                            <input
                                type="search"
                                placeholder="Search Command Center..."
                                class="w-full rounded-lg border border-cyra-border bg-cyra-navy px-4 py-2.5 pl-10 text-sm text-cyra-text placeholder:text-cyra-muted focus:border-cyra-primary focus:outline-none focus:ring-2 focus:ring-cyra-primary/30"
                            />
                            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-cyra-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/>
                            </svg>
                        </label>

                        <div class="flex items-center justify-between gap-4 xl:justify-end">
                            <div class="hidden text-right text-sm text-cyra-muted lg:block">
                                {{ now()->format('l, F j, Y | h:i A T') }}
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-cyra-text">{{ $user?->name }}</p>
                                <p class="text-xs text-cyra-muted">{{ $user?->getPrimaryRoleName() ?? 'Team Member' }}</p>
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
