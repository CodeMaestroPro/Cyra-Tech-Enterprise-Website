@extends('layouts.app')

@section('title', $solutions['seo']['title'] ?? 'Solutions')

@push('head')
    <meta name="description" content="{{ $solutions['seo']['description'] ?? '' }}">
    @if (! empty($solutions['seo']['keywords']))
        <meta name="keywords" content="{{ implode(', ', $solutions['seo']['keywords']) }}">
    @endif
    <meta property="og:title" content="{{ $solutions['seo']['title'] ?? 'Solutions' }}">
    <meta property="og:description" content="{{ $solutions['seo']['description'] ?? '' }}">
@endpush

@section('content')
    @php
        $hero = $solutions['hero'] ?? [];
        $categories = $solutions['categories'] ?? [];
        $offerings = $solutions['offerings'] ?? [];
        $process = $solutions['process'] ?? [];
        $cta = $solutions['cta'] ?? [];
    @endphp

    <main id="main-content">
        <section class="border-b border-cyra-border/60 bg-cyra-navy/30">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Solutions'],
                ]" />

                @if (! empty($hero['eyebrow']))
                    <p class="cyra-caption mt-6">{{ $hero['eyebrow'] }}</p>
                @endif
                <h1 class="mt-3 cyra-display">{{ $hero['title'] ?? 'Solutions' }}</h1>
                @if (! empty($hero['description']))
                    <p class="mt-4 max-w-3xl text-lg leading-relaxed text-cyra-muted">{{ $hero['description'] }}</p>
                @endif
            </div>
        </section>

        <section class="py-16" aria-labelledby="solutions-catalog-title">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-ui.section-heading
                    eyebrow="Solution Catalog"
                    title="Modular offerings for every stage of transformation"
                    id="solutions-catalog-title"
                    class="mb-8"
                />

                <div class="mb-8 flex flex-wrap gap-2" role="tablist" aria-label="Filter solutions by category">
                    @foreach ($categories as $category)
                        <button
                            type="button"
                            @class([
                                'rounded-full border px-4 py-2 text-sm font-medium transition-colors',
                                'border-cyra-primary bg-cyra-primary/15 text-cyra-text' => $category['slug'] === 'all',
                                'border-cyra-border text-cyra-muted hover:border-cyra-primary/40 hover:text-cyra-text' => $category['slug'] !== 'all',
                            ])
                            data-solution-filter="{{ $category['slug'] }}"
                            role="tab"
                            aria-selected="{{ $category['slug'] === 'all' ? 'true' : 'false' }}"
                        >
                            {{ $category['label'] }}
                        </button>
                    @endforeach
                </div>

                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3" data-solution-grid>
                    @foreach ($offerings as $offering)
                        <x-solutions.offering-card :offering="$offering" />
                    @endforeach
                </div>
            </div>
        </section>

        <section class="border-t border-cyra-border/60 bg-cyra-navy/20 py-16" aria-labelledby="solutions-process-title">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-ui.section-heading
                    :eyebrow="$process['eyebrow'] ?? null"
                    :title="$process['title'] ?? ''"
                    :description="$process['description'] ?? null"
                    id="solutions-process-title"
                    class="mb-10"
                />

                <ol class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    @foreach ($process['steps'] ?? [] as $index => $step)
                        <li class="cyra-card p-6">
                            <p class="text-sm font-semibold text-cyra-accent">Step {{ $index + 1 }}</p>
                            <h3 class="mt-2 text-lg font-semibold text-cyra-text">{{ $step['title'] }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-cyra-muted">{{ $step['description'] }}</p>
                        </li>
                    @endforeach
                </ol>
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
