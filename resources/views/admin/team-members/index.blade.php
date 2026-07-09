@extends('layouts.admin')

@section('title', 'Team Members')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Team Members'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="People Workspace"
        title="Team Members"
        description="{{ $team['description'] }}"
        class="cyra-section-heading"
    />

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="Total Members" :value="(string) $team['summary']['total_members']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Active" :value="(string) $team['summary']['active_members']" accent="text-cyra-success" />
        <x-ui.metric-card label="Inactive" :value="(string) $team['summary']['inactive_members']" accent="text-cyra-warning" />
        <x-ui.metric-card label="Featured" :value="(string) $team['summary']['featured_members']" accent="text-cyra-primary" />
        <x-ui.metric-card label="Departments" :value="(string) $team['summary']['departments']" accent="text-cyra-purple" />
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)]">
        <x-ui.card title="Team Directory" description="Global Cyra-Tech practitioners across engineering, consulting, design, delivery, and operations.">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cyra-border text-sm">
                    <thead>
                        <tr class="text-left text-cyra-muted">
                            <th class="px-4 py-3 font-medium">Member</th>
                            <th class="px-4 py-3 font-medium">Department</th>
                            <th class="px-4 py-3 font-medium">Location</th>
                            <th class="px-4 py-3 font-medium">Skills</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyra-border/70">
                        @foreach ($team['members'] as $member)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-start gap-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-cyra-primary/30 bg-cyra-primary/10 text-sm font-semibold text-cyra-accent">
                                            {{ $member['initials'] }}
                                        </span>
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <p class="font-medium text-cyra-text">{{ $member['name'] }}</p>
                                                @if ($member['is_featured'])
                                                    <x-ui.badge variant="primary">Featured</x-ui.badge>
                                                @endif
                                            </div>
                                            <p class="mt-0.5 text-xs text-cyra-muted">{{ $member['title'] }}</p>
                                            <p class="mt-1 line-clamp-2 text-xs text-cyra-muted">{{ $member['bio'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <x-ui.icon :name="$member['department_icon']" class="h-4 w-4 text-cyra-accent" />
                                        <span class="text-cyra-text">{{ $member['department_label'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-cyra-text">{{ $member['location'] ?: '—' }}</p>
                                    @if ($member['work_type'])
                                        <p class="mt-0.5 text-xs text-cyra-muted">{{ $member['work_type'] }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex max-w-xs flex-wrap gap-1">
                                        @foreach (array_slice($member['skills'], 0, 3) as $skill)
                                            <x-ui.badge variant="default">{{ $skill }}</x-ui.badge>
                                        @endforeach
                                        @if (count($member['skills']) > 3)
                                            <x-ui.badge variant="default">+{{ count($member['skills']) - 3 }}</x-ui.badge>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <x-ui.badge :variant="$member['is_active'] ? 'success' : 'default'">
                                        {{ $member['is_active'] ? 'Active' : 'Inactive' }}
                                    </x-ui.badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <div class="space-y-6">
            <x-ui.card title="Featured Members" description="Highlighted practitioners for programs and client engagements.">
                @if (count($team['featured_members']) === 0)
                    <x-ui.empty-state
                        title="No featured members"
                        description="Mark team members as featured in config to highlight them here."
                    />
                @else
                    <div class="space-y-3">
                        @foreach ($team['featured_members'] as $member)
                            <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-cyra-primary/30 bg-cyra-primary/10 text-sm font-semibold text-cyra-accent">
                                        {{ $member['initials'] }}
                                    </span>
                                    <div>
                                        <p class="font-medium text-cyra-text">{{ $member['name'] }}</p>
                                        <p class="text-xs text-cyra-muted">{{ $member['title'] }}</p>
                                        <p class="mt-1 text-xs text-cyra-accent">{{ $member['department_label'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-ui.card>

            <x-ui.card title="Departments" description="Team distribution across practice areas.">
                <div class="space-y-3">
                    @foreach ($team['departments'] as $department)
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-cyra-primary/10 text-cyra-accent">
                                    <x-ui.icon :name="$department['icon']" class="h-4 w-4" />
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-cyra-text">{{ $department['label'] }}</p>
                                    <p class="text-xs text-cyra-muted">{{ $department['active_count'] }}/{{ $department['count'] }} active</p>
                                </div>
                            </div>
                            <x-ui.badge variant="primary">{{ $department['count'] }}</x-ui.badge>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-2">
        <x-ui.card title="Quick Links" description="Related people and delivery workspaces.">
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($team['quick_links'] as $link)
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
                                @if ($link['description'])
                                    <p class="mt-1 text-xs text-cyra-muted">{{ $link['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </x-ui.card>

        @if (count($team['workspace_notes']) > 0)
            <x-ui.card title="Workspace Notes" description="How team member profiles are managed in the current release.">
                <ul class="space-y-2 text-sm text-cyra-muted">
                    @foreach ($team['workspace_notes'] as $note)
                        <li class="flex items-start gap-2">
                            <x-ui.icon name="spark" class="mt-0.5 h-4 w-4 shrink-0 text-cyra-accent" />
                            <span>{{ $note }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-ui.card>
        @endif
    </div>
@endsection
