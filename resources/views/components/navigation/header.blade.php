@props([
    'navigation' => [],
])

@php
    $brand = $navigation['brand'] ?? config('cyra.navigation.brand', []);
    $headerLinks = $navigation['header'] ?? [];
    $actions = $navigation['actions'] ?? [];
@endphp

<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-cyra-primary focus:px-4 focus:py-2 focus:text-white">
    Skip to main content
</a>

<header class="sticky top-0 z-40 border-b border-cyra-border/80 bg-cyra-midnight/95 backdrop-blur-md" data-navigation-header>
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg border border-cyra-border p-2 text-cyra-muted hover:bg-cyra-surface hover:text-cyra-text lg:hidden"
                aria-controls="mobile-navigation"
                aria-expanded="false"
                data-mobile-nav-toggle
            >
                <span class="sr-only">Open navigation menu</span>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="{{ route('home') }}" class="group flex items-center gap-2" aria-label="{{ $brand['name'] ?? config('cyra.name') }} home">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-cyra-primary to-cyra-accent text-sm font-bold text-white">
                    C
                </span>
                <span class="text-lg font-bold tracking-tight text-cyra-text">
                    CYRA<span class="text-cyra-accent">{{ $brand['accent'] ?? 'TECH' }}</span>
                </span>
            </a>
        </div>

        <nav class="hidden items-center gap-1 xl:flex" aria-label="Primary navigation">
            @foreach ($headerLinks as $link)
                <a
                    href="{{ $link['url'] }}"
                    @class([
                        'rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                        'bg-cyra-surface text-cyra-text' => $link['active'],
                        'text-cyra-muted hover:bg-cyra-surface/60 hover:text-cyra-text' => ! $link['active'],
                    ])
                    @if ($link['opens_in_new_tab']) target="_blank" rel="noreferrer" @endif
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-2 sm:gap-3">
            <button
                type="button"
                class="hidden rounded-lg border border-cyra-border p-2 text-cyra-muted hover:bg-cyra-surface hover:text-cyra-text sm:inline-flex"
                aria-label="Search (coming soon)"
                disabled
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>

            <button
                type="button"
                class="hidden rounded-lg border border-cyra-border p-2 text-cyra-muted hover:bg-cyra-surface hover:text-cyra-text md:inline-flex"
                aria-label="Theme toggle (coming soon)"
                disabled
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </button>

            @foreach ($actions as $action)
                @if (($action['style'] ?? 'link') === 'button')
                    <x-ui.button href="{{ $action['url'] }}" size="sm">
                        {{ $action['label'] }}
                    </x-ui.button>
                @else
                    <a
                        href="{{ $action['url'] }}"
                        class="hidden text-sm font-medium text-cyra-muted transition-colors hover:text-cyra-accent sm:inline-flex"
                        @if ($action['opens_in_new_tab']) target="_blank" rel="noreferrer" @endif
                    >
                        {{ $action['label'] }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</header>

@include('components.navigation.mobile-menu', ['navigation' => $navigation])
