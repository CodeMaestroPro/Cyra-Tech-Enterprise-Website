@extends('layouts.app')

@section('title', $page['seo']['title'] ?? $page['title'])

@push('head')
    <meta name="description" content="{{ $page['seo']['description'] ?? $page['description'] ?? $page['excerpt'] }}">
    @if (! empty($page['seo']['keywords']))
        <meta name="keywords" content="{{ implode(', ', $page['seo']['keywords']) }}">
    @endif
    <meta property="og:title" content="{{ $page['seo']['title'] ?? $page['title'] }}">
    <meta property="og:description" content="{{ $page['seo']['description'] ?? $page['description'] ?? $page['excerpt'] }}">
    <meta property="og:type" content="website">
@endpush

@section('content')
    <main id="main-content">
        <section class="cyra-page-hero">
            <div class="cyra-page-hero-glow" aria-hidden="true"></div>
            <div class="cyra-container relative cyra-section-hero-inner">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => $page['title']],
                ]" />

                @if (! empty($page['eyebrow']))
                    <p class="cyra-hero-badge mt-6">{{ $page['eyebrow'] }}</p>
                @endif
                <h1 class="mt-3 cyra-display">{{ $page['title'] }}</h1>
                @if (! empty($page['description']))
                    <p class="mt-4 max-w-3xl text-lg leading-relaxed text-cyra-muted">{{ $page['description'] }}</p>
                @endif
            </div>
        </section>

        <div class="cyra-container cyra-section">
            <div class="cyra-section-stack">
            @foreach ($page['blocks'] as $block)
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
    </main>
@endsection
