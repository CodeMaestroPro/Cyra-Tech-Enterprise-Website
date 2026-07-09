@extends('layouts.app')

@section('title', $offering['title'].' | Solutions')

@push('head')
    <meta name="description" content="{{ $offering['summary'] }}">
    <meta property="og:title" content="{{ $offering['title'] }} | Cyra-Tech Solutions">
    <meta property="og:description" content="{{ $offering['summary'] }}">
@endpush

@section('content')
    <main id="main-content">
        <section class="cyra-page-hero">
            <div class="cyra-page-hero-glow" aria-hidden="true"></div>
            <div class="cyra-container relative cyra-section-hero-inner">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Solutions', 'href' => route('solutions')],
                    ['label' => $offering['title']],
                ]" />

                <div class="mt-6 flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
                        <x-homepage.icon :name="$offering['icon'] ?? 'spark'" />
                    </div>
                    <div>
                        <x-ui.badge variant="primary">{{ $offering['category_label'] }}</x-ui.badge>
                        <h1 class="mt-3 cyra-display">{{ $offering['title'] }}</h1>
                        <p class="mt-3 max-w-3xl text-lg leading-relaxed text-cyra-muted">{{ $offering['summary'] }}</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="cyra-container cyra-section">
            <div class="grid gap-10 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h2 class="cyra-heading-2">Overview</h2>
                    <p class="mt-4 text-base leading-relaxed text-cyra-muted">{{ $offering['description'] }}</p>
                </div>

                <aside class="cyra-card p-6">
                    <h2 class="text-lg font-semibold text-cyra-text">Expected Outcomes</h2>
                    <ul class="mt-4 space-y-2">
                        @foreach ($offering['outcomes'] ?? [] as $outcome)
                            <li class="text-sm text-cyra-success">• {{ $outcome }}</li>
                        @endforeach
                    </ul>
                </aside>
            </div>

            <section class="mt-12">
                <h2 class="cyra-heading-2">Core Capabilities</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach ($offering['capabilities'] ?? [] as $capability)
                        <article class="cyra-chip">
                            {{ $capability }}
                        </article>
                    @endforeach
                </div>
            </section>

            <div class="mt-12 flex flex-wrap gap-3">
                <x-ui.button href="{{ route('contact') }}">Request Consultation</x-ui.button>
                <x-ui.button href="{{ route('solutions') }}" variant="secondary">Back to Solutions</x-ui.button>
            </div>
        </div>
    </main>
@endsection
