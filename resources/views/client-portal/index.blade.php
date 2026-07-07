@extends('layouts.app')

@section('title', $portal['seo']['title'] ?? 'Client Portal')

@push('head')
    <meta name="description" content="{{ $portal['seo']['description'] ?? '' }}">
    @if (! empty($portal['seo']['keywords']))
        <meta name="keywords" content="{{ implode(', ', $portal['seo']['keywords']) }}">
    @endif
@endpush

@section('content')
    @php
        $hero = $portal['hero'] ?? [];
        $features = $portal['features'] ?? [];
        $security = $portal['security'] ?? [];
        $cta = $portal['cta'] ?? [];
    @endphp

    <main id="main-content">
        <section class="border-b border-cyra-border/60 bg-cyra-navy/30">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Client Portal'],
                ]" />

                @if (! empty($hero['eyebrow']))
                    <p class="cyra-caption mt-6">{{ $hero['eyebrow'] }}</p>
                @endif
                <h1 class="mt-3 cyra-display">{{ $hero['title'] ?? 'Client Portal' }}</h1>
                @if (! empty($hero['description']))
                    <p class="mt-4 max-w-3xl text-lg leading-relaxed text-cyra-muted">{{ $hero['description'] }}</p>
                @endif
            </div>
        </section>

        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-ui.section-heading
                    :eyebrow="$features['eyebrow'] ?? null"
                    :title="$features['title'] ?? ''"
                    :description="$features['description'] ?? null"
                    class="mb-8"
                />

                <ul class="grid gap-4 md:grid-cols-2">
                    @foreach ($features['points'] ?? [] as $point)
                        <li class="flex items-start gap-3 rounded-lg border border-cyra-border/70 bg-cyra-surface/40 px-4 py-3 text-sm text-cyra-text">
                            <span class="mt-0.5 text-cyra-accent">✓</span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        @if (! empty($security))
            <section class="border-t border-cyra-border/60 bg-cyra-navy/20 py-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h2 class="cyra-heading-2">{{ $security['title'] ?? 'Security' }}</h2>
                    @if (! empty($security['description']))
                        <p class="mt-4 max-w-3xl text-base leading-relaxed text-cyra-muted">{{ $security['description'] }}</p>
                    @endif
                    <ul class="mt-8 grid gap-4 md:grid-cols-3">
                        @foreach ($security['points'] ?? [] as $point)
                            <li class="rounded-lg border border-cyra-primary/20 bg-cyra-primary/5 px-4 py-3 text-sm text-cyra-text">
                                {{ $point }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif

        @if (! empty($cta))
            <section class="pb-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="rounded-2xl border border-cyra-primary/30 bg-gradient-to-r from-cyra-primary/15 via-cyra-navy to-cyra-accent/10 px-6 py-10 sm:px-10">
                        <h2 class="cyra-heading-2">{{ $cta['title'] ?? '' }}</h2>
                        @if (! empty($cta['description']))
                            <p class="mt-4 max-w-2xl text-base leading-relaxed text-cyra-muted">{{ $cta['description'] }}</p>
                        @endif
                        @if (! empty($cta['action']))
                            <div class="mt-6">
                                <x-ui.button href="{{ route($cta['action']['route']) }}">
                                    {{ $cta['action']['label'] }}
                                </x-ui.button>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif
    </main>
@endsection
