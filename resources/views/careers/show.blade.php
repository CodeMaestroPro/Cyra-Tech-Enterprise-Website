@extends('layouts.app')

@section('title', $opening['title'].' | Careers')

@push('head')
    <meta name="description" content="{{ $opening['summary'] }}">
    <meta property="og:title" content="{{ $opening['title'] }} | Cyra-Tech Careers">
    <meta property="og:description" content="{{ $opening['summary'] }}">
@endpush

@section('content')
    <main id="main-content">
        <section class="cyra-page-hero">
            <div class="cyra-page-hero-glow" aria-hidden="true"></div>
            <div class="cyra-container relative cyra-section-hero-inner">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Careers', 'href' => route('careers')],
                    ['label' => $opening['title']],
                ]" />

                <div class="mt-6 flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
                        <x-homepage.icon :name="$opening['icon'] ?? 'spark'" />
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            @if (! empty($opening['badge']))
                                <x-ui.badge variant="purple">{{ $opening['badge'] }}</x-ui.badge>
                            @endif
                            <x-ui.badge variant="primary">{{ $opening['category_label'] }}</x-ui.badge>
                        </div>
                        <h1 class="mt-3 cyra-display">{{ $opening['title'] }}</h1>
                        <p class="mt-2 text-lg font-medium text-cyra-accent">{{ $opening['tagline'] }}</p>
                        <div class="mt-3 flex flex-wrap gap-4 text-sm text-cyra-muted">
                            <span>{{ $opening['department'] }}</span>
                            <span>{{ $opening['location'] }}</span>
                            <span>{{ $opening['work_type'] }}</span>
                            @if (! empty($opening['experience_level']))
                                <span>{{ $opening['experience_level'] }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="cyra-container cyra-section">
            <div class="grid gap-10 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h2 class="cyra-heading-2">Role Overview</h2>
                    <p class="mt-4 text-base leading-relaxed text-cyra-muted">{{ $opening['description'] }}</p>

                    <h2 class="cyra-heading-2 mt-10">Responsibilities</h2>
                    <ul class="mt-6 space-y-3">
                        @foreach ($opening['responsibilities'] ?? [] as $responsibility)
                            <li class="flex items-start gap-3 cyra-chip">
                                <span class="mt-0.5 text-cyra-success">✓</span>
                                {{ $responsibility }}
                            </li>
                        @endforeach
                    </ul>

                    <h2 class="cyra-heading-2 mt-10">Requirements</h2>
                    <ul class="mt-6 space-y-3">
                        @foreach ($opening['requirements'] ?? [] as $requirement)
                            <li class="flex items-start gap-3 rounded-lg border border-cyra-primary/20 bg-cyra-primary/5 px-4 py-3 text-sm text-cyra-text">
                                <span class="mt-0.5 text-cyra-accent">•</span>
                                {{ $requirement }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <aside class="cyra-card p-6">
                    <h2 class="text-lg font-semibold text-cyra-text">Apply for this role</h2>
                    <p class="mt-4 text-sm leading-relaxed text-cyra-muted">Interested in joining Cyra-Tech? Reach out to our talent team with your resume and a brief note on why this role fits your goals.</p>

                    <div class="mt-6">
                        <x-ui.button href="{{ route('contact') }}" class="w-full justify-center">Apply Now</x-ui.button>
                    </div>
                </aside>
            </div>

            <div class="mt-12 flex flex-wrap gap-3">
                <x-ui.button href="{{ route('contact') }}">Contact Talent Team</x-ui.button>
                <x-ui.button href="{{ route('careers') }}" variant="secondary">Back to Careers</x-ui.button>
            </div>
        </div>
    </main>
@endsection
