@props([
    'navigation' => [],
])

@php
    $headerLinks = $navigation['header'] ?? [];
    $actions = $navigation['actions'] ?? [];
    $publicSearchIndex = $publicSearchIndex ?? [];
@endphp

<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-cyra-primary focus:px-4 focus:py-2 focus:text-white">
    Skip to main content
</a>

<header class="cyra-header-glass sticky top-0 z-40" data-navigation-header>
    <div class="mx-auto flex max-w-7xl items-center gap-3 px-4 py-3 sm:gap-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-2 sm:gap-3 lg:flex-1">
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg border border-cyra-border p-2 text-cyra-muted hover:bg-cyra-soft hover:text-cyra-text xl:hidden"
                aria-controls="mobile-navigation"
                aria-expanded="false"
                data-mobile-nav-toggle
            >
                <span class="sr-only">Open navigation menu</span>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <x-brand.logo size="sm" variant="compact" class="min-w-0" />
        </div>

        <nav class="hidden flex-1 items-center justify-center gap-0.5 xl:flex" aria-label="Primary navigation">
            @foreach ($headerLinks as $link)
                <a
                    href="{{ $link['url'] }}"
                    @class([
                        'cyra-nav-link',
                        'cyra-nav-link-active' => $link['active'],
                    ])
                    @if ($link['opens_in_new_tab']) target="_blank" rel="noreferrer" @endif
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex flex-1 items-center justify-end gap-2 sm:gap-3">
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg border border-cyra-border p-2 text-cyra-muted transition-colors hover:bg-cyra-soft hover:text-cyra-text"
                aria-label="Search site"
                aria-controls="public-nav-search"
                aria-expanded="false"
                data-public-nav-search-open
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>

            <x-theme.toggle class="hidden md:inline-flex" />

            @foreach ($actions as $action)
                @php($style = $action['style'] ?? 'link')
                @if ($style === 'button')
                    <x-ui.button href="{{ $action['url'] }}" size="sm" class="hidden sm:inline-flex">
                        {{ $action['label'] }}
                    </x-ui.button>
                @elseif ($style === 'outline')
                    <x-ui.button href="{{ $action['url'] }}" variant="outline" size="sm" class="hidden sm:inline-flex">
                        {{ $action['label'] }}
                    </x-ui.button>
                @else
                    <a
                        href="{{ $action['url'] }}"
                        class="hidden text-sm font-medium text-cyra-muted transition-colors hover:text-cyra-primary sm:inline-flex"
                        @if ($action['opens_in_new_tab']) target="_blank" rel="noreferrer" @endif
                    >
                        {{ $action['label'] }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</header>

<div
    id="public-nav-search"
    class="fixed inset-0 z-[60] hidden"
    data-public-nav-search-panel
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
    aria-label="Search site navigation"
>
    <div class="absolute inset-0 bg-cyra-midnight/80 backdrop-blur-sm" data-public-nav-search-backdrop></div>

    <div class="relative mx-auto flex min-h-full w-full max-w-2xl items-start justify-center px-3 pb-6 pt-[max(1rem,env(safe-area-inset-top))] sm:px-4 sm:pt-[12vh]">
        <div class="w-full overflow-hidden rounded-2xl border border-cyra-border bg-cyra-surface shadow-2xl shadow-black/30">
            <div class="flex items-center gap-3 border-b border-cyra-border px-3 py-3 sm:px-4">
                <label class="relative min-w-0 flex-1">
                    <span class="sr-only">Search pages and sections</span>
                    <input
                        type="search"
                        placeholder="Search pages, solutions, careers..."
                        autocomplete="off"
                        role="combobox"
                        aria-autocomplete="list"
                        aria-expanded="false"
                        aria-controls="public-nav-search-results"
                        data-public-nav-search-input
                        class="w-full rounded-lg border border-cyra-border bg-cyra-navy px-4 py-2.5 pl-10 text-sm text-cyra-text placeholder:text-cyra-muted focus:border-cyra-primary focus:outline-none focus:ring-2 focus:ring-cyra-primary/30"
                    />
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-cyra-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/>
                    </svg>
                </label>

                <button
                    type="button"
                    class="inline-flex shrink-0 items-center justify-center rounded-lg border border-cyra-border p-2 text-cyra-muted transition-colors hover:bg-cyra-soft hover:text-cyra-text"
                    aria-label="Close search"
                    data-public-nav-search-close
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div
                id="public-nav-search-results"
                data-public-nav-search-results
                hidden
                class="max-h-[min(60vh,28rem)] overflow-y-auto"
                role="listbox"
            ></div>

            <p class="hidden border-t border-cyra-border px-4 py-2 text-xs text-cyra-muted sm:block">
                Tip: Press <kbd class="rounded border border-cyra-border px-1.5 py-0.5 font-mono text-[10px]">Ctrl</kbd>+<kbd class="rounded border border-cyra-border px-1.5 py-0.5 font-mono text-[10px]">K</kbd> anywhere to search.
            </p>
        </div>
    </div>
</div>

<script type="application/json" id="public-navigation-index">@json($publicSearchIndex)</script>

@include('components.navigation.mobile-menu', ['navigation' => $navigation])
