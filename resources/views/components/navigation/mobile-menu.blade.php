@props([
    'navigation' => [],
])

@php
    $headerLinks = $navigation['header'] ?? [];
    $actions = $navigation['actions'] ?? [];
@endphp

<div
    id="mobile-navigation"
    class="fixed inset-0 z-50 hidden lg:hidden"
    data-mobile-nav-panel
    aria-hidden="true"
>
    <div class="absolute inset-0 bg-cyra-midnight/80 backdrop-blur-sm" data-mobile-nav-backdrop></div>

    <div class="absolute inset-y-0 left-0 flex w-full max-w-sm flex-col border-r border-cyra-border bg-cyra-navy shadow-2xl">
        <div class="flex items-center justify-between border-b border-cyra-border px-4 py-4">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyra-accent">Menu</p>
            <button
                type="button"
                class="rounded-lg border border-cyra-border p-2 text-cyra-muted hover:bg-cyra-surface hover:text-cyra-text"
                aria-label="Close navigation menu"
                data-mobile-nav-close
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-4" aria-label="Mobile navigation">
            <ul class="space-y-1">
                @foreach ($headerLinks as $link)
                    <li>
                        <a
                            href="{{ $link['url'] }}"
                            @class([
                                'block rounded-lg px-3 py-2 text-sm font-medium',
                                'bg-cyra-surface text-cyra-text' => $link['active'],
                                'text-cyra-muted hover:bg-cyra-surface/60 hover:text-cyra-text' => ! $link['active'],
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
                    @if (($action['style'] ?? 'link') === 'button')
                        <x-ui.button href="{{ $action['url'] }}" class="w-full justify-center" data-mobile-nav-link>
                            {{ $action['label'] }}
                        </x-ui.button>
                    @else
                        <a
                            href="{{ $action['url'] }}"
                            class="block rounded-lg px-3 py-2 text-sm font-medium text-cyra-muted hover:bg-cyra-surface/60 hover:text-cyra-text"
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
