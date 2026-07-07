@props(['section'])

@php
    $content = $section['content'] ?? [];
    $actions = $content['actions'] ?? [];
    $highlights = $content['highlights'] ?? [];
@endphp

<section class="relative overflow-hidden border-b border-cyra-border/60" aria-labelledby="homepage-hero-title">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(37,99,235,0.18),transparent_40%),radial-gradient(circle_at_top_right,rgba(6,182,212,0.12),transparent_35%)]"></div>
    <div class="pointer-events-none absolute -right-20 top-20 h-72 w-72 rounded-full bg-cyra-primary/10 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="grid items-center gap-12 lg:grid-cols-2">
            <div>
                @if (! empty($section['eyebrow']))
                    <p class="cyra-caption">{{ $section['eyebrow'] }}</p>
                @endif
                <h1 id="homepage-hero-title" class="mt-4 cyra-display">
                    <span class="cyra-gradient-text">{{ $section['title'] ?? config('cyra.tagline') }}</span>
                </h1>
                @if (! empty($section['description']))
                    <p class="mt-6 max-w-xl text-lg leading-relaxed text-cyra-muted">
                        {{ $section['description'] }}
                    </p>
                @endif

                @if (count($actions) > 0)
                    <div class="mt-8 flex flex-wrap gap-3">
                        @foreach ($actions as $action)
                            <x-ui.button
                                href="{{ route($action['route']) }}"
                                :variant="$action['variant'] === 'primary' ? 'primary' : 'secondary'"
                            >
                                {{ $action['label'] }}
                            </x-ui.button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="cyra-card p-6 lg:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyra-accent">Platform Highlights</p>
                <ul class="mt-6 space-y-4">
                    @foreach ($highlights as $highlight)
                        <li class="flex items-start gap-3 rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3">
                            <span class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-lg bg-cyra-primary/15 text-cyra-accent">
                                <x-homepage.icon />
                            </span>
                            <span class="text-sm font-medium text-cyra-text">{{ $highlight }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
