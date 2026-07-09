@extends('layouts.app')

@section('title', $program['title'].' | Community')

@push('head')
    <meta name="description" content="{{ $program['summary'] }}">
    <meta property="og:title" content="{{ $program['title'] }} | Cyra-Tech Community">
    <meta property="og:description" content="{{ $program['summary'] }}">
@endpush

@section('content')
    <main id="main-content">
        <section class="cyra-page-hero">
            <div class="cyra-page-hero-glow" aria-hidden="true"></div>
            <div class="cyra-container relative cyra-section-hero-inner">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Community', 'href' => route('community')],
                    ['label' => $program['title']],
                ]" />

                <div class="mt-6 flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
                        <x-homepage.icon :name="$program['icon'] ?? 'spark'" />
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            @if (! empty($program['badge']))
                                <x-ui.badge variant="purple">{{ $program['badge'] }}</x-ui.badge>
                            @endif
                            <x-ui.badge variant="primary">{{ $program['category_label'] }}</x-ui.badge>
                        </div>
                        <h1 class="mt-3 cyra-display">{{ $program['title'] }}</h1>
                        <p class="mt-2 text-lg font-medium text-cyra-accent">{{ $program['tagline'] }}</p>
                        <p class="mt-3 max-w-3xl text-base leading-relaxed text-cyra-muted">{{ $program['summary'] }}</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="cyra-container cyra-section">
            <div class="grid gap-10 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h2 class="cyra-heading-2">Program Overview</h2>
                    <p class="mt-4 text-base leading-relaxed text-cyra-muted">{{ $program['description'] }}</p>

                    <h2 class="cyra-heading-2 mt-10">Member Benefits</h2>
                    <ul class="mt-6 space-y-3">
                        @foreach ($program['benefits'] ?? [] as $benefit)
                            <li class="flex items-start gap-3 cyra-chip">
                                <span class="mt-0.5 text-cyra-success">✓</span>
                                {{ $benefit }}
                            </li>
                        @endforeach
                    </ul>

                    <h2 class="cyra-heading-2 mt-10">Activities</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ($program['activities'] ?? [] as $activity)
                            <article class="rounded-lg border border-cyra-primary/20 bg-cyra-primary/5 px-4 py-3 text-sm text-cyra-text">
                                {{ $activity }}
                            </article>
                        @endforeach
                    </div>
                </div>

                <aside class="cyra-card p-6">
                    <h2 class="text-lg font-semibold text-cyra-text">Program Details</h2>

                    @if (! empty($program['membership']))
                        <div class="mt-4">
                            <p class="text-xs uppercase tracking-wide text-cyra-muted">Membership</p>
                            <p class="mt-1 text-sm font-medium text-cyra-text">{{ $program['membership'] }}</p>
                        </div>
                    @endif

                    @if (! empty($program['schedule']))
                        <div class="mt-6 border-t border-cyra-border/60 pt-4">
                            <p class="text-xs uppercase tracking-wide text-cyra-muted">Schedule</p>
                            <p class="mt-1 text-sm font-medium text-cyra-text">{{ $program['schedule'] }}</p>
                        </div>
                    @endif
                </aside>
            </div>

            <div class="mt-12 flex flex-wrap gap-3">
                <x-ui.button href="{{ route('contact') }}">Join the Community</x-ui.button>
                <x-ui.button href="{{ route('innovation-lab') }}" variant="secondary">Explore Innovation Lab</x-ui.button>
                <x-ui.button href="{{ route('community') }}" variant="secondary">Back to Community</x-ui.button>
            </div>
        </div>
    </main>
@endsection
