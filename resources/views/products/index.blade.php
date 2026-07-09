@extends('layouts.app')

@section('title', $catalog['seo']['title'] ?? 'Products')

@push('head')
    <meta name="description" content="{{ $catalog['seo']['description'] ?? '' }}">
    @if (! empty($catalog['seo']['keywords']))
        <meta name="keywords" content="{{ implode(', ', $catalog['seo']['keywords']) }}">
    @endif
    <meta property="og:title" content="{{ $catalog['seo']['title'] ?? 'Products' }}">
    <meta property="og:description" content="{{ $catalog['seo']['description'] ?? '' }}">
@endpush

@section('content')
    @php
        $hero = $catalog['hero'] ?? [];
        $categories = $catalog['categories'] ?? [];
        $products = $catalog['products'] ?? [];
        $ecosystem = $catalog['ecosystem'] ?? [];
        $cta = $catalog['cta'] ?? [];
    @endphp

    <main id="main-content">
        <section class="cyra-page-hero">
            <div class="cyra-page-hero-glow" aria-hidden="true"></div>
            <div class="cyra-container relative cyra-section-hero-inner">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Products'],
                ]" />

                @if (! empty($hero['eyebrow']))
                    <p class="cyra-hero-badge mt-6">{{ $hero['eyebrow'] }}</p>
                @endif
                <h1 class="mt-3 cyra-display">{{ $hero['title'] ?? 'Products' }}</h1>
                @if (! empty($hero['description']))
                    <p class="mt-4 max-w-3xl text-lg leading-relaxed text-cyra-muted">{{ $hero['description'] }}</p>
                @endif
            </div>
        </section>

        <section class="cyra-section" aria-labelledby="products-catalog-title">
            <div class="cyra-container">
                <x-ui.section-heading
                    eyebrow="Product Catalog"
                    title="Composable platform products for every team"
                    id="products-catalog-title"
                    class="cyra-section-heading"
                />

                <div class="mb-8 flex flex-wrap gap-2" role="tablist" aria-label="Filter products by category">
                    @foreach ($categories as $category)
                        <button
                            type="button"
                            @class([
                                'cyra-filter-pill',
                                'cyra-filter-pill-active' => $category['slug'] === 'all',
                                'cyra-filter-pill-inactive' => $category['slug'] !== 'all',
                            ])
                            data-product-filter="{{ $category['slug'] }}"
                            role="tab"
                            aria-selected="{{ $category['slug'] === 'all' ? 'true' : 'false' }}"
                        >
                            {{ $category['label'] }}
                        </button>
                    @endforeach
                </div>

                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3" data-product-grid>
                    @foreach ($products as $product)
                        <x-products.product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>

        <section class="cyra-section cyra-section-soft border-t border-cyra-border" aria-labelledby="products-ecosystem-title">
            <div class="cyra-container">
                <x-ui.section-heading
                    :eyebrow="$ecosystem['eyebrow'] ?? null"
                    :title="$ecosystem['title'] ?? ''"
                    :description="$ecosystem['description'] ?? null"
                    id="products-ecosystem-title"
                    class="cyra-section-heading"
                />

                <ul class="grid gap-4 md:grid-cols-2">
                    @foreach ($ecosystem['points'] ?? [] as $point)
                        <li class="flex items-start gap-3 cyra-chip">
                            <span class="mt-0.5 text-cyra-accent">✓</span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        @if (! empty($cta))
            <section class="cyra-section-footer">
                <div class="cyra-container">
                    <div class="cyra-cta-premium px-6 py-10 sm:px-10">
                        <div class="cyra-cta-premium-glow" aria-hidden="true"></div>
                        <div class="relative">
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
                </div>
            </section>
        @endif
    </main>
@endsection
