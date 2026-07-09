@extends('layouts.app')

@section('title', $initiative['title'].' | Innovation Lab')

@push('head')
    <meta name="description" content="{{ $initiative['summary'] }}">
    <meta property="og:title" content="{{ $initiative['title'] }} | Cyra-Tech Innovation Lab">
    <meta property="og:description" content="{{ $initiative['summary'] }}">
@endpush

@section('content')
    <main id="main-content">
        <section class="cyra-page-hero">
            <div class="cyra-page-hero-glow" aria-hidden="true"></div>
            <div class="cyra-container relative cyra-section-hero-inner">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Innovation Lab', 'href' => route('innovation-lab')],
                    ['label' => $initiative['title']],
                ]" />

                <div class="mt-6 flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
                        <x-homepage.icon :name="$initiative['icon'] ?? 'spark'" />
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            @if (! empty($initiative['badge']))
                                <x-ui.badge variant="purple">{{ $initiative['badge'] }}</x-ui.badge>
                            @endif
                            <x-ui.badge variant="primary">{{ $initiative['category_label'] }}</x-ui.badge>
                        </div>
                        <h1 class="mt-3 cyra-display">{{ $initiative['title'] }}</h1>
                        <p class="mt-2 text-lg font-medium text-cyra-accent">{{ $initiative['tagline'] }}</p>
                        <p class="mt-3 max-w-3xl text-base leading-relaxed text-cyra-muted">{{ $initiative['summary'] }}</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="cyra-container cyra-section">
            <div class="grid gap-10 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h2 class="cyra-heading-2">Program Overview</h2>
                    <p class="mt-4 text-base leading-relaxed text-cyra-muted">{{ $initiative['description'] }}</p>

                    <h2 class="cyra-heading-2 mt-10">Focus Areas</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ($initiative['focus_areas'] ?? [] as $area)
                            <article class="cyra-chip">
                                {{ $area }}
                            </article>
                        @endforeach
                    </div>

                    <h2 class="cyra-heading-2 mt-10">Deliverables</h2>
                    <ul class="mt-6 space-y-3">
                        @foreach ($initiative['deliverables'] ?? [] as $deliverable)
                            <li class="flex items-start gap-3 rounded-lg border border-cyra-primary/20 bg-cyra-primary/5 px-4 py-3 text-sm text-cyra-text">
                                <span class="mt-0.5 text-cyra-success">✓</span>
                                {{ $deliverable }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <aside class="cyra-card p-6">
                    <h2 class="text-lg font-semibold text-cyra-text">Program Details</h2>

                    @if (! empty($initiative['timeline']))
                        <div class="mt-4">
                            <p class="text-xs uppercase tracking-wide text-cyra-muted">Typical Timeline</p>
                            <p class="mt-1 text-sm font-medium text-cyra-text">{{ $initiative['timeline'] }}</p>
                        </div>
                    @endif

                    <div class="mt-6 border-t border-cyra-border/60 pt-4">
                        <p class="text-xs uppercase tracking-wide text-cyra-muted">Category</p>
                        <p class="mt-1 text-sm font-medium text-cyra-text">{{ $initiative['category_label'] }}</p>
                    </div>
                </aside>
            </div>

            <div class="mt-12 flex flex-wrap gap-3">
                <x-ui.button href="{{ route('contact') }}">Book a Lab Session</x-ui.button>
                <x-ui.button href="{{ route('solutions') }}" variant="secondary">Explore Solutions</x-ui.button>
                <x-ui.button href="{{ route('innovation-lab') }}" variant="secondary">Back to Innovation Lab</x-ui.button>
            </div>
        </div>
    </main>
@endsection
