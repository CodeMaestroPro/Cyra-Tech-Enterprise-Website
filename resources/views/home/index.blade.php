@extends('layouts.app')

@section('title', $seo['title'] ?? config('cyra.name'))

@push('head')
    <meta name="description" content="{{ $seo['description'] ?? config('cyra.tagline') }}">
    @if (! empty($seo['keywords']))
        <meta name="keywords" content="{{ implode(', ', $seo['keywords']) }}">
    @endif
    <meta property="og:title" content="{{ $seo['title'] ?? config('cyra.name') }}">
    <meta property="og:description" content="{{ $seo['description'] ?? config('cyra.tagline') }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('cyra.name'),
            'description' => $seo['description'] ?? config('cyra.tagline'),
            'url' => url('/'),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <main id="main-content">
        @foreach ($sections as $section)
            @switch($section['type'])
                @case('hero')
                    <x-homepage.hero :section="$section" />
                    @break
                @case('stats')
                    <x-homepage.stats :section="$section" />
                    @break
                @case('logos')
                    <x-homepage.logos :section="$section" />
                    @break
                @case('feature-grid')
                    <x-homepage.feature-grid :section="$section" />
                    @break
                @case('card-grid')
                    <x-homepage.card-grid :section="$section" />
                    @break
                @case('split-feature')
                    <x-homepage.split-feature :section="$section" />
                    @break
                @case('solutions-ecosystem')
                    <x-homepage.solutions-ecosystem :section="$section" />
                    @break
                @case('featured-products')
                    <x-homepage.featured-products :section="$section" />
                    @break
                @case('commitment')
                    <x-homepage.commitment :section="$section" />
                    @break
                @case('cta-band')
                    <x-homepage.cta-band :section="$section" />
                    @break
            @endswitch
        @endforeach
    </main>
@endsection
