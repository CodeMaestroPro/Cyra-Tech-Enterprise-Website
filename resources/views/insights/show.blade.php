@extends('layouts.app')

@section('title', $article['title'].' | Insights')

@push('head')
    <meta name="description" content="{{ $article['summary'] }}">
    <meta property="og:title" content="{{ $article['title'] }} | Cyra-Tech Insights">
    <meta property="og:description" content="{{ $article['summary'] }}">
@endpush

@section('content')
    <main id="main-content">
        <section class="border-b border-cyra-border/60 bg-cyra-navy/30">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Insights', 'href' => route('insights')],
                    ['label' => $article['title']],
                ]" />

                <div class="mt-6 flex items-start gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-cyra-primary/15 text-cyra-accent">
                        <x-homepage.icon :name="$article['icon'] ?? 'spark'" />
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            @if (! empty($article['badge']))
                                <x-ui.badge variant="purple">{{ $article['badge'] }}</x-ui.badge>
                            @endif
                            <x-ui.badge variant="primary">{{ $article['category_label'] }}</x-ui.badge>
                        </div>
                        <h1 class="mt-3 cyra-display">{{ $article['title'] }}</h1>
                        <p class="mt-2 text-lg font-medium text-cyra-accent">{{ $article['tagline'] }}</p>
                        <div class="mt-3 flex flex-wrap gap-4 text-sm text-cyra-muted">
                            <span>{{ $article['author'] }}</span>
                            <span>{{ $article['read_time'] }}</span>
                            @if (! empty($article['published_label']))
                                <span>{{ $article['published_label'] }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h2 class="cyra-heading-2">Article Overview</h2>
                    <p class="mt-4 text-base leading-relaxed text-cyra-muted">{{ $article['description'] }}</p>

                    <h2 class="cyra-heading-2 mt-10">Key Topics</h2>
                    <div class="mt-6 flex flex-wrap gap-2">
                        @foreach ($article['topics'] ?? [] as $topic)
                            <x-ui.badge variant="primary">{{ $topic }}</x-ui.badge>
                        @endforeach
                    </div>

                    <h2 class="cyra-heading-2 mt-10">Executive Takeaways</h2>
                    <ul class="mt-6 space-y-3">
                        @foreach ($article['takeaways'] ?? [] as $takeaway)
                            <li class="flex items-start gap-3 rounded-lg border border-cyra-border/70 bg-cyra-surface/40 px-4 py-3 text-sm text-cyra-text">
                                <span class="mt-0.5 text-cyra-success">✓</span>
                                {{ $takeaway }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <aside class="cyra-card p-6">
                    <h2 class="text-lg font-semibold text-cyra-text">About the Author</h2>
                    <p class="mt-4 text-sm leading-relaxed text-cyra-muted">{{ $article['author'] }}</p>

                    @if (! empty($article['read_time']))
                        <div class="mt-6 border-t border-cyra-border/60 pt-4">
                            <p class="text-xs uppercase tracking-wide text-cyra-muted">Reading Time</p>
                            <p class="mt-1 text-sm font-medium text-cyra-text">{{ $article['read_time'] }}</p>
                        </div>
                    @endif
                </aside>
            </div>

            <div class="mt-12 flex flex-wrap gap-3">
                <x-ui.button href="{{ route('contact') }}">Subscribe to Insights</x-ui.button>
                <x-ui.button href="{{ route('community') }}" variant="secondary">Join the Community</x-ui.button>
                <x-ui.button href="{{ route('insights') }}" variant="secondary">Back to Insights</x-ui.button>
            </div>
        </div>
    </main>
@endsection
