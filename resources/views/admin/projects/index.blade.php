@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Projects'],
    ]" class="mb-6" />

    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <x-ui.section-heading
            eyebrow="Module 24 Complete"
            title="Project Management"
            description="Track enterprise delivery programs, monitor progress, and manage operational project portfolios across Cyra-Tech engagements."
        />

        <div class="flex flex-wrap gap-2">
            <x-ui.button href="{{ route('admin.projects.tasks') }}" variant="secondary">View Tasks</x-ui.button>
            @if (auth()->user()?->hasPermission('projects.create'))
                <x-ui.button href="{{ route('admin.projects.create') }}">Add Project</x-ui.button>
            @endif
        </div>
    </div>

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="danger" class="mb-6">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-6">
        <x-ui.metric-card label="Active Projects" :value="(string) $portfolio['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="In Progress" :value="(string) $portfolio['summary']['in_progress']" accent="text-cyra-primary" />
        <x-ui.metric-card label="Completed" :value="(string) $portfolio['summary']['completed']" accent="text-cyra-success" />
        <x-ui.metric-card label="On Hold" :value="(string) $portfolio['summary']['on_hold']" accent="text-cyra-warning" />
        <x-ui.metric-card label="Avg. Progress" :value="$portfolio['summary']['average_progress'].'%'" accent="text-cyra-purple" />
        <x-ui.metric-card label="Open Tasks" :value="(string) $portfolio['summary']['open_tasks']" accent="text-cyra-accent" />
    </div>

    <div class="mb-4 flex flex-wrap gap-2">
        <span class="self-center text-xs font-medium uppercase tracking-wide text-cyra-muted">Status</span>
        <a
            href="{{ route('admin.projects.index', ['status' => 'all', 'phase' => $phaseFilter]) }}"
            @class([
                'rounded-full border px-3 py-1 text-xs font-medium transition',
                'border-cyra-primary bg-cyra-primary/15 text-cyra-accent' => $statusFilter === 'all' || $statusFilter === null,
                'border-cyra-border text-cyra-muted hover:border-cyra-primary/40 hover:text-cyra-text' => $statusFilter !== 'all' && $statusFilter !== null,
            ])
        >
            All
        </a>
        @foreach ($portfolio['statuses'] as $status)
            <a
                href="{{ route('admin.projects.index', ['status' => $status['slug'], 'phase' => $phaseFilter]) }}"
                @class([
                    'rounded-full border px-3 py-1 text-xs font-medium transition',
                    'border-cyra-primary bg-cyra-primary/15 text-cyra-accent' => $statusFilter === $status['slug'],
                    'border-cyra-border text-cyra-muted hover:border-cyra-primary/40 hover:text-cyra-text' => $statusFilter !== $status['slug'],
                ])
            >
                {{ $status['label'] }}
            </a>
        @endforeach
    </div>

    <div class="mb-6 flex flex-wrap gap-2">
        <span class="self-center text-xs font-medium uppercase tracking-wide text-cyra-muted">Phase</span>
        <a
            href="{{ route('admin.projects.index', ['status' => $statusFilter, 'phase' => 'all']) }}"
            @class([
                'rounded-full border px-3 py-1 text-xs font-medium transition',
                'border-cyra-primary bg-cyra-primary/15 text-cyra-accent' => $phaseFilter === 'all' || $phaseFilter === null,
                'border-cyra-border text-cyra-muted hover:border-cyra-primary/40 hover:text-cyra-text' => $phaseFilter !== 'all' && $phaseFilter !== null,
            ])
        >
            All Phases
        </a>
        @foreach ($portfolio['phases'] as $phase)
            <a
                href="{{ route('admin.projects.index', ['status' => $statusFilter, 'phase' => $phase['slug']]) }}"
                @class([
                    'rounded-full border px-3 py-1 text-xs font-medium transition',
                    'border-cyra-primary bg-cyra-primary/15 text-cyra-accent' => $phaseFilter === $phase['slug'],
                    'border-cyra-border text-cyra-muted hover:border-cyra-primary/40 hover:text-cyra-text' => $phaseFilter !== $phase['slug'],
                ])
            >
                {{ $phase['label'] }}
            </a>
        @endforeach
    </div>

    <div class="grid gap-4 xl:grid-cols-2">
        @forelse ($portfolio['projects'] as $project)
            <x-ui.card :title="$project['name']" :description="$project['client_name'] ?? 'Internal Program'">
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span @class([
                            'rounded-full px-2 py-0.5 font-semibold uppercase',
                            'bg-cyra-primary/15 text-cyra-primary' => $project['status_variant'] === 'primary',
                            'bg-cyra-success/15 text-cyra-success' => $project['status_variant'] === 'success',
                            'bg-cyra-warning/15 text-cyra-warning' => $project['status_variant'] === 'warning',
                            'bg-cyra-purple/15 text-cyra-purple' => $project['status_variant'] === 'purple',
                            'bg-cyra-danger/15 text-cyra-danger' => $project['status_variant'] === 'danger',
                        ])>
                            {{ $project['status_label'] }}
                        </span>
                        <span class="text-cyra-muted">{{ $project['phase_label'] }}</span>
                        <span class="text-cyra-muted">·</span>
                        <span class="text-cyra-muted">{{ $project['priority_label'] }} priority</span>
                        @if ($project['budget_label'])
                            <span class="text-cyra-muted">·</span>
                            <span class="text-cyra-accent">{{ $project['budget_label'] }}</span>
                        @endif
                    </div>

                    <div>
                        <div class="mb-1 flex items-center justify-between text-xs text-cyra-muted">
                            <span>Progress</span>
                            <span>{{ $project['progress'] }}%</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-cyra-navy">
                            <div
                                class="h-full rounded-full bg-gradient-to-r from-cyra-primary to-cyra-accent"
                                style="width: {{ $project['progress'] }}%"
                            ></div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
                        <div class="text-cyra-muted">
                            <span>{{ $project['open_tasks_count'] }} open tasks</span>
                            @if ($project['manager'])
                                <span> · {{ $project['manager'] }}</span>
                            @endif
                        </div>
                        <x-ui.button href="{{ route('admin.projects.edit', $project['reference']) }}" variant="secondary" class="text-xs">
                            Manage
                        </x-ui.button>
                    </div>

                    @if (auth()->user()?->hasPermission('projects.manage'))
                        <form method="POST" action="{{ route('admin.projects.progress', $project['reference']) }}" class="flex items-center gap-2">
                            @csrf
                            <input
                                type="range"
                                name="progress"
                                min="0"
                                max="100"
                                value="{{ $project['progress'] }}"
                                class="w-full accent-cyra-primary"
                                onchange="this.form.submit()"
                            />
                        </form>
                    @endif
                </div>
            </x-ui.card>
        @empty
            <x-ui.card title="No Projects Found" description="Adjust filters or create a new project to begin tracking delivery.">
                <p class="text-sm text-cyra-muted">No active projects match the selected filters.</p>
            </x-ui.card>
        @endforelse
    </div>
@endsection
