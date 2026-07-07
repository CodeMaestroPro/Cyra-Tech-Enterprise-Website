@extends('layouts.app')

@section('title', $vertical['title'].' | Industries')

@push('head')
    <meta name="description" content="{{ $vertical['summary'] }}">
    <meta property="og:title" content="{{ $vertical['title'] }} | Cyra-Tech Industries">
    <meta property="og:description" content="{{ $vertical['summary'] }}">
@endpush

@section('content')
    <main id="main-content">
        <section class="border-b border-cyra-border/60 bg-cyra-navy/30">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Industries', 'href' => route('industries')],
                    ['label' => $vertical['title']],
                ]" />

                <div class="mt-6 flex items-start gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-cyra-primary/15 text-cyra-accent">
                        <x-homepage.icon :name="$vertical['icon'] ?? 'spark'" />
                    </div>
                    <div>
                        <x-ui.badge variant="primary">{{ $vertical['category_label'] }}</x-ui.badge>
                        <h1 class="mt-3 cyra-display">{{ $vertical['title'] }}</h1>
                        <p class="mt-2 text-lg font-medium text-cyra-accent">{{ $vertical['tagline'] }}</p>
                        <p class="mt-3 max-w-3xl text-base leading-relaxed text-cyra-muted">{{ $vertical['summary'] }}</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h2 class="cyra-heading-2">Industry Overview</h2>
                    <p class="mt-4 text-base leading-relaxed text-cyra-muted">{{ $vertical['description'] }}</p>

                    <h2 class="cyra-heading-2 mt-10">Key Challenges</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ($vertical['challenges'] ?? [] as $challenge)
                            <article class="rounded-lg border border-cyra-border/70 bg-cyra-surface/40 px-4 py-3 text-sm text-cyra-text">
                                {{ $challenge }}
                            </article>
                        @endforeach
                    </div>

                    <h2 class="cyra-heading-2 mt-10">Cyra Capabilities</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ($vertical['capabilities'] ?? [] as $capability)
                            <article class="rounded-lg border border-cyra-primary/20 bg-cyra-primary/5 px-4 py-3 text-sm text-cyra-text">
                                {{ $capability }}
                            </article>
                        @endforeach
                    </div>
                </div>

                <aside class="cyra-card p-6">
                    <h2 class="text-lg font-semibold text-cyra-text">Compliance Frameworks</h2>
                    <ul class="mt-4 space-y-2">
                        @foreach ($vertical['compliance'] ?? [] as $framework)
                            <li class="text-sm text-cyra-muted">• {{ $framework }}</li>
                        @endforeach
                    </ul>
                </aside>
            </div>

            <div class="mt-12 flex flex-wrap gap-3">
                <x-ui.button href="{{ route('contact') }}">Talk to an Advisor</x-ui.button>
                <x-ui.button href="{{ route('solutions') }}" variant="secondary">Explore Solutions</x-ui.button>
                <x-ui.button href="{{ route('industries') }}" variant="secondary">Back to Industries</x-ui.button>
            </div>
        </div>
    </main>
@endsection
