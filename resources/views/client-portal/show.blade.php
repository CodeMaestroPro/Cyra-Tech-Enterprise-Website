@extends('layouts.portal')

@section('title', $engagement['title'])

@section('content')
    <section aria-label="Engagement details">
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'href' => route('client-portal.dashboard')],
            ['label' => $engagement['title']],
        ]" class="mb-6" />

        <header class="mb-8">
            <div class="flex flex-wrap items-center gap-2">
                <x-ui.badge variant="primary">{{ $engagement['phase'] }}</x-ui.badge>
                <x-ui.badge variant="purple">{{ $engagement['status_label'] }}</x-ui.badge>
            </div>
            <h1 class="mt-3 text-2xl font-bold text-cyra-text">{{ $engagement['title'] }}</h1>
            <p class="mt-2 text-base font-medium text-cyra-accent">{{ $engagement['tagline'] }}</p>
            <p class="mt-3 max-w-3xl text-sm leading-relaxed text-cyra-muted">{{ $engagement['summary'] }}</p>
        </header>

        <div class="mb-8 max-w-xl">
            <div class="mb-2 flex items-center justify-between text-sm text-cyra-muted">
                <span>Overall Progress</span>
                <span>{{ $engagement['progress'] }}%</span>
            </div>
            <div class="h-3 overflow-hidden rounded-full bg-cyra-navy">
                <div class="h-full rounded-full bg-cyra-primary" style="width: {{ $engagement['progress'] }}%"></div>
            </div>
        </div>

        <div class="grid gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <h2 class="cyra-heading-2">Program Overview</h2>
                <p class="mt-4 text-base leading-relaxed text-cyra-muted">{{ $engagement['description'] }}</p>

                <h2 class="cyra-heading-2 mt-10">Milestones</h2>
                <ul class="mt-6 space-y-3">
                    @foreach ($engagement['milestones'] ?? [] as $milestone)
                        <li class="flex items-start gap-3 rounded-lg border border-cyra-border/70 bg-cyra-surface/40 px-4 py-3 text-sm text-cyra-text">
                            <span class="mt-0.5 text-cyra-success">✓</span>
                            {{ $milestone }}
                        </li>
                    @endforeach
                </ul>

                <h2 class="cyra-heading-2 mt-10">Deliverables</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach ($engagement['deliverables'] ?? [] as $deliverable)
                        <article class="rounded-lg border border-cyra-primary/20 bg-cyra-primary/5 px-4 py-3 text-sm text-cyra-text">
                            {{ $deliverable }}
                        </article>
                    @endforeach
                </div>
            </div>

            <aside class="cyra-card p-6">
                <h2 class="text-lg font-semibold text-cyra-text">Delivery Team</h2>
                <ul class="mt-4 space-y-3">
                    @foreach ($engagement['team'] ?? [] as $member)
                        <li class="text-sm text-cyra-muted">{{ $member }}</li>
                    @endforeach
                </ul>

                @if (! empty($engagement['portfolio_slug']))
                    <div class="mt-6 border-t border-cyra-border/60 pt-4">
                        <p class="text-xs uppercase tracking-wide text-cyra-muted">Related Case Study</p>
                        <a href="{{ route('portfolio.show', $engagement['portfolio_slug']) }}" class="mt-2 inline-block text-sm font-medium text-cyra-accent hover:text-cyra-primary">
                            View portfolio project →
                        </a>
                    </div>
                @endif
            </aside>
        </div>
    </section>
@endsection
