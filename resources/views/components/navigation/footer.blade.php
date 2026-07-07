@props([
    'navigation' => [],
])

@php
    $footer = $navigation['footer'] ?? [];
    $columns = $footer['columns'] ?? [];
    $social = $footer['social'] ?? [];
    $legal = $footer['legal'] ?? [];
    $newsletter = $footer['newsletter'] ?? [];
    $brand = $navigation['brand'] ?? config('cyra.navigation.brand', []);
@endphp

<footer class="border-t border-cyra-border bg-cyra-navy/60" data-navigation-footer>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-cyra-primary to-cyra-accent text-sm font-bold text-white">
                        C
                    </span>
                    <span class="text-lg font-bold text-cyra-text">
                        CYRA<span class="text-cyra-accent">{{ $brand['accent'] ?? 'TECH' }}</span>
                    </span>
                </a>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-cyra-muted">
                    {{ config('cyra.tagline') }}
                </p>

                @if (! empty($newsletter))
                    <div class="mt-6">
                        <h2 class="text-sm font-semibold text-cyra-text">{{ $newsletter['title'] ?? 'Newsletter' }}</h2>
                        <p class="mt-2 text-sm text-cyra-muted">{{ $newsletter['description'] ?? '' }}</p>
                        <form class="mt-4 flex flex-col gap-2 sm:flex-row" action="#" method="post" aria-label="Newsletter signup">
                            <label for="newsletter-email" class="sr-only">Email address</label>
                            <input
                                id="newsletter-email"
                                type="email"
                                name="email"
                                placeholder="{{ $newsletter['placeholder'] ?? 'Email address' }}"
                                class="min-w-0 flex-1 rounded-lg border border-cyra-border bg-cyra-surface px-3 py-2 text-sm text-cyra-text placeholder:text-cyra-muted focus:border-cyra-primary focus:outline-none focus:ring-2 focus:ring-cyra-primary/30"
                                disabled
                            >
                            <x-ui.button type="button" variant="secondary" size="sm" disabled>
                                {{ $newsletter['button'] ?? 'Subscribe' }}
                            </x-ui.button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="grid gap-8 sm:grid-cols-2 lg:col-span-5 lg:grid-cols-4">
                @foreach ($columns as $column)
                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-cyra-text">{{ $column['title'] }}</h2>
                        <ul class="mt-4 space-y-2">
                            @foreach ($column['links'] as $link)
                                <li>
                                    <a
                                        href="{{ $link['url'] }}"
                                        class="text-sm text-cyra-muted transition-colors hover:text-cyra-accent"
                                        @if ($link['opens_in_new_tab']) target="_blank" rel="noreferrer" @endif
                                    >
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="lg:col-span-3">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-cyra-text">Follow Cyra-Tech</h2>
                <ul class="mt-4 space-y-2">
                    @foreach ($social as $link)
                        <li>
                            <a
                                href="{{ $link['url'] }}"
                                class="text-sm text-cyra-muted transition-colors hover:text-cyra-accent"
                                target="_blank"
                                rel="noreferrer"
                            >
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-10 flex flex-col gap-4 border-t border-cyra-border pt-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-cyra-muted">
                &copy; {{ date('Y') }} {{ config('cyra.name') }}. All rights reserved.
            </p>
            <ul class="flex flex-wrap gap-4">
                @foreach ($legal as $link)
                    <li>
                        <a href="{{ $link['url'] }}" class="text-sm text-cyra-muted hover:text-cyra-accent">
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>
