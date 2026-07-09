@extends('layouts.admin')

@section('title', 'Business Intelligence')

@section('content')
    @php
        $maxTrend = max(array_column($bi['digital']['traffic_trend'], 'page_views') ?: [1]);
        $maxStageValue = max(array_column($bi['crm']['stages'], 'raw_value') ?: [1]);
    @endphp

    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Business Intelligence'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="Executive Workspace"
        title="Business Intelligence"
        description="Unified executive analytics across revenue, digital performance, delivery, operations, and platform health for {{ $bi['summary']['range_label'] }}."
        class="cyra-section-heading"
    />

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-7">
        <x-ui.metric-card label="Pipeline Value" :value="$bi['summary']['pipeline_value']" accent="text-cyra-success" />
        <x-ui.metric-card label="Active Leads" :value="(string) $bi['summary']['active_leads']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Conversion Rate" :value="$bi['summary']['conversion_rate']" accent="text-cyra-purple" />
        <x-ui.metric-card label="Page Views" :value="$bi['summary']['page_views']" accent="text-cyra-primary" />
        <x-ui.metric-card label="Company Pulse" :value="$bi['summary']['company_pulse'].'%'" accent="text-cyra-warning" />
        <x-ui.metric-card label="Avg. Project Progress" :value="$bi['summary']['avg_project_progress'].'%'" accent="text-cyra-accent" />
        <x-ui.metric-card label="Platform Health" :value="$bi['summary']['health_score'].'%'" accent="text-cyra-success" />
    </div>

    <x-ui.card title="Data Domains" description="Live intelligence sources feeding the executive BI workspace." class="mb-6">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ($bi['data_domains'] as $domain)
                <article class="rounded-xl border border-cyra-border/70 bg-cyra-navy/50 p-4">
                    <div class="mb-3 flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyra-primary/30 bg-cyra-primary/10 text-cyra-accent">
                            <x-ui.icon :name="$domain['icon'] ?? 'chart-bar'" class="h-4 w-4" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-cyra-text">{{ $domain['label'] }}</p>
                            <p class="text-xs text-cyra-muted">{{ $domain['source'] }}</p>
                        </div>
                    </div>
                    <p class="text-lg font-semibold text-cyra-text">{{ $domain['primary_metric'] }}</p>
                    <p class="mt-1 text-xs text-cyra-muted">{{ $domain['secondary_metric'] }}</p>
                </article>
            @endforeach
        </div>
    </x-ui.card>

    <div class="mb-6 grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)]">
        <x-ui.card title="Digital Performance" description="Traffic, engagement, and conversion signals from Cyra Pulse Analytics.">
            <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['label' => 'Unique Sessions', 'value' => number_format($bi['digital']['overview']['unique_sessions'] ?? 0), 'icon' => 'users'],
                    ['label' => 'Module Views', 'value' => number_format($bi['digital']['overview']['module_views'] ?? 0), 'icon' => 'cube'],
                    ['label' => 'Form Submissions', 'value' => number_format($bi['digital']['overview']['form_submissions'] ?? 0), 'icon' => 'mail'],
                    ['label' => 'Portal Logins', 'value' => number_format($bi['digital']['overview']['portal_logins'] ?? 0), 'icon' => 'portal'],
                ] as $metric)
                    <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs text-cyra-muted">{{ $metric['label'] }}</p>
                            <x-ui.icon :name="$metric['icon']" class="h-4 w-4 text-cyra-accent" />
                        </div>
                        <p class="mt-1 text-xl font-semibold text-cyra-text">{{ $metric['value'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="flex h-48 items-end gap-2 overflow-x-auto pb-2">
                @foreach ($bi['digital']['traffic_trend'] as $point)
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

        <x-ui.card title="CRM Pipeline" description="Revenue pipeline by stage with live lead counts.">
            <div class="mb-4 rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4">
                <p class="text-xs text-cyra-muted">Total pipeline value</p>
                <p class="mt-1 text-2xl font-semibold text-cyra-text">{{ $bi['summary']['pipeline_value'] }}</p>
                <p class="mt-1 text-xs text-cyra-muted">{{ $bi['summary']['active_leads'] }} active leads · {{ $bi['crm']['summary']['high_priority'] ?? 0 }} high priority</p>
            </div>

            <div class="space-y-4">
                @foreach ($bi['crm']['stages'] as $stage)
                    @if (($stage['count'] ?? 0) > 0 || ($stage['raw_value'] ?? 0) > 0)
                        @php($barWidth = $maxStageValue > 0 ? max(6, (int) round(($stage['raw_value'] / $maxStageValue) * 100)) : 6)
                        <div>
                            <div class="mb-1 flex items-center justify-between text-xs">
                                <span class="text-cyra-muted">{{ $stage['label'] }}</span>
                                <span class="font-medium text-cyra-text">{{ $stage['count'] }} · {{ $stage['value'] }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-cyra-border/60">
                                <div class="h-full rounded-full bg-gradient-to-r from-cyra-primary to-cyra-success" style="width: {{ $barWidth }}%"></div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </x-ui.card>
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-3">
        <x-ui.card title="Executive KPIs" description="Command Center KPI snapshot.">
            <div class="space-y-3">
                @foreach ($bi['kpis'] as $kpi)
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

        <x-ui.card title="Company Pulse Dimensions" description="Operational health scores across the organization.">
            <div class="space-y-3">
                @foreach ($bi['company_pulse']['metrics'] ?? [] as $metric)
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

        <x-ui.card title="Platform Health" description="QA, modules, and SEO readiness indicators.">
            <dl class="space-y-4">
                <div class="flex items-center justify-between border-b border-cyra-border/60 pb-3">
                    <dt class="text-sm text-cyra-muted">Health Score</dt>
                    <dd class="text-sm font-semibold text-cyra-text">{{ $bi['platform']['health_score'] }}%</dd>
                </div>
                <div class="flex items-center justify-between border-b border-cyra-border/60 pb-3">
                    <dt class="text-sm text-cyra-muted">Modules Complete</dt>
                    <dd class="text-sm font-semibold text-cyra-text">{{ $bi['platform']['modules_completed'] }}/{{ $bi['platform']['modules_total'] }}</dd>
                </div>
                <div class="flex items-center justify-between border-b border-cyra-border/60 pb-3">
                    <dt class="text-sm text-cyra-muted">Feature Tests</dt>
                    <dd class="text-sm font-semibold text-cyra-text">{{ number_format($bi['platform']['feature_tests']) }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-sm text-cyra-muted">SEO Score</dt>
                    <dd class="text-sm font-semibold text-cyra-text">{{ $bi['platform']['seo_score'] }}%</dd>
                </div>
            </dl>
        </x-ui.card>
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-2">
        <x-ui.card title="Top Traffic Pages" description="Highest-performing public routes in the reporting period.">
            <div class="space-y-3">
                @forelse ($bi['digital']['top_pages'] as $page)
                    <div class="flex items-center justify-between rounded-lg border border-cyra-border/70 px-4 py-3">
                        <div>
                            <p class="text-sm font-medium text-cyra-text">{{ $page['label'] }}</p>
                            <p class="text-xs text-cyra-muted">{{ $page['subject'] }}</p>
                        </div>
                        <x-ui.badge variant="primary">{{ number_format($page['total']) }}</x-ui.badge>
                    </div>
                @empty
                    <x-ui.empty-state title="No page data yet" description="Traffic events will populate this panel." />
                @endforelse
            </div>
        </x-ui.card>

        <x-ui.card title="Lead Sources" description="Form submission events by source page or module.">
            <div class="space-y-3">
                @forelse ($bi['digital']['lead_sources'] as $source)
                    <div class="flex items-center justify-between rounded-lg border border-cyra-border/70 px-4 py-3">
                        <span class="text-sm text-cyra-text">{{ $source['label'] }}</span>
                        <span class="text-sm font-medium text-cyra-accent">{{ number_format($source['total']) }}</span>
                    </div>
                @empty
                    <x-ui.empty-state title="No lead events yet" description="Form submission tracking will populate this panel." />
                @endforelse
            </div>
        </x-ui.card>
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)]">
        <x-ui.card title="Delivery Portfolio" description="Active programs and progress across client engagements.">
            <div class="mb-4 grid gap-4 sm:grid-cols-3">
                <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4 text-center">
                    <p class="text-xs text-cyra-muted">In Progress</p>
                    <p class="mt-1 text-xl font-semibold text-cyra-text">{{ $bi['delivery']['summary']['in_progress'] ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4 text-center">
                    <p class="text-xs text-cyra-muted">Open Tasks</p>
                    <p class="mt-1 text-xl font-semibold text-cyra-text">{{ $bi['delivery']['summary']['open_tasks'] ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4 text-center">
                    <p class="text-xs text-cyra-muted">Overdue Tasks</p>
                    <p class="mt-1 text-xl font-semibold text-cyra-text">{{ $bi['delivery']['tasks']['overdue'] ?? 0 }}</p>
                </div>
            </div>

            <div class="space-y-3">
                @foreach ($bi['delivery']['projects'] as $project)
                    <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="font-medium text-cyra-text">{{ $project['name'] }}</p>
                                <p class="text-xs text-cyra-muted">{{ $project['client_name'] }}</p>
                            </div>
                            <x-ui.badge :variant="$project['status_variant'] ?? 'primary'">{{ $project['status_label'] }}</x-ui.badge>
                        </div>
                        <div class="mt-3">
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

        <div class="space-y-6">
            <x-ui.card title="Executive Insights" description="Cross-domain intelligence synthesized from live platform data.">
                <ul class="space-y-3">
                    @foreach ($bi['insights'] as $insight)
                        <li class="flex gap-3 rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3 text-sm leading-relaxed text-cyra-muted">
                            <x-ui.icon name="light-bulb" class="mt-0.5 h-4 w-4 shrink-0 text-cyra-accent" />
                            <span>{{ $insight }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-ui.card>

            <x-ui.card title="Related Reports" description="Drill down into specialized analytics workspaces.">
                <div class="space-y-2">
                    @foreach ($bi['reports'] as $report)
                        <a href="{{ $report['url'] }}" class="flex items-start gap-3 rounded-lg border border-cyra-border/70 px-4 py-3 transition hover:border-cyra-primary/40 hover:bg-cyra-surface/40">
                            <x-ui.icon name="report" class="mt-0.5 h-4 w-4 shrink-0 text-cyra-accent" />
                            <div>
                                <p class="text-sm font-medium text-cyra-text">{{ $report['label'] }}</p>
                                <p class="mt-1 text-xs text-cyra-muted">{{ $report['description'] }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection
