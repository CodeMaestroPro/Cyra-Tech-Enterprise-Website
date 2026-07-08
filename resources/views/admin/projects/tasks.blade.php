@extends('layouts.admin')

@section('title', 'Tasks')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Projects', 'href' => route('admin.projects.index')],
        ['label' => 'Tasks'],
    ]" class="mb-6" />

    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <x-ui.section-heading
            title="Project Tasks"
            description="Cross-project task board for operational delivery, reviews, and milestone tracking."
        />

        <x-ui.button href="{{ route('admin.projects.index') }}" variant="secondary">Back to Projects</x-ui.button>
    </div>

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="Total Tasks" :value="(string) $board['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Pending" :value="(string) $board['summary']['pending']" accent="text-cyra-warning" />
        <x-ui.metric-card label="In Progress" :value="(string) $board['summary']['in_progress']" accent="text-cyra-primary" />
        <x-ui.metric-card label="Completed" :value="(string) $board['summary']['completed']" accent="text-cyra-success" />
        <x-ui.metric-card label="Overdue" :value="(string) $board['summary']['overdue']" accent="text-cyra-danger" />
    </div>

    <div class="mb-6 flex flex-wrap gap-2">
        <a
            href="{{ route('admin.projects.tasks', ['status' => 'all']) }}"
            @class([
                'rounded-full border px-3 py-1 text-xs font-medium transition',
                'border-cyra-primary bg-cyra-primary/15 text-cyra-accent' => $statusFilter === 'all' || $statusFilter === null,
                'border-cyra-border text-cyra-muted hover:border-cyra-primary/40 hover:text-cyra-text' => $statusFilter !== 'all' && $statusFilter !== null,
            ])
        >
            All Tasks
        </a>
        @foreach ($board['task_statuses'] as $status)
            <a
                href="{{ route('admin.projects.tasks', ['status' => $status['slug']]) }}"
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

    <x-ui.card title="Task Board" description="Active tasks across all Cyra-Tech delivery programs.">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyra-border text-sm">
                <thead>
                    <tr class="text-left text-cyra-muted">
                        <th class="px-3 py-2 font-medium">Task</th>
                        <th class="px-3 py-2 font-medium">Project</th>
                        <th class="px-3 py-2 font-medium">Status</th>
                        <th class="px-3 py-2 font-medium">Priority</th>
                        <th class="px-3 py-2 font-medium">Due</th>
                        <th class="px-3 py-2 font-medium">Assignee</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyra-border/60">
                    @forelse ($board['tasks'] as $task)
                        <tr>
                            <td class="px-3 py-3">
                                <p class="font-medium text-cyra-text">{{ $task['title'] }}</p>
                                <p class="font-mono text-[11px] text-cyra-muted">{{ $task['reference'] }}</p>
                            </td>
                            <td class="px-3 py-3">
                                @if ($task['project_reference'])
                                    <a href="{{ route('admin.projects.edit', $task['project_reference']) }}" class="text-cyra-accent hover:underline">
                                        {{ $task['project_name'] }}
                                    </a>
                                @endif
                            </td>
                            <td class="px-3 py-3">{{ $task['status_label'] }}</td>
                            <td class="px-3 py-3">{{ $task['priority_label'] }}</td>
                            <td @class(['px-3 py-3', 'text-cyra-danger' => $task['is_overdue']])>
                                {{ $task['due_date'] ?? '—' }}
                            </td>
                            <td class="px-3 py-3 text-cyra-muted">{{ $task['assignee'] ?? 'Unassigned' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-6 text-center text-cyra-muted">No tasks match the selected filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
