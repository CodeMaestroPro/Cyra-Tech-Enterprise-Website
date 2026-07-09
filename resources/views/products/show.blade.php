@extends('layouts.app')

@section('title', $product['title'].' | Products')

@push('head')
    <meta name="description" content="{{ $product['summary'] }}">
    <meta property="og:title" content="{{ $product['title'] }} | Cyra-Tech Products">
    <meta property="og:description" content="{{ $product['summary'] }}">
@endpush

@section('content')
    <main id="main-content">
        <section class="cyra-page-hero">
            <div class="cyra-page-hero-glow" aria-hidden="true"></div>
            <div class="cyra-container relative cyra-section-hero-inner">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Products', 'href' => route('products')],
                    ['label' => $product['title']],
                ]" />

                <div class="mt-6 flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
                        <x-homepage.icon :name="$product['icon'] ?? 'spark'" />
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            @if (! empty($product['badge']))
                                <x-ui.badge variant="purple">{{ $product['badge'] }}</x-ui.badge>
                            @endif
                            <x-ui.badge variant="primary">{{ $product['category_label'] }}</x-ui.badge>
                        </div>
                        <h1 class="mt-3 cyra-display">{{ $product['title'] }}</h1>
                        <p class="mt-2 text-lg font-medium text-cyra-accent">{{ $product['tagline'] }}</p>
                        <p class="mt-3 max-w-3xl text-base leading-relaxed text-cyra-muted">{{ $product['summary'] }}</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="cyra-container cyra-section">
            <div class="grid gap-10 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h2 class="cyra-heading-2">Product Overview</h2>
                    <p class="mt-4 text-base leading-relaxed text-cyra-muted">{{ $product['description'] }}</p>

                    <h2 class="cyra-heading-2 mt-10">Key Features</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ($product['features'] ?? [] as $feature)
                            <article class="cyra-chip">
                                {{ $feature }}
                            </article>
                        @endforeach
                    </div>
                </div>

                <aside class="cyra-card p-6">
                    <h2 class="text-lg font-semibold text-cyra-text">Use Cases</h2>
                    <ul class="mt-4 space-y-2">
                        @foreach ($product['use_cases'] ?? [] as $useCase)
                            <li class="text-sm text-cyra-muted">• {{ $useCase }}</li>
                        @endforeach
                    </ul>
                </aside>
            </div>

            <div class="mt-12 flex flex-wrap gap-3">
                <x-ui.button href="{{ route('contact') }}">Book a Demo</x-ui.button>
                <x-ui.button href="{{ route('products') }}" variant="secondary">Back to Products</x-ui.button>
            </div>
        </div>
    </main>
@endsection
