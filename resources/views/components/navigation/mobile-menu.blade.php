@props([
    'navigation' => [],
])

@php
    $headerLinks = $navigation['header'] ?? [];
    $actions = $navigation['actions'] ?? [];
@endphp

<div
    id="mobile-navigation"
    class="fixed inset-0 z-50 hidden xl:hidden"
    data-mobile-nav-panel
    aria-hidden="true"
>
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" data-mobile-nav-backdrop></div>

    <div class="absolute inset-y-0 left-0 flex w-full max-w-sm flex-col border-r border-cyra-border bg-cyra-surface shadow-2xl shadow-black/40 transition-transform duration-300 ease-out -translate-x-full" data-mobile-nav-drawer>
        <div class="flex items-center justify-between border-b border-cyra-border px-4 py-4">
            <x-brand.logo size="sm" variant="compact" />
            <button
                type="button"
                class="rounded-lg border border-cyra-border p-2 text-cyra-muted hover:bg-cyra-soft hover:text-cyra-text"
                aria-label="Close navigation menu"
                data-mobile-nav-close
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-4" aria-label="Mobile navigation">
            <button
                type="button"
                class="mb-4 flex w-full items-center gap-3 rounded-lg border border-cyra-border bg-cyra-navy/60 px-3 py-2.5 text-left text-sm text-cyra-muted transition-colors hover:border-cyra-primary/40 hover:text-cyra-text"
                aria-controls="public-nav-search"
                aria-expanded="false"
                data-public-nav-search-open
            >
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Search pages...</span>
            </button>

            <ul class="space-y-1">
                @foreach ($headerLinks as $link)
                    <li>
                        <a
                            href="{{ $link['url'] }}"
                            @class([
                                'block rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200',
                                'bg-cyra-primary/15 text-cyra-text' => $link['active'],
                                'text-cyra-muted hover:bg-cyra-soft hover:text-cyra-text' => ! $link['active'],
                            ])
                            data-mobile-nav-link
                        >
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6 space-y-2 border-t border-cyra-border pt-4">
                @foreach ($actions as $action)
                    @php($style = $action['style'] ?? 'link')
                    @if ($style === 'button')
                        <x-ui.button href="{{ $action['url'] }}" class="w-full justify-center" data-mobile-nav-link>
                            {{ $action['label'] }}
                        </x-ui.button>
                    @elseif ($style === 'outline')
                        <x-ui.button href="{{ $action['url'] }}" variant="outline" class="w-full justify-center" data-mobile-nav-link>
                            {{ $action['label'] }}
                        </x-ui.button>
                    @else
                        <a
                            href="{{ $action['url'] }}"
                            class="block rounded-lg px-3 py-2 text-sm font-medium text-cyra-muted hover:bg-cyra-soft hover:text-cyra-text"
                            data-mobile-nav-link
                        >
                            {{ $action['label'] }}
                        </a>
                    @endif
                @endforeach
            </div>
        </nav>
    </div>
</div>
