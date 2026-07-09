@extends('layouts.admin')

@section('title', 'Marketing')

@section('content')
    @php
        $maxTrend = max(array_column($marketing['performance']['traffic_trend'], 'page_views') ?: [1]);
    @endphp

    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Marketing'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="Growth Workspace"
        title="Marketing"
        description="{{ $marketing['description'] }} Reporting period: {{ $marketing['summary']['range_label'] }}."
        class="cyra-section-heading"
    />

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-7">
        <x-ui.metric-card label="Active Campaigns" :value="(string) $marketing['summary']['active_campaigns']" accent="text-cyra-success" />
        <x-ui.metric-card label="Total Campaigns" :value="(string) $marketing['summary']['total_campaigns']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Page Views" :value="$marketing['summary']['page_views']" accent="text-cyra-primary" />
        <x-ui.metric-card label="Conversion Rate" :value="$marketing['summary']['conversion_rate']" accent="text-cyra-purple" />
        <x-ui.metric-card label="CRM Leads" :value="(string) $marketing['summary']['marketing_leads']" accent="text-cyra-warning" />
        <x-ui.metric-card label="Form Submissions" :value="$marketing['summary']['form_submissions']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Marketing Assets" :value="(string) $marketing['summary']['marketing_assets']" accent="text-cyra-success" />
    </div>

    <x-ui.card title="Campaign Status" description="Current campaign portfolio by lifecycle stage." class="mb-6">
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ($marketing['status_breakdown'] as $stage)
                <div class="rounded-xl border border-cyra-border/70 bg-cyra-navy/50 p-4 text-center">
                    <span class="mx-auto mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-cyra-primary/10 text-cyra-accent">
                        <x-ui.icon :name="$stage['icon']" class="h-4 w-4" />
                    </span>
                    <p class="text-2xl font-bold text-cyra-text">{{ $stage['count'] }}</p>
                    <p class="mt-1 text-xs text-cyra-muted">{{ $stage['label'] }}</p>
                </div>
            @endforeach
        </div>
    </x-ui.card>

    <div class="mb-6 grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(0,1fr)]">
        <x-ui.card title="Campaign Portfolio" description="Active and planned growth programs across channels.">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cyra-border text-sm">
                    <thead>
                        <tr class="text-left text-cyra-muted">
                            <th class="px-4 py-3 font-medium">Campaign</th>
                            <th class="px-4 py-3 font-medium">Channel</th>
                            <th class="px-4 py-3 font-medium">Period</th>
                            <th class="px-4 py-3 font-medium">Leads</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyra-border/70">
                        @foreach ($marketing['campaigns'] as $campaign)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-start gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyra-border/70 bg-cyra-surface/60 text-cyra-accent">
                                            <x-ui.icon :name="$campaign['channel_icon']" class="h-4 w-4" />
                                        </span>
                                        <div class="min-w-0">
                                            <p class="font-medium text-cyra-text">{{ $campaign['name'] }}</p>
                                            <p class="mt-0.5 text-xs text-cyra-muted">{{ $campaign['owner'] }}</p>
                                            <p class="mt-1 line-clamp-2 text-xs text-cyra-muted">{{ $campaign['objective'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-cyra-text">{{ $campaign['channel_label'] }}</td>
                                <td class="px-4 py-4">
                                    <p class="text-cyra-text">{{ $campaign['period'] }}</p>
                                    @if ($campaign['budget'])
                                        <p class="mt-0.5 text-xs text-cyra-muted">{{ $campaign['budget'] }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-cyra-text">{{ $campaign['metrics']['leads'] }}</p>
                                    <p class="text-xs text-cyra-muted">{{ $campaign['metrics']['clicks'] }} clicks</p>
                                </td>
                                <td class="px-4 py-4">
                                    <x-ui.badge :variant="$campaign['status_variant']">{{ $campaign['status_label'] }}</x-ui.badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <div class="space-y-6">
            <x-ui.card title="Channel Mix" description="Campaign distribution and lead contribution by channel.">
                <div class="space-y-3">
                    @foreach ($marketing['channel_breakdown'] as $channel)
                        <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-cyra-primary/10 text-cyra-accent">
                                        <x-ui.icon :name="$channel['icon']" class="h-4 w-4" />
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-cyra-text">{{ $channel['label'] }}</p>
                                        <p class="text-xs text-cyra-muted">{{ $channel['active_count'] }} active · {{ $channel['total_leads'] }} leads</p>
                                    </div>
                                </div>
                                <x-ui.badge variant="primary">{{ $channel['count'] }}</x-ui.badge>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.card title="Digital Performance" description="Live traffic trend from Cyra Pulse Analytics.">
                <div class="flex h-40 items-end gap-2 overflow-x-auto pb-2">
                    @foreach ($marketing['performance']['traffic_trend'] as $point)
                        @php($height = $maxTrend > 0 ? max(8, (int) round(($point['page_views'] / $maxTrend) * 100)) : 8)
                        <div class="flex min-w-8 flex-1 flex-col items-center gap-2">
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
        </div>
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-2">
        <x-ui.card title="Content Channels" description="Public and admin surfaces powering marketing programs.">
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($marketing['content_channels'] as $channel)
                    <a
                        href="{{ $channel['href'] }}"
                        @if ($channel['external']) target="_blank" rel="noopener noreferrer" @endif
                        class="group rounded-xl border border-cyra-border/70 bg-cyra-navy/50 p-4 transition hover:border-cyra-primary/40 hover:bg-cyra-primary/5"
                    >
                        <div class="flex items-start gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyra-primary/30 bg-cyra-primary/10 text-cyra-accent">
                                <x-ui.icon :name="$channel['icon']" class="h-4 w-4" />
                            </span>
                            <div>
                                <p class="text-sm font-medium text-cyra-text group-hover:text-cyra-accent">{{ $channel['label'] }}</p>
                                <p class="mt-1 text-xs text-cyra-muted">{{ $channel['description'] }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </x-ui.card>

        <x-ui.card title="Quick Links" description="Related growth and analytics workspaces.">
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($marketing['quick_links'] as $link)
                    <a
                        href="{{ $link['href'] }}"
                        @if ($link['external']) target="_blank" rel="noopener noreferrer" @endif
                        class="group rounded-xl border border-cyra-border/70 bg-cyra-navy/50 p-4 transition hover:border-cyra-primary/40 hover:bg-cyra-primary/5"
                    >
                        <div class="flex items-start gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyra-primary/30 bg-cyra-primary/10 text-cyra-accent">
                                <x-ui.icon :name="$link['icon']" class="h-4 w-4" />
                            </span>
                            <div>
                                <p class="text-sm font-medium text-cyra-text group-hover:text-cyra-accent">{{ $link['label'] }}</p>
                                <p class="mt-1 text-xs text-cyra-muted">{{ $link['description'] }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </x-ui.card>
    </div>

    @if (count($marketing['workspace_notes']) > 0)
        <x-ui.card title="Workspace Notes" description="How marketing programs are managed in the current release.">
            <ul class="space-y-2 text-sm text-cyra-muted">
                @foreach ($marketing['workspace_notes'] as $note)
                    <li class="flex items-start gap-2">
                        <x-ui.icon name="spark" class="mt-0.5 h-4 w-4 shrink-0 text-cyra-accent" />
                        <span>{{ $note }}</span>
                    </li>
                @endforeach
            </ul>
        </x-ui.card>
    @endif
@endsection
