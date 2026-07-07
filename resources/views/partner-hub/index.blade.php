@extends('layouts.app')

@section('title', $catalog['seo']['title'] ?? 'Partner Hub')

@push('head')
    <meta name="description" content="{{ $catalog['seo']['description'] ?? '' }}">
    @if (! empty($catalog['seo']['keywords']))
        <meta name="keywords" content="{{ implode(', ', $catalog['seo']['keywords']) }}">
    @endif
    <meta property="og:title" content="{{ $catalog['seo']['title'] ?? 'Partner Hub' }}">
    <meta property="og:description" content="{{ $catalog['seo']['description'] ?? '' }}">
@endpush

@section('content')
    @php
        $hero = $catalog['hero'] ?? [];
        $categories = $catalog['categories'] ?? [];
        $programs = $catalog['programs'] ?? [];
        $ecosystem = $catalog['ecosystem'] ?? [];
        $cta = $catalog['cta'] ?? [];
    @endphp

    <main id="main-content">
        <section class="border-b border-cyra-border/60 bg-cyra-navy/30">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Partner Hub'],
                ]" />

                @if (! empty($hero['eyebrow']))
                    <p class="cyra-caption mt-6">{{ $hero['eyebrow'] }}</p>
                @endif
                <h1 class="mt-3 cyra-display">{{ $hero['title'] ?? 'Partner Hub' }}</h1>
                @if (! empty($hero['description']))
                    <p class="mt-4 max-w-3xl text-lg leading-relaxed text-cyra-muted">{{ $hero['description'] }}</p>
                @endif
            </div>
        </section>

        <section class="py-16" aria-labelledby="partner-hub-catalog-title">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-ui.section-heading
                    eyebrow="Partner Programs"
                    title="Find the right partnership model for your organization"
                    id="partner-hub-catalog-title"
                    class="mb-8"
                />

                <div class="mb-8 flex flex-wrap gap-2" role="tablist" aria-label="Filter programs by category">
                    @foreach ($categories as $category)
                        <button
                            type="button"
                            @class([
                                'rounded-full border px-4 py-2 text-sm font-medium transition-colors',
                                'border-cyra-primary bg-cyra-primary/15 text-cyra-text' => $category['slug'] === 'all',
                                'border-cyra-border text-cyra-muted hover:border-cyra-primary/40 hover:text-cyra-text' => $category['slug'] !== 'all',
                            ])
                            data-partner-filter="{{ $category['slug'] }}"
                            role="tab"
                            aria-selected="{{ $category['slug'] === 'all' ? 'true' : 'false' }}"
                        >
                            {{ $category['label'] }}
                        </button>
                    @endforeach
                </div>

                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3" data-partner-grid>
                    @foreach ($programs as $program)
                        <x-partner-hub.program-card :program="$program" />
                    @endforeach
                </div>
            </div>
        </section>

        <section class="border-t border-cyra-border/60 bg-cyra-navy/20 py-16" aria-labelledby="partner-hub-ecosystem-title">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-ui.section-heading
                    :eyebrow="$ecosystem['eyebrow'] ?? null"
                    :title="$ecosystem['title'] ?? ''"
                    :description="$ecosystem['description'] ?? null"
                    id="partner-hub-ecosystem-title"
                    class="mb-8"
                />

                <ul class="grid gap-4 md:grid-cols-2">
                    @foreach ($ecosystem['points'] ?? [] as $point)
                        <li class="flex items-start gap-3 rounded-lg border border-cyra-border/70 bg-cyra-surface/40 px-4 py-3 text-sm text-cyra-text">
                            <span class="mt-0.5 text-cyra-accent">✓</span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

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
                                <x-ui.button href="{{ route($cta['action']['route'], ['inquiry' => 'partnership']) }}">
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
