@extends('layouts.portal')

@section('title', 'Dashboard')

@section('content')
    @php
        $account = $dashboard['account'] ?? [];
        $summary = $dashboard['summary'] ?? [];
        $engagements = $dashboard['engagements'] ?? [];
        $support = $dashboard['support'] ?? [];
    @endphp

    <section aria-label="Client portal dashboard">
        <header class="cyra-section-heading">
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-cyra-accent">Client Portal</p>
            <h1 class="mt-2 text-2xl font-bold text-cyra-text">{{ $account['name'] ?? 'Dashboard' }}</h1>
            <p class="mt-2 max-w-3xl text-sm text-cyra-muted">
                Track active Cyra-Tech engagements, milestones, and deliverables for your organization.
            </p>
        </header>

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.metric-card label="Total Engagements" :value="(string) ($summary['total_engagements'] ?? 0)" accent="text-cyra-accent" />
            <x-ui.metric-card label="Active Programs" :value="(string) ($summary['active_engagements'] ?? 0)" accent="text-cyra-primary" />
            <x-ui.metric-card label="Average Progress" :value="($summary['average_progress'] ?? 0).'%'" accent="text-cyra-success" />
        </div>

        <div class="mt-8">
            <h2 class="cyra-heading-2">Your Engagements</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($engagements as $engagement)
                    <x-client-portal.engagement-card :engagement="$engagement" />
                @endforeach
            </div>
        </div>

        @if (! empty($support))
            <x-ui.card :title="$support['title'] ?? 'Support'" class="mt-8">
                <p class="text-sm leading-relaxed text-cyra-muted">{{ $support['description'] ?? '' }}</p>
                <div class="mt-4">
                    <x-ui.button href="{{ route('contact', ['inquiry' => 'support']) }}" variant="secondary">
                        Contact Support
                    </x-ui.button>
                </div>
            </x-ui.card>
        @endif
    </section>
@endsection
