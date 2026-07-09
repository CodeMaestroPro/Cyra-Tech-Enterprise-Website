@extends('layouts.app')

@section('title', $project['title'].' | Portfolio')

@push('head')
    <meta name="description" content="{{ $project['summary'] }}">
    <meta property="og:title" content="{{ $project['title'] }} | Cyra-Tech Portfolio">
    <meta property="og:description" content="{{ $project['summary'] }}">
@endpush

@section('content')
    <main id="main-content">
        <section class="cyra-page-hero">
            <div class="cyra-page-hero-glow" aria-hidden="true"></div>
            <div class="cyra-container relative cyra-section-hero-inner">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Portfolio', 'href' => route('portfolio')],
                    ['label' => $project['title']],
                ]" />

                <div class="mt-6 flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
                        <x-homepage.icon :name="$project['icon'] ?? 'spark'" />
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <x-ui.badge variant="purple">{{ $project['client_name'] }}</x-ui.badge>
                            <x-ui.badge variant="primary">{{ $project['category_label'] }}</x-ui.badge>
                        </div>
                        <h1 class="mt-3 cyra-display">{{ $project['title'] }}</h1>
                        <p class="mt-2 text-lg font-medium text-cyra-accent">{{ $project['tagline'] }}</p>
                        <p class="mt-3 max-w-3xl text-base leading-relaxed text-cyra-muted">{{ $project['summary'] }}</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="cyra-container cyra-section">
            <div class="grid gap-10 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h2 class="cyra-heading-2">Program Overview</h2>
                    <p class="mt-4 text-base leading-relaxed text-cyra-muted">{{ $project['description'] }}</p>

                    <h2 class="cyra-heading-2 mt-10">Key Outcomes</h2>
                    <ul class="mt-6 space-y-3">
                        @foreach ($project['outcomes'] ?? [] as $outcome)
                            <li class="flex items-start gap-3 cyra-chip">
                                <span class="mt-0.5 text-cyra-success">✓</span>
                                {{ $outcome }}
                            </li>
                        @endforeach
                    </ul>

                    <h2 class="cyra-heading-2 mt-10">Services Delivered</h2>
                    <div class="mt-6 flex flex-wrap gap-2">
                        @foreach ($project['services'] ?? [] as $service)
                            <x-ui.badge variant="primary">{{ $service }}</x-ui.badge>
                        @endforeach
                    </div>
                </div>

                <aside class="cyra-card p-6">
                    <h2 class="text-lg font-semibold text-cyra-text">Impact Metrics</h2>
                    <dl class="mt-4 space-y-4">
                        @foreach ($project['metrics'] ?? [] as $metric)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-cyra-muted">{{ $metric['label'] ?? '' }}</dt>
                                <dd class="mt-1 text-lg font-semibold text-cyra-success">{{ $metric['value'] ?? '' }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    @if (! empty($project['duration']))
                        <div class="mt-6 border-t border-cyra-border/60 pt-4">
                            <p class="text-xs uppercase tracking-wide text-cyra-muted">Program Duration</p>
                            <p class="mt-1 text-sm font-medium text-cyra-text">{{ $project['duration'] }}</p>
                        </div>
                    @endif
                </aside>
            </div>

            <div class="mt-12 flex flex-wrap gap-3">
                <x-ui.button href="{{ route('contact') }}">Start a Conversation</x-ui.button>
                <x-ui.button href="{{ route('solutions') }}" variant="secondary">Explore Solutions</x-ui.button>
                <x-ui.button href="{{ route('portfolio') }}" variant="secondary">Back to Portfolio</x-ui.button>
            </div>
        </div>
    </main>
@endsection
