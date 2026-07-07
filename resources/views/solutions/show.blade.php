@extends('layouts.app')

@section('title', $offering['title'].' | Solutions')

@push('head')
    <meta name="description" content="{{ $offering['summary'] }}">
    <meta property="og:title" content="{{ $offering['title'] }} | Cyra-Tech Solutions">
    <meta property="og:description" content="{{ $offering['summary'] }}">
@endpush

@section('content')
    <main id="main-content">
        <section class="border-b border-cyra-border/60 bg-cyra-navy/30">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Solutions', 'href' => route('solutions')],
                    ['label' => $offering['title']],
                ]" />

                <div class="mt-6 flex items-start gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-cyra-primary/15 text-cyra-accent">
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

        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
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
                        <article class="rounded-lg border border-cyra-border/70 bg-cyra-surface/40 px-4 py-3 text-sm text-cyra-text">
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
