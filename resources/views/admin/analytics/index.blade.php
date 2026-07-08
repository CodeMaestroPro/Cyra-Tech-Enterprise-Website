@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
    @php($maxTrend = max(array_column($dashboard['traffic_trend'], 'page_views') ?: [1]))

    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Analytics'],
    ]" class="mb-6" />

    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <x-ui.section-heading
            eyebrow="Module 21 Complete"
            title="Cyra Pulse Analytics"
            description="Executive visibility into traffic, module engagement, lead conversion, and platform activity across the Cyra-Tech digital estate."
        />

        <div class="flex flex-wrap gap-2">
            @foreach ($rangeOptions as $option)
                <a
                    href="{{ route('admin.analytics.index', ['range' => $option]) }}"
                    @class([
                        'rounded-full border px-3 py-1 text-xs font-medium transition',
                        'border-cyra-primary bg-cyra-primary/15 text-cyra-accent' => $rangeDays === $option,
                        'border-cyra-border text-cyra-muted hover:border-cyra-primary/40 hover:text-cyra-text' => $rangeDays !== $option,
                    ])
                >
                    Last {{ $option }} days
                </a>
            @endforeach
        </div>
    </div>

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card label="Page Views" :value="number_format($dashboard['overview']['page_views'])" accent="text-cyra-accent" />
        <x-ui.metric-card label="Unique Sessions" :value="number_format($dashboard['overview']['unique_sessions'])" accent="text-cyra-primary" />
        <x-ui.metric-card label="Form Submissions" :value="number_format($dashboard['overview']['form_submissions'])" accent="text-cyra-success" />
        <x-ui.metric-card label="Conversion Rate" :value="$dashboard['overview']['conversion_rate'].'%'" accent="text-cyra-purple" />
    </div>

    <div class="mb-6 grid gap-4 md:grid-cols-3">
        <x-ui.metric-card label="Module Views" :value="number_format($dashboard['overview']['module_views'])" accent="text-cyra-accent" />
        <x-ui.metric-card label="Contact Inquiries" :value="number_format($dashboard['overview']['contact_inquiries'])" accent="text-cyra-warning" />
        <x-ui.metric-card label="Portal Logins" :value="number_format($dashboard['overview']['portal_logins'])" accent="text-cyra-success" />
    </div>

    <x-ui.card title="Traffic Trend" description="Daily page views for the selected reporting period." class="mb-6">
        <div class="flex h-56 items-end gap-2 overflow-x-auto pb-2">
            @foreach ($dashboard['traffic_trend'] as $point)
                @php($height = $maxTrend > 0 ? max(8, (int) round(($point['page_views'] / $maxTrend) * 100)) : 8)
                <div class="flex min-w-10 flex-1 flex-col items-center gap-2">
                    <span class="text-xs text-cyra-muted">{{ number_format($point['page_views']) }}</span>
                    <div
                        class="w-full rounded-t-lg bg-gradient-to-t from-cyra-primary to-cyra-accent"
                        style="height: {{ $height }}%"
                        title="{{ $point['label'] }}: {{ number_format($point['page_views']) }} views"
                    ></div>
                    <span class="text-[10px] text-cyra-muted">{{ $point['label'] }}</span>
                </div>
            @endforeach
        </div>
    </x-ui.card>

    <div class="grid gap-6 xl:grid-cols-2">
        <x-ui.card title="Top Pages" description="Highest traffic public routes in the selected period.">
            @if (count($dashboard['top_pages']) === 0)
                <x-ui.empty-state title="No page data yet" description="Traffic events will appear here once collected." />
            @else
                <div class="space-y-3">
                    @foreach ($dashboard['top_pages'] as $page)
                        <div class="flex items-center justify-between rounded-lg border border-cyra-border/70 bg-cyra-navy/60 px-4 py-3">
                            <div>
                                <p class="font-medium text-cyra-text">{{ $page['label'] }}</p>
                                <p class="text-xs text-cyra-muted">{{ $page['subject'] }}</p>
                            </div>
                            <x-ui.badge variant="primary">{{ number_format($page['total']) }}</x-ui.badge>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-ui.card>

        <x-ui.card title="Top Modules" description="Most viewed solution, product, and content modules.">
            @if (count($dashboard['top_modules']) === 0)
                <x-ui.empty-state title="No module data yet" description="Module engagement events will appear here once collected." />
            @else
                <div class="space-y-3">
                    @foreach ($dashboard['top_modules'] as $module)
                        <div class="flex items-center justify-between rounded-lg border border-cyra-border/70 bg-cyra-navy/60 px-4 py-3">
                            <div>
                                <p class="font-medium text-cyra-text">{{ $module['label'] }}</p>
                                <p class="text-xs text-cyra-muted">{{ $module['subject'] }}</p>
                            </div>
                            <x-ui.badge variant="success">{{ number_format($module['total']) }}</x-ui.badge>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-ui.card>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[2fr,1fr]">
        <x-ui.card title="Lead Sources" description="Tracked form submission events by source page or module.">
            @if (count($dashboard['lead_sources']) === 0)
                <x-ui.empty-state title="No lead events yet" description="Form submission tracking will populate this panel." />
            @else
                <div class="space-y-3">
                    @foreach ($dashboard['lead_sources'] as $source)
                        <div class="flex items-center justify-between rounded-lg border border-cyra-border/70 px-4 py-3">
                            <span class="text-sm text-cyra-text">{{ $source['label'] }}</span>
                            <span class="text-sm font-medium text-cyra-accent">{{ number_format($source['total']) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-ui.card>

        <x-ui.card title="Platform Snapshot" description="Current operational counts across CMS, media, and users.">
            <dl class="space-y-4">
                <div class="flex items-center justify-between border-b border-cyra-border/60 pb-3">
                    <dt class="text-sm text-cyra-muted">Published CMS Pages</dt>
                    <dd class="text-sm font-semibold text-cyra-text">{{ number_format($dashboard['platform_snapshot']['cms_pages']) }}</dd>
                </div>
                <div class="flex items-center justify-between border-b border-cyra-border/60 pb-3">
                    <dt class="text-sm text-cyra-muted">Active Media Assets</dt>
                    <dd class="text-sm font-semibold text-cyra-text">{{ number_format($dashboard['platform_snapshot']['media_assets']) }}</dd>
                </div>
                <div class="flex items-center justify-between border-b border-cyra-border/60 pb-3">
                    <dt class="text-sm text-cyra-muted">Total Contact Inquiries</dt>
                    <dd class="text-sm font-semibold text-cyra-text">{{ number_format($dashboard['platform_snapshot']['contact_inquiries_total']) }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-sm text-cyra-muted">Active Users</dt>
                    <dd class="text-sm font-semibold text-cyra-text">{{ number_format($dashboard['platform_snapshot']['active_users']) }}</dd>
                </div>
            </dl>
        </x-ui.card>
    </div>

    @if (! empty($dashboard['insights']))
        <x-ui.card title="Executive Insights" class="mt-6">
            <ul class="space-y-3">
                @foreach ($dashboard['insights'] as $insight)
                    <li class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3 text-sm text-cyra-muted">
                        {{ $insight }}
                    </li>
                @endforeach
            </ul>
        </x-ui.card>
    @endif
@endsection
