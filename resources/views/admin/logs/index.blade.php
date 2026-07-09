@extends('layouts.admin')

@section('title', 'Logs')

@section('content')
    <x-ui.breadcrumb :items="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Logs']]" class="mb-6" />
    <x-ui.section-heading eyebrow="System Workspace" title="Logs" description="{{ $logs['description'] }}" class="cyra-section-heading" />

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="Audit Entries" :value="(string) $logs['summary']['total_entries']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Info" :value="(string) $logs['summary']['info_count']" accent="text-cyra-primary" />
        <x-ui.metric-card label="Warnings" :value="(string) $logs['summary']['warning_count']" accent="text-cyra-warning" />
        <x-ui.metric-card label="Critical" :value="(string) $logs['summary']['critical_count']" accent="text-cyra-danger" />
        <x-ui.metric-card label="Activity Feed" :value="(string) $logs['summary']['activity_feed_count']" accent="text-cyra-purple" />
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(0,1fr)]">
        <x-ui.card title="Audit Log" description="Platform audit trail ordered by most recent events.">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cyra-border text-sm">
                    <thead><tr class="text-left text-cyra-muted"><th class="px-4 py-3 font-medium">Reference</th><th class="px-4 py-3 font-medium">Action</th><th class="px-4 py-3 font-medium">Actor</th><th class="px-4 py-3 font-medium">Time</th><th class="px-4 py-3 font-medium">Severity</th></tr></thead>
                    <tbody class="divide-y divide-cyra-border/70">
                        @foreach ($logs['entries'] as $entry)
                            <tr>
                                <td class="px-4 py-4 font-mono text-xs text-cyra-accent">{{ $entry['reference'] }}</td>
                                <td class="px-4 py-4"><p class="font-medium text-cyra-text">{{ $entry['action'] }}</p><p class="mt-1 text-xs text-cyra-muted">{{ $entry['details'] }}</p></td>
                                <td class="px-4 py-4 text-cyra-muted">{{ $entry['actor'] }}</td>
                                <td class="px-4 py-4"><p class="text-cyra-text">{{ $entry['occurred_at'] }}</p><p class="text-xs text-cyra-muted">{{ $entry['occurred_ago'] }}</p></td>
                                <td class="px-4 py-4"><x-ui.badge :variant="$entry['severity_variant']">{{ $entry['severity_label'] }}</x-ui.badge></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <div class="space-y-6">
            <x-ui.card title="Severity Breakdown" description="Log volume by severity level.">
                <div class="space-y-3">@foreach ($logs['severity_breakdown'] as $item)<div class="flex items-center justify-between rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3"><div class="flex items-center gap-3"><x-ui.icon :name="$item['icon']" class="h-4 w-4 text-cyra-accent" /><span class="text-sm text-cyra-text">{{ $item['label'] }}</span></div><x-ui.badge :variant="$item['variant']">{{ $item['count'] }}</x-ui.badge></div>@endforeach</div>
            </x-ui.card>
            <x-ui.card title="Recent Activity Feed" description="Live activity signals from the Command Center.">
                <div class="space-y-3">@foreach ($logs['activity_feed'] as $activity)<div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-3"><p class="text-sm font-medium text-cyra-text">{{ $activity['title'] }}</p><p class="text-xs text-cyra-muted">{{ $activity['actor'] }} · {{ $activity['time'] }}</p></div>@endforeach</div>
            </x-ui.card>
        </div>
    </div>
@endsection
