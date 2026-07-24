@extends('layouts.app')

@section('title', $page['seo']['title'] ?? $page['title'])

@push('head')
    <meta name="description" content="{{ $page['seo']['description'] ?? $page['description'] }}">
    @if (! empty($page['seo']['keywords']))
        <meta name="keywords" content="{{ implode(', ', $page['seo']['keywords']) }}">
    @endif
    <meta property="og:title" content="{{ $page['seo']['title'] ?? $page['title'] }}">
    <meta property="og:description" content="{{ $page['seo']['description'] ?? $page['description'] }}">
    <meta property="og:type" content="website">
@endpush

@section('content')
    <main id="main-content">
        <section class="cyra-page-hero">
            <div class="cyra-page-hero-glow" aria-hidden="true"></div>
            <div class="cyra-container relative cyra-section-hero-inner">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'About'],
                ]" />

                @if (! empty($page['eyebrow']))
                    <p class="cyra-hero-badge mt-6">{{ $page['eyebrow'] }}</p>
                @endif
                <h1 class="mt-4 max-w-4xl cyra-display">{{ $page['title'] }}</h1>
                @if (! empty($page['description']))
                    <p class="mt-5 max-w-3xl text-lg leading-relaxed text-cyra-muted sm:text-xl">{{ $page['description'] }}</p>
                @endif
            </div>
        </section>

        @foreach ($page['sections'] as $index => $section)
            <section
                id="{{ $section['id'] }}"
                @class([
                    'scroll-mt-28',
                    'border-y border-cyra-border/70 bg-cyra-soft/60' => $index % 2 === 1,
                ])
                @if ($section['show_heading'])
                    aria-labelledby="about-section-{{ $section['id'] }}"
                @endif
            >
                <div class="cyra-container cyra-section">
                    <div class="mx-auto flex max-w-5xl flex-col gap-12 lg:gap-14">
                        @if ($section['show_heading'])
                            <header class="max-w-3xl">
                                @if (! empty($section['eyebrow']))
                                    <p class="cyra-caption">{{ $section['eyebrow'] }}</p>
                                @endif
                                <h2 id="about-section-{{ $section['id'] }}" class="mt-3 cyra-heading-1">{{ $section['title'] }}</h2>
                                @if (! empty($section['description']))
                                    <p class="mt-4 text-base leading-relaxed text-cyra-muted sm:text-lg">{{ $section['description'] }}</p>
                                @endif
                            </header>
                        @endif

                        @foreach ($section['blocks'] as $block)
                            @switch($block['type'])
                                @case('prose')
                                    <x-about.blocks.prose :block="$block" />
                                    @break
                                @case('stats-row')
                                    <x-about.blocks.stats-row :block="$block" />
                                    @break
                                @case('feature-list')
                                    <x-about.blocks.feature-list :block="$block" />
                                    @break
                                @case('quote-cards')
                                    <x-about.blocks.quote-cards :block="$block" />
                                    @break
                                @case('value-grid')
                                    <x-about.blocks.value-grid :block="$block" />
                                    @break
                                @case('timeline')
                                    <x-about.blocks.timeline :block="$block" />
                                    @break
                                @case('cta')
                                    <x-about.blocks.cta :block="$block" />
                                    @break
                            @endswitch
                        @endforeach
                    </div>
                </div>
            </section>
        @endforeach
    </main>
@endsection
