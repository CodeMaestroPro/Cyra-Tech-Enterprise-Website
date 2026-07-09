@extends('layouts.admin')

@section('title', 'Applicants')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Applicants'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="People Workspace"
        title="Applicants"
        description="{{ $applicants['description'] }}"
        class="cyra-section-heading"
    />

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-6">
        <x-ui.metric-card label="Total Applications" :value="(string) $applicants['summary']['total_applicants']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Active Pipeline" :value="(string) $applicants['summary']['active_pipeline']" accent="text-cyra-primary" />
        <x-ui.metric-card label="New" :value="(string) $applicants['summary']['new_applications']" accent="text-cyra-warning" />
        <x-ui.metric-card label="In Interview" :value="(string) $applicants['summary']['in_interview']" accent="text-cyra-purple" />
        <x-ui.metric-card label="Offers" :value="(string) $applicants['summary']['offers_outstanding']" accent="text-cyra-success" />
        <x-ui.metric-card label="Hired" :value="(string) $applicants['summary']['hired']" accent="text-cyra-success" />
    </div>

    <x-ui.card title="Hiring Pipeline" description="Application volume by recruitment stage." class="mb-6">
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-6">
            @foreach ($applicants['pipeline'] as $stage)
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
        <x-ui.card title="Application Queue" description="All career applications ordered by most recent submission.">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cyra-border text-sm">
                    <thead>
                        <tr class="text-left text-cyra-muted">
                            <th class="px-4 py-3 font-medium">Applicant</th>
                            <th class="px-4 py-3 font-medium">Role</th>
                            <th class="px-4 py-3 font-medium">Source</th>
                            <th class="px-4 py-3 font-medium">Applied</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            @if (auth()->user()?->hasPermission('crm.update'))
                                <th class="px-4 py-3 font-medium">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyra-border/70">
                        @foreach ($applicants['applicants'] as $applicant)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-start gap-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-cyra-primary/30 bg-cyra-primary/10 text-sm font-semibold text-cyra-accent">
                                            {{ $applicant['initials'] }}
                                        </span>
                                        <div class="min-w-0">
                                            <p class="font-medium text-cyra-text">{{ $applicant['name'] }}</p>
                                            <p class="text-xs text-cyra-muted">{{ $applicant['email'] }}</p>
                                            <p class="mt-0.5 font-mono text-xs text-cyra-accent">{{ $applicant['reference'] }}</p>
                                            @if ($applicant['notes'])
                                                <p class="mt-1 line-clamp-2 text-xs text-cyra-muted">{{ $applicant['notes'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-cyra-text">{{ $applicant['role_title'] }}</p>
                                    @if ($applicant['location'])
                                        <p class="mt-0.5 text-xs text-cyra-muted">{{ $applicant['location'] }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-cyra-muted">{{ $applicant['source_label'] }}</td>
                                <td class="px-4 py-4">
                                    <p class="text-cyra-text">{{ $applicant['applied_at'] }}</p>
                                    <p class="mt-0.5 text-xs text-cyra-muted">{{ $applicant['applied_ago'] }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <x-ui.badge :variant="$applicant['status_variant']">{{ $applicant['status_label'] }}</x-ui.badge>
                                </td>
                                @if (auth()->user()?->hasPermission('crm.update'))
                                    <td class="px-4 py-4">
                                        <x-ui.button href="{{ $applicant['edit_url'] }}" variant="secondary" size="sm">Edit</x-ui.button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <div class="space-y-6">
            <x-ui.card title="Recent Applications" description="Latest submissions requiring review.">
                <div class="space-y-3">
                    @foreach ($applicants['recent_applications'] as $applicant)
                        <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-medium text-cyra-text">{{ $applicant['name'] }}</p>
                                    <p class="text-xs text-cyra-muted">{{ $applicant['role_title'] }}</p>
                                    <p class="mt-1 text-xs text-cyra-accent">{{ $applicant['applied_ago'] }}</p>
                                </div>
                                <x-ui.badge :variant="$applicant['status_variant']">{{ $applicant['status_label'] }}</x-ui.badge>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.card title="Applications by Role" description="Volume per open position.">
                <div class="space-y-3">
                    @foreach ($applicants['role_breakdown'] as $role)
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-cyra-text">{{ $role['role_title'] }}</p>
                                <p class="text-xs text-cyra-muted">{{ $role['active_count'] }} active / {{ $role['count'] }} total</p>
                            </div>
                            <x-ui.badge variant="primary">{{ $role['count'] }}</x-ui.badge>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-2">
        <x-ui.card title="Quick Links" description="Related talent and people workspaces.">
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($applicants['quick_links'] as $link)
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

        @if (count($applicants['workspace_notes']) > 0)
            <x-ui.card title="Workspace Notes" description="How applications are managed in the current release.">
                <ul class="space-y-2 text-sm text-cyra-muted">
                    @foreach ($applicants['workspace_notes'] as $note)
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
