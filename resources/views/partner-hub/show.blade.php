@extends('layouts.app')

@section('title', $program['title'].' | Partner Hub')

@push('head')
    <meta name="description" content="{{ $program['summary'] }}">
    <meta property="og:title" content="{{ $program['title'] }} | Cyra-Tech Partner Hub">
    <meta property="og:description" content="{{ $program['summary'] }}">
@endpush

@section('content')
    <main id="main-content">
        <section class="cyra-page-hero">
            <div class="cyra-page-hero-glow" aria-hidden="true"></div>
            <div class="cyra-container relative cyra-section-hero-inner">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Partner Hub', 'href' => route('partner-hub')],
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
                        <div class="mt-3 flex flex-wrap gap-4 text-sm text-cyra-muted">
                            <span>{{ $program['partner_type'] }}</span>
                            <span>{{ $program['region'] }}</span>
                            <span>{{ $program['engagement_model'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="cyra-container cyra-section">
            <div class="grid gap-10 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h2 class="cyra-heading-2">Program Overview</h2>
                    <p class="mt-4 text-base leading-relaxed text-cyra-muted">{{ $program['description'] }}</p>

                    <h2 class="cyra-heading-2 mt-10">Partner Benefits</h2>
                    <ul class="mt-6 space-y-3">
                        @foreach ($program['benefits'] ?? [] as $benefit)
                            <li class="flex items-start gap-3 cyra-chip">
                                <span class="mt-0.5 text-cyra-success">✓</span>
                                {{ $benefit }}
                            </li>
                        @endforeach
                    </ul>

                    <h2 class="cyra-heading-2 mt-10">Requirements</h2>
                    <ul class="mt-6 space-y-3">
                        @foreach ($program['requirements'] ?? [] as $requirement)
                            <li class="flex items-start gap-3 rounded-lg border border-cyra-primary/20 bg-cyra-primary/5 px-4 py-3 text-sm text-cyra-text">
                                <span class="mt-0.5 text-cyra-accent">•</span>
                                {{ $requirement }}
                            </li>
                        @endforeach
                    </ul>

                    <h2 class="cyra-heading-2 mt-10">Enablement</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ($program['enablement'] ?? [] as $item)
                            <article class="rounded-lg border border-cyra-primary/20 bg-cyra-primary/5 px-4 py-3 text-sm text-cyra-text">
                                {{ $item }}
                            </article>
                        @endforeach
                    </div>
                </div>

                <aside class="cyra-card p-6">
                    <h2 class="text-lg font-semibold text-cyra-text">Apply to this program</h2>
                    <p class="mt-4 text-sm leading-relaxed text-cyra-muted">Ready to explore this partnership model? Contact our partner team with your organization profile and goals.</p>

                    <div class="mt-6">
                        <x-ui.button href="{{ route('contact', ['inquiry' => 'partnership']) }}" class="w-full justify-center">
                            Apply Now
                        </x-ui.button>
                    </div>
                </aside>
            </div>

            <div class="mt-12 flex flex-wrap gap-3">
                <x-ui.button href="{{ route('contact', ['inquiry' => 'partnership']) }}">Contact Partner Team</x-ui.button>
                <x-ui.button href="{{ route('partner-hub') }}" variant="secondary">Back to Partner Hub</x-ui.button>
            </div>
        </div>
    </main>
@endsection
