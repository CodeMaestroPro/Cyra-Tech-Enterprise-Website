@extends('layouts.app')

@section('title', $leadership['seo']['title'] ?? 'Leadership')

@push('head')
    <meta name="description" content="{{ $leadership['seo']['description'] ?? '' }}">
    @if (! empty($leadership['seo']['keywords']))
        <meta name="keywords" content="{{ implode(', ', $leadership['seo']['keywords']) }}">
    @endif
    <meta property="og:title" content="{{ $leadership['seo']['title'] ?? 'Leadership' }}">
    <meta property="og:description" content="{{ $leadership['seo']['description'] ?? '' }}">
    <meta property="og:type" content="website">
@endpush

@section('content')
    @php
        $hero = $leadership['hero'] ?? [];
        $governance = $leadership['governance'] ?? [];
        $cta = $leadership['cta'] ?? [];
        $executives = $leadership['executives'] ?? [];
        $profiles = $leadership['profiles'] ?? [];
        $extended = collect($profiles)->where('tier', 'extended')->values();
    @endphp

    <main id="main-content">
        <section class="border-b border-cyra-border/60 bg-cyra-navy/30">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'About', 'href' => route('about')],
                    ['label' => 'Leadership'],
                ]" />

                @if (! empty($hero['eyebrow']))
                    <p class="cyra-caption mt-6">{{ $hero['eyebrow'] }}</p>
                @endif
                <h1 class="mt-3 cyra-display">{{ $hero['title'] ?? 'Leadership' }}</h1>
                @if (! empty($hero['description']))
                    <p class="mt-4 max-w-3xl text-lg leading-relaxed text-cyra-muted">{{ $hero['description'] }}</p>
                @endif
            </div>
        </section>

        <section class="py-16" aria-labelledby="executive-team-title">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-ui.section-heading
                    eyebrow="Executive Team"
                    title="Leaders guiding Cyra-Tech forward"
                    description="Our executive team partners directly with clients on strategy, delivery, and long-term innovation."
                    id="executive-team-title"
                    class="mb-10"
                />

                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    @foreach ($executives as $profile)
                        <x-leadership.profile-card :profile="$profile" />
                    @endforeach
                </div>
            </div>
        </section>

        @if ($extended->isNotEmpty())
            <section class="border-t border-cyra-border/60 bg-cyra-navy/20 py-16" aria-labelledby="extended-leadership-title">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <x-ui.section-heading
                        eyebrow="Extended Leadership"
                        title="Functional leaders supporting enterprise delivery"
                        id="extended-leadership-title"
                        class="mb-10"
                    />

                    <div class="grid gap-6 md:grid-cols-2">
                        @foreach ($extended as $profile)
                            <x-leadership.profile-card :profile="$profile" />
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section class="border-t border-cyra-border/60 py-16" aria-labelledby="governance-title">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-ui.section-heading
                    :eyebrow="$governance['eyebrow'] ?? null"
                    :title="$governance['title'] ?? ''"
                    :description="$governance['description'] ?? null"
                    id="governance-title"
                    class="mb-10"
                />

                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($governance['pillars'] ?? [] as $pillar)
                        <article class="cyra-card p-6">
                            <h3 class="text-lg font-semibold text-cyra-text">{{ $pillar['title'] }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-cyra-muted">{{ $pillar['description'] }}</p>
                        </article>
                    @endforeach
                </div>
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

    <div
        id="leadership-profile-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="leadership-profile-modal-title"
        data-cyra-modal
    >
        <div class="cyra-card w-full max-w-lg p-6">
            <div class="mb-4 flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div
                        id="leadership-profile-modal-avatar"
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-cyra-primary to-cyra-accent text-lg font-bold text-white"
                    ></div>
                    <div>
                        <h2 id="leadership-profile-modal-title" class="cyra-heading-3"></h2>
                        <p id="leadership-profile-modal-role" class="mt-1 text-sm text-cyra-accent"></p>
                    </div>
                </div>
                <button
                    type="button"
                    class="rounded-lg p-2 text-cyra-muted hover:bg-cyra-navy hover:text-cyra-text"
                    data-cyra-modal-close
                    aria-label="Close profile dialog"
                >
                    ✕
                </button>
            </div>
            <p id="leadership-profile-modal-bio" class="text-sm leading-relaxed text-cyra-muted"></p>
            <div id="leadership-profile-modal-links" class="mt-6 flex flex-wrap gap-3"></div>
        </div>
    </div>
@endsection
