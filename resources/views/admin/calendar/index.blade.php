@extends('layouts.admin')

@section('title', 'Calendar')

@section('content')
    <x-ui.breadcrumb :items="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Calendar']]" class="mb-6" />
    <x-ui.section-heading eyebrow="Operations Workspace" title="Calendar" description="{{ $calendar['description'] }}" class="cyra-section-heading" />

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="Total Items" :value="(string) $calendar['summary']['total_items']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Meetings" :value="(string) $calendar['summary']['meetings']" accent="text-cyra-primary" />
        <x-ui.metric-card label="Events" :value="(string) $calendar['summary']['events']" accent="text-cyra-success" />
        <x-ui.metric-card label="Tasks" :value="(string) $calendar['summary']['tasks']" accent="text-cyra-warning" />
        <x-ui.metric-card label="Calls" :value="(string) $calendar['summary']['calls']" accent="text-cyra-purple" />
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)]">
        <x-ui.card title="Schedule" description="Meetings, events, calls, tasks, and reviews across the command center.">
            <div class="space-y-3">
                @foreach ($calendar['items'] as $item)
                    <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-cyra-primary/10 text-cyra-accent"><x-ui.icon :name="$item['type_icon']" class="h-4 w-4" /></span>
                                <div>
                                    <p class="font-medium text-cyra-text">{{ $item['title'] }}</p>
                                    <p class="text-xs text-cyra-muted">{{ $item['datetime'] }}@if ($item['location']) · {{ $item['location'] }}@endif</p>
                                    <p class="mt-1 text-xs text-cyra-accent">{{ $item['source'] }}</p>
                                </div>
                            </div>
                            <x-ui.badge :variant="$item['type_variant']">{{ $item['type_label'] }}</x-ui.badge>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.card>

        <div class="space-y-6">
            <x-ui.card title="Upcoming" description="Next items on the schedule.">
                <div class="space-y-3">@foreach ($calendar['upcoming'] as $item)<div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-3"><p class="text-sm font-medium text-cyra-text">{{ $item['title'] }}</p><p class="text-xs text-cyra-muted">{{ $item['datetime'] }}</p></div>@endforeach</div>
            </x-ui.card>
            <x-ui.card title="By Type" description="Schedule breakdown by item type.">
                <div class="space-y-3">@foreach ($calendar['type_breakdown'] as $type)<div class="flex items-center justify-between rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3"><div class="flex items-center gap-3"><x-ui.icon :name="$type['icon']" class="h-4 w-4 text-cyra-accent" /><span class="text-sm text-cyra-text">{{ $type['label'] }}</span></div><x-ui.badge variant="primary">{{ $type['count'] }}</x-ui.badge></div>@endforeach</div>
            </x-ui.card>
        </div>
    </div>
@endsection
