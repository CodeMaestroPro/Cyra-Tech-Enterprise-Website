@extends('layouts.admin')

@section('title', 'Leads & CRM')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Leads & CRM'],
    ]" class="mb-6" />

    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <x-ui.section-heading
            eyebrow="Module 23 Complete"
            title="Leads & CRM"
            description="Track enterprise pipeline stages, manage qualified leads, and convert inbound contact inquiries into active opportunities."
        />

        @if (auth()->user()?->hasPermission('crm.create'))
            <x-ui.button href="{{ route('admin.crm.create') }}">Add Lead</x-ui.button>
        @endif
    </div>

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="danger" class="mb-6">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="Active Leads" :value="(string) $pipeline['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Pipeline Value" :value="'₦'.number_format($pipeline['summary']['pipeline_value'], 0)" accent="text-cyra-primary" />
        <x-ui.metric-card label="Won Deals" :value="(string) $pipeline['summary']['won']" accent="text-cyra-success" />
        <x-ui.metric-card label="High Priority" :value="(string) $pipeline['summary']['high_priority']" accent="text-cyra-warning" />
        <x-ui.metric-card label="Inbound Inquiries" :value="(string) $pipeline['summary']['inbound_inquiries']" accent="text-cyra-purple" />
    </div>

    @if (count($pipeline['inbound_inquiries']) > 0)
        <x-ui.card title="Inbound Contact Inquiries" description="Unlinked contact form submissions ready for CRM conversion." class="mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cyra-border text-sm">
                    <thead>
                        <tr class="text-left text-cyra-muted">
                            <th class="px-3 py-2 font-medium">Reference</th>
                            <th class="px-3 py-2 font-medium">Contact</th>
                            <th class="px-3 py-2 font-medium">Type</th>
                            <th class="px-3 py-2 font-medium">Submitted</th>
                            <th class="px-3 py-2 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyra-border/60">
                        @foreach ($pipeline['inbound_inquiries'] as $inquiry)
                            <tr>
                                <td class="px-3 py-3 font-mono text-xs text-cyra-accent">{{ $inquiry['reference'] }}</td>
                                <td class="px-3 py-3">
                                    <p class="font-medium text-cyra-text">{{ $inquiry['name'] }}</p>
                                    <p class="text-xs text-cyra-muted">{{ $inquiry['email'] }}</p>
                                </td>
                                <td class="px-3 py-3">{{ $inquiry['inquiry_type_label'] }}</td>
                                <td class="px-3 py-3 text-cyra-muted">{{ \Illuminate\Support\Carbon::parse($inquiry['created_at'])->diffForHumans() }}</td>
                                <td class="px-3 py-3 text-right">
                                    @if (auth()->user()?->hasPermission('crm.create'))
                                        <form method="POST" action="{{ route('admin.crm.inquiries.convert', $inquiry['id']) }}">
                                            @csrf
                                            <x-ui.button type="submit" variant="secondary" class="text-xs">Convert to Lead</x-ui.button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    @endif

    <div class="mb-4 flex flex-wrap gap-2">
        <a
            href="{{ route('admin.crm.index', ['stage' => 'all']) }}"
            @class([
                'rounded-full border px-3 py-1 text-xs font-medium transition',
                'border-cyra-primary bg-cyra-primary/15 text-cyra-accent' => $stageFilter === 'all' || $stageFilter === null,
                'border-cyra-border text-cyra-muted hover:border-cyra-primary/40 hover:text-cyra-text' => $stageFilter !== 'all' && $stageFilter !== null,
            ])
        >
            All Stages
        </a>
        @foreach ($pipeline['pipeline_stages'] as $stage)
            <a
                href="{{ route('admin.crm.index', ['stage' => $stage['slug']]) }}"
                @class([
                    'rounded-full border px-3 py-1 text-xs font-medium transition',
                    'border-cyra-primary bg-cyra-primary/15 text-cyra-accent' => $stageFilter === $stage['slug'],
                    'border-cyra-border text-cyra-muted hover:border-cyra-primary/40 hover:text-cyra-text' => $stageFilter !== $stage['slug'],
                ])
            >
                {{ $stage['label'] }}
            </a>
        @endforeach
    </div>

    <div class="grid gap-4 xl:grid-cols-3 2xl:grid-cols-6">
        @foreach ($pipeline['stages'] as $stage)
            <x-ui.card
                :title="$stage['label']"
                :description="$stage['count'].' leads · ₦'.number_format($stage['value'], 0)"
                class="min-h-[320px]"
            >
                <div class="space-y-3">
                    @forelse ($stage['leads'] as $lead)
                        <div class="rounded-lg border border-cyra-border bg-cyra-navy/60 p-3">
                            <div class="mb-2 flex items-start justify-between gap-2">
                                <div>
                                    <a
                                        href="{{ route('admin.crm.edit', $lead['reference']) }}"
                                        class="font-medium text-cyra-text hover:text-cyra-accent"
                                    >
                                        {{ $lead['name'] }}
                                    </a>
                                    <p class="text-xs text-cyra-muted">{{ $lead['company'] ?? 'Independent' }}</p>
                                </div>
                                <span @class([
                                    'rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase',
                                    'bg-cyra-danger/15 text-cyra-danger' => $lead['priority'] === 'high',
                                    'bg-cyra-warning/15 text-cyra-warning' => $lead['priority'] === 'medium',
                                    'bg-cyra-muted/15 text-cyra-muted' => $lead['priority'] === 'low',
                                ])>
                                    {{ $lead['priority_label'] }}
                                </span>
                            </div>

                            <div class="mb-3 flex flex-wrap gap-2 text-[11px] text-cyra-muted">
                                <span>{{ $lead['source_label'] }}</span>
                                @if ($lead['estimated_value_label'])
                                    <span>·</span>
                                    <span class="text-cyra-accent">{{ $lead['estimated_value_label'] }}</span>
                                @endif
                            </div>

                            @if (auth()->user()?->hasPermission('crm.manage'))
                                <form method="POST" action="{{ route('admin.crm.stage', $lead['reference']) }}" class="flex gap-2">
                                    @csrf
                                    <select
                                        name="pipeline_stage"
                                        class="block w-full rounded-md border border-cyra-border bg-cyra-navy px-2 py-1 text-xs text-cyra-text"
                                        onchange="this.form.submit()"
                                    >
                                        @foreach ($pipeline['pipeline_stages'] as $option)
                                            <option value="{{ $option['slug'] }}" @selected($lead['pipeline_stage'] === $option['slug'])>
                                                {{ $option['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-cyra-muted">No leads in this stage.</p>
                    @endforelse
                </div>
            </x-ui.card>
        @endforeach
    </div>
@endsection
