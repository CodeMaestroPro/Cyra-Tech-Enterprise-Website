@props([
    'navigation' => [],
])

@php
    $brand = $navigation['brand'] ?? config('cyra.navigation.brand', []);
    $groups = $navigation['groups'] ?? [];
@endphp

<aside class="hidden w-72 border-r border-cyra-border bg-cyra-navy/80 lg:block">
    <div class="sticky top-0 flex h-screen flex-col overflow-y-auto p-6">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyra-accent">Command Center</p>
        <h1 class="mt-2 text-xl font-bold text-cyra-text">{{ $brand['name'] ?? config('cyra.name') }}</h1>

        <nav class="mt-8 space-y-6" aria-label="Admin navigation">
            @foreach ($groups as $group)
                <div>
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wide text-cyra-muted">
                        {{ $group['label'] }}
                    </p>
                    <ul class="space-y-1">
                        @foreach ($group['items'] as $item)
                            <li>
                                @if ($item['available'])
                                    <a
                                        href="{{ $item['url'] }}"
                                        @class([
                                            'block rounded-lg px-3 py-2 text-sm transition-colors',
                                            'bg-cyra-surface text-cyra-text' => $item['active'],
                                            'text-cyra-muted hover:bg-cyra-surface/60 hover:text-cyra-text' => ! $item['active'],
                                        ])
                                    >
                                        {{ $item['label'] }}
                                    </a>
                                @else
                                    <span class="block rounded-lg px-3 py-2 text-sm text-cyra-muted/70" title="Coming in a future module">
                                        {{ $item['label'] }}
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>

        <div class="mt-auto border-t border-cyra-border/70 pt-6">
            <a
                href="{{ route('home') }}"
                target="_blank"
                rel="noopener"
                class="flex items-center justify-center gap-2 rounded-lg border border-cyra-border bg-cyra-surface/60 px-4 py-2.5 text-sm font-medium text-cyra-text transition hover:border-cyra-primary/40"
            >
                View Website
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </div>
    </div>
</aside>
