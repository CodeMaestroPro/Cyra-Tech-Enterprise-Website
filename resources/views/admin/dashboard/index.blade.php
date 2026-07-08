@extends('layouts.admin')

@section('title', 'Command Center')

@section('content')
    @php
        $maxTrend = max(array_column($dashboard['website_analytics']['traffic_trend'], 'page_views') ?: [1]);
        $pulseScore = $dashboard['company_pulse']['overall_score'];
    @endphp

    <section aria-label="Command Center dashboard">
        <header class="mb-8 flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.2em] text-cyra-accent">Module 22 Complete</p>
                <h2 class="mt-2 text-2xl font-bold text-cyra-text">{{ $dashboard['greeting']['message'] }} {{ $dashboard['greeting']['name'] }} 👋</h2>
                <p class="mt-2 text-sm text-cyra-muted">{{ $dashboard['greeting']['subtitle'] }}</p>
            </div>
            <p class="text-sm text-cyra-muted">{{ $dashboard['datetime'] }}</p>
        </header>

        <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            @foreach ($dashboard['kpis'] as $kpi)
                <div class="cyra-card p-5">
                    <p class="text-sm text-cyra-muted">{{ $kpi['label'] }}</p>
                    <p class="mt-2 text-2xl font-semibold text-cyra-text">{{ $kpi['value'] }}</p>
                    <p @class([
                        'mt-2 text-xs font-medium',
                        'text-cyra-success' => $kpi['positive'] ?? true,
                        'text-cyra-danger' => ! ($kpi['positive'] ?? true),
                    ])>
                        {{ $kpi['change'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
            <div class="space-y-6">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)]">
                    <x-ui.card :title="$dashboard['executive_brief']['title']" :description="$dashboard['executive_brief']['subtitle'] ?? null">
                        <div class="mb-6 flex items-start gap-4">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border border-cyra-primary/30 bg-cyra-primary/10 text-3xl" aria-hidden="true">
                                🧠
                            </div>
                            <ul class="space-y-3 text-sm leading-relaxed text-cyra-muted">
                                @foreach ($dashboard['executive_brief']['summary'] as $line)
                                    <li class="flex gap-2">
                                        <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-cyra-accent"></span>
                                        <span>{{ $line }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <x-ui.button href="{{ $dashboard['executive_brief']['report_route'] }}">
                            {{ $dashboard['executive_brief']['action_label'] ?? 'Open Executive Report' }}
                        </x-ui.button>
                    </x-ui.card>

                    <x-ui.card title="Company Pulse" description="Overall organizational health score.">
                        <div class="flex flex-col items-center">
                            <div
                                class="relative flex h-40 w-40 items-center justify-center rounded-full"
                                style="background: conic-gradient(#22c55e 0% {{ $pulseScore }}%, #1e293b {{ $pulseScore }}% 100%);"
                                role="img"
                                aria-label="Overall company pulse score {{ $pulseScore }} percent"
                            >
                                <div class="flex h-28 w-28 flex-col items-center justify-center rounded-full bg-cyra-navy">
                                    <span class="text-3xl font-bold text-cyra-text">{{ $pulseScore }}%</span>
                                    <span class="text-xs text-cyra-muted">Overall Score</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 space-y-3">
                            @foreach ($dashboard['company_pulse']['metrics'] as $metric)
                                <div>
                                    <div class="mb-1 flex items-center justify-between text-xs">
                                        <span class="text-cyra-muted">{{ $metric['label'] }}</span>
                                        <span class="font-medium text-cyra-text">{{ $metric['score'] }}%</span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-cyra-border/60">
                                        <div class="h-full rounded-full bg-gradient-to-r from-cyra-primary to-cyra-accent" style="width: {{ $metric['score'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-ui.card>
                </div>

                <x-ui.card :title="$dashboard['website_analytics']['title']" :description="$dashboard['website_analytics']['range_label']">
                    <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach ($dashboard['website_analytics']['metrics'] as $metric)
                            <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4">
                                <p class="text-xs text-cyra-muted">{{ $metric['label'] }}</p>
                                <p class="mt-1 text-xl font-semibold text-cyra-text">{{ $metric['value'] }}</p>
                                <p @class([
                                    'mt-1 text-xs font-medium',
                                    'text-cyra-success' => $metric['positive'] ?? true,
                                    'text-cyra-danger' => ! ($metric['positive'] ?? true),
                                ])>{{ $metric['change'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex h-48 items-end gap-2 overflow-x-auto pb-2">
                        @foreach ($dashboard['website_analytics']['traffic_trend'] as $point)
                            @php($height = $maxTrend > 0 ? max(8, (int) round(($point['page_views'] / $maxTrend) * 100)) : 8)
                            <div class="flex min-w-10 flex-1 flex-col items-center gap-2">
                                <div
                                    class="w-full rounded-t-md bg-gradient-to-t from-cyra-primary/80 to-cyra-accent/80"
                                    style="height: {{ $height }}%"
                                    title="{{ $point['label'] }}: {{ number_format($point['page_views']) }} views"
                                ></div>
                                <span class="text-[10px] text-cyra-muted">{{ $point['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card title="Projects Overview" description="Active delivery programs and client engagements.">
                    <div class="space-y-4">
                        @foreach ($dashboard['projects'] as $project)
                            <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div>
                                        <p class="font-medium text-cyra-text">{{ $project['name'] }}</p>
                                        @if (! empty($project['client']))
                                            <p class="text-xs text-cyra-muted">{{ $project['client'] }}</p>
                                        @endif
                                    </div>
                                    <x-ui.badge :variant="$project['status_variant'] ?? 'primary'">{{ $project['status'] }}</x-ui.badge>
                                </div>
                                <div class="mt-4">
                                    <div class="mb-1 flex items-center justify-between text-xs">
                                        <span class="text-cyra-muted">Progress</span>
                                        <span class="text-cyra-text">{{ $project['progress'] }}%</span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-cyra-border/60">
                                        <div class="h-full rounded-full bg-cyra-primary" style="width: {{ $project['progress'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>
            </div>

            <aside class="space-y-6">
                <x-ui.card title="Quick Actions">
                    <div class="grid grid-cols-2 gap-3">
                        @foreach ($dashboard['quick_actions'] as $action)
                            <a
                                href="{{ $action['url'] }}"
                                class="flex flex-col items-center justify-center rounded-xl border border-cyra-border/70 bg-cyra-navy/50 px-3 py-4 text-center transition hover:border-cyra-primary/40 hover:bg-cyra-surface/60"
                            >
                                <span class="mb-2 text-xl" aria-hidden="true">
                                    @switch($action['icon'] ?? 'default')
                                        @case('project') 📁 @break
                                        @case('post') ✍️ @break
                                        @case('user') 👤 @break
                                        @case('media') 🖼️ @break
                                        @case('career') 💼 @break
                                        @case('report') 📊 @break
                                        @default ⚡
                                    @endswitch
                                </span>
                                <span class="text-xs font-medium text-cyra-text">{{ $action['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card title="Upcoming Events">
                    <div class="space-y-3">
                        @foreach ($dashboard['upcoming_events'] as $event)
                            <div class="rounded-lg border border-cyra-border/70 px-4 py-3">
                                <p class="text-sm font-medium text-cyra-text">{{ $event['title'] }}</p>
                                <p class="mt-1 text-xs text-cyra-muted">{{ $event['datetime'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card title="My Tasks">
                    <div class="space-y-3">
                        @foreach ($dashboard['tasks'] as $task)
                            <div class="flex items-start gap-3 rounded-lg border border-cyra-border/70 px-4 py-3">
                                <input
                                    type="checkbox"
                                    @checked(($task['status'] ?? '') === 'completed')
                                    disabled
                                    class="mt-0.5 rounded border-cyra-border bg-cyra-navy text-cyra-primary"
                                    aria-label="{{ $task['title'] }}"
                                />
                                <div>
                                    <p @class([
                                        'text-sm',
                                        'text-cyra-text' => ($task['status'] ?? '') !== 'completed',
                                        'text-cyra-muted line-through' => ($task['status'] ?? '') === 'completed',
                                    ])>{{ $task['title'] }}</p>
                                    <p class="mt-1 text-xs text-cyra-muted">{{ $task['due'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card :title="$dashboard['system_status']['title']">
                    <div class="mb-4 flex items-center justify-between">
                        <span class="text-sm text-cyra-muted">Overall</span>
                        <x-ui.badge variant="success">{{ ucfirst($dashboard['system_status']['overall']) }}</x-ui.badge>
                    </div>
                    <div class="space-y-3">
                        @foreach ($dashboard['system_status']['services'] as $service)
                            <div class="flex items-center justify-between rounded-lg border border-cyra-border/70 px-4 py-2.5">
                                <span class="text-sm text-cyra-text">{{ $service['label'] }}</span>
                                <x-ui.badge variant="success">{{ ucfirst($service['status']) }}</x-ui.badge>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>
            </aside>
        </div>

        <x-ui.card title="Recent Activities" class="mt-6">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($dashboard['recent_activities'] as $activity)
                    <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4">
                        <p class="text-sm font-medium text-cyra-text">{{ $activity['title'] }}</p>
                        <p class="mt-1 text-xs text-cyra-muted">{{ $activity['actor'] }}</p>
                        <p class="mt-2 text-xs text-cyra-accent">{{ $activity['time'] }}</p>
                    </div>
                @endforeach
            </div>
        </x-ui.card>
    </section>
@endsection
