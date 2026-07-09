@extends('layouts.admin')

@section('title', 'Company Pulse')

@section('content')
    @php
        $pulseScore = $pulse['summary']['overall_score'];
        $bandVariants = [
            'excellent' => 'success',
            'good' => 'primary',
            'watch' => 'warning',
            'critical' => 'danger',
        ];
    @endphp

    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Company Pulse'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="Executive Workspace"
        title="Company Pulse"
        description="{{ $pulse['description'] }} Reporting period: {{ $pulse['summary']['range_label'] }}."
        class="cyra-section-heading"
    />

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="Overall Pulse" :value="$pulseScore.'%'" accent="text-cyra-success" />
        <x-ui.metric-card label="Excellent" :value="(string) $pulse['summary']['excellent_count']" accent="text-cyra-success" />
        <x-ui.metric-card label="Good" :value="(string) $pulse['summary']['good_count']" accent="text-cyra-primary" />
        <x-ui.metric-card label="Watch" :value="(string) $pulse['summary']['watch_count']" accent="text-cyra-warning" />
        <x-ui.metric-card label="Critical" :value="(string) $pulse['summary']['critical_count']" accent="text-cyra-danger" />
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1.4fr)]">
        <x-ui.card title="Overall Organizational Health" description="Composite pulse score across six executive dimensions.">
            <div class="flex flex-col items-center py-4">
                <div
                    class="relative flex h-48 w-48 items-center justify-center rounded-full"
                    style="background: conic-gradient(var(--color-cyra-success) 0% {{ $pulseScore }}%, var(--color-cyra-border) {{ $pulseScore }}% 100%);"
                    role="img"
                    aria-label="Overall company pulse score {{ $pulseScore }} percent"
                >
                    <div class="flex h-32 w-32 flex-col items-center justify-center rounded-full bg-cyra-navy">
                        <x-ui.icon name="pulse" class="mb-1 h-6 w-6 text-cyra-accent" />
                        <span class="text-4xl font-bold text-cyra-text">{{ $pulseScore }}%</span>
                        <span class="text-xs text-cyra-muted">Overall Score</span>
                    </div>
                </div>
            </div>

            @if (count($pulse['alerts']) > 0)
                <div class="mt-6 space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-cyra-warning">Attention Required</p>
                    @foreach ($pulse['alerts'] as $alert)
                        <div class="rounded-lg border border-cyra-warning/30 bg-cyra-warning/5 px-4 py-3">
                            <p class="text-sm font-medium text-cyra-text">{{ $alert['message'] }}</p>
                            <p class="mt-1 text-xs text-cyra-muted">{{ $alert['recommendation'] }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="mt-6 rounded-lg border border-cyra-success/30 bg-cyra-success/5 px-4 py-3 text-sm text-cyra-muted">
                    All pulse dimensions are at or above the good threshold. Organizational health is stable.
                </p>
            @endif
        </x-ui.card>

        <x-ui.card title="Pulse Dimensions" description="Detailed scores, status bands, and executive recommendations.">
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($pulse['metrics'] as $metric)
                    <article class="rounded-xl border border-cyra-border/70 bg-cyra-navy/50 p-4">
                        <div class="mb-3 flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyra-border/70 bg-cyra-surface/60 text-cyra-accent">
                                    <x-ui.icon :name="$metric['icon']" class="h-4 w-4" />
                                </span>
                                <div>
                                    <p class="font-medium text-cyra-text">{{ $metric['label'] }}</p>
                                    <p class="mt-1 text-xs text-cyra-muted">{{ $metric['focus'] }}</p>
                                </div>
                            </div>
                            <x-ui.badge :variant="$bandVariants[$metric['band']] ?? 'default'">{{ $metric['band_label'] }}</x-ui.badge>
                        </div>

                        <div class="mb-3">
                            <div class="mb-1 flex items-center justify-between text-xs">
                                <span class="text-cyra-muted">Score</span>
                                <span class="font-semibold text-cyra-text">{{ $metric['score'] }}%</span>
                            </div>
                            <div class="h-2.5 overflow-hidden rounded-full bg-cyra-border/60">
                                <div @class([
                                    'h-full rounded-full',
                                    'bg-cyra-success' => $metric['band'] === 'excellent',
                                    'bg-cyra-primary' => $metric['band'] === 'good',
                                    'bg-cyra-warning' => $metric['band'] === 'watch',
                                    'bg-cyra-danger' => $metric['band'] === 'critical',
                                ]) style="width: {{ $metric['score'] }}%"></div>
                            </div>
                        </div>

                        <p class="text-xs leading-relaxed text-cyra-muted">{{ $metric['recommendation'] }}</p>
                    </article>
                @endforeach
            </div>
        </x-ui.card>
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-2">
        <x-ui.card title="Live Operational Signals" description="Real-time indicators feeding the pulse assessment.">
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($pulse['live_signals'] as $signal)
                    <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-cyra-primary/10 text-cyra-accent">
                                <x-ui.icon :name="$signal['icon']" class="h-4 w-4" />
                            </span>
                            <div>
                                <p class="text-xs text-cyra-muted">{{ $signal['label'] }}</p>
                                <p class="text-sm font-semibold text-cyra-text">{{ $signal['value'] }}</p>
                                <p class="text-xs text-cyra-muted">{{ $signal['detail'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.card>

        <x-ui.card title="Executive KPI Alignment" description="Command Center KPIs correlated with pulse performance.">
            <div class="space-y-3">
                @foreach ($pulse['kpis'] as $kpi)
                    <div class="flex items-center justify-between rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3">
                        <div class="flex items-center gap-3">
                            <x-ui.icon :name="$kpi['icon'] ?? 'spark'" class="h-4 w-4 text-cyra-accent" />
                            <span class="text-sm text-cyra-muted">{{ $kpi['label'] }}</span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-cyra-text">{{ $kpi['value'] }}</p>
                            <p class="text-xs text-cyra-success">{{ $kpi['change'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.card>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
        <x-ui.card title="Executive Watchlist" description="Dimensions and themes requiring ongoing leadership attention.">
            <div class="space-y-3">
                @foreach ($pulse['watchlist'] as $item)
                    <div class="flex items-start gap-3 rounded-lg border border-cyra-border/70 px-4 py-3">
                        <x-ui.icon name="activity" class="mt-0.5 h-4 w-4 shrink-0 text-cyra-warning" />
                        <div>
                            <p class="text-sm font-medium text-cyra-text">{{ $item['label'] }}</p>
                            <p class="mt-1 text-xs text-cyra-muted">{{ $item['reason'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.card>

        <x-ui.card title="Related Workspaces">
            <div class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 rounded-lg border border-cyra-border/70 px-4 py-3 text-sm text-cyra-text transition hover:border-cyra-primary/40 hover:bg-cyra-surface/40">
                    <x-ui.icon name="command" class="h-4 w-4 text-cyra-accent" />
                    Command Center Brief
                </a>
                <a href="{{ route('admin.business-intelligence.index') }}" class="flex items-center gap-2 rounded-lg border border-cyra-border/70 px-4 py-3 text-sm text-cyra-text transition hover:border-cyra-primary/40 hover:bg-cyra-surface/40">
                    <x-ui.icon name="chart-bar" class="h-4 w-4 text-cyra-accent" />
                    Business Intelligence
                </a>
                <a href="{{ route('admin.projects.index') }}" class="flex items-center gap-2 rounded-lg border border-cyra-border/70 px-4 py-3 text-sm text-cyra-text transition hover:border-cyra-primary/40 hover:bg-cyra-surface/40">
                    <x-ui.icon name="folder" class="h-4 w-4 text-cyra-accent" />
                    Project Delivery
                </a>
            </div>
        </x-ui.card>
    </div>
@endsection
