@extends('layouts.admin')

@section('title', 'Strategic Roadmap')

@section('content')
    @php
        $initiativeVariants = [
            'on-track' => 'success',
            'in-progress' => 'primary',
            'planned' => 'default',
            'at-risk' => 'warning',
            'completed' => 'success',
        ];
        $milestoneVariants = [
            'completed' => 'success',
            'on-track' => 'success',
            'in-progress' => 'primary',
            'planned' => 'default',
            'at-risk' => 'warning',
        ];
        $phaseVariants = [
            'completed' => 'success',
            'in_progress' => 'primary',
            'in-progress' => 'primary',
            'planned' => 'default',
        ];
    @endphp

    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Strategic Roadmap'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="Executive Workspace"
        title="Strategic Roadmap"
        description="Enterprise vision, strategic pillars, quarterly initiatives, and platform evolution through {{ $roadmap['summary']['horizon'] }}."
        class="cyra-section-heading"
    />

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="Platform Progress" :value="$roadmap['summary']['platform_progress'].'%'" accent="text-cyra-success" />
        <x-ui.metric-card label="Modules Complete" :value="$roadmap['summary']['modules_completed'].'/'.$roadmap['summary']['modules_total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Active Projects" :value="(string) $roadmap['summary']['active_projects']" accent="text-cyra-primary" />
        <x-ui.metric-card label="Initiatives In Flight" :value="(string) $roadmap['summary']['initiatives_in_flight']" accent="text-cyra-purple" />
        <x-ui.metric-card label="Pillar Progress" :value="$roadmap['summary']['pillar_progress'].'%'" accent="text-cyra-warning" />
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)]">
        <x-ui.card title="Vision & Executive Summary" description="Strategic direction for the Cyra-Tech enterprise platform.">
            <div class="mb-6 flex items-start gap-4 rounded-xl border border-cyra-primary/30 bg-cyra-primary/5 p-5">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-cyra-primary/30 bg-cyra-primary/10 text-cyra-accent">
                    <x-ui.icon name="map" class="h-6 w-6" />
                </span>
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-cyra-accent">Vision {{ $roadmap['summary']['horizon'] }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-cyra-text">{{ $roadmap['vision'] }}</p>
                </div>
            </div>
            <p class="text-sm leading-relaxed text-cyra-muted">{{ $roadmap['executive_summary'] }}</p>
        </x-ui.card>

        <x-ui.card title="Strategic Pillars" description="Core focus areas driving enterprise outcomes.">
            <div class="space-y-4">
                @foreach ($roadmap['pillars'] as $pillar)
                    <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4">
                        <div class="flex items-start gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyra-border/70 bg-cyra-surface/60 text-cyra-accent">
                                <x-ui.icon :name="$pillar['icon'] ?? 'spark'" class="h-4 w-4" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <p class="font-medium text-cyra-text">{{ $pillar['title'] }}</p>
                                    <x-ui.badge :variant="$phaseVariants[$pillar['status'] ?? 'planned'] ?? 'default'">
                                        {{ str_replace('_', ' ', $pillar['status'] ?? 'planned') }}
                                    </x-ui.badge>
                                </div>
                                <p class="mt-1 text-xs text-cyra-muted">{{ $pillar['description'] }}</p>
                                <div class="mt-3">
                                    <div class="mb-1 flex items-center justify-between text-xs">
                                        <span class="text-cyra-muted">Progress</span>
                                        <span class="font-medium text-cyra-text">{{ $pillar['progress'] }}%</span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-cyra-border/60">
                                        <div class="h-full rounded-full bg-gradient-to-r from-cyra-primary to-cyra-accent" style="width: {{ $pillar['progress'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.card>
    </div>

    <x-ui.card title="Platform Evolution Phases" description="Module delivery phases and the next enterprise scale horizon." class="mb-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($roadmap['phases'] as $phase)
                <article class="rounded-xl border border-cyra-border/70 bg-cyra-navy/50 p-5">
                    <div class="mb-3 flex items-center justify-between gap-2">
                        <x-ui.badge :variant="$phaseVariants[$phase['status'] ?? 'planned'] ?? 'default'">
                            {{ str_replace('_', ' ', $phase['status'] ?? 'planned') }}
                        </x-ui.badge>
                        <span class="text-xs text-cyra-muted">{{ $phase['period'] }}</span>
                    </div>
                    <h3 class="text-base font-semibold text-cyra-text">{{ $phase['label'] }}</h3>
                    <p class="mt-2 text-xs leading-relaxed text-cyra-muted">{{ $phase['description'] }}</p>
                    <div class="mt-4">
                        <div class="mb-1 flex items-center justify-between text-xs">
                            <span class="text-cyra-muted">Completion</span>
                            <span class="text-cyra-text">{{ $phase['progress'] }}%</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-cyra-border/60">
                            <div @class([
                                'h-full rounded-full',
                                'bg-cyra-success' => ($phase['status'] ?? '') === 'completed',
                                'bg-gradient-to-r from-cyra-primary to-cyra-accent' => ($phase['status'] ?? '') !== 'completed',
                            ]) style="width: {{ $phase['progress'] }}%"></div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </x-ui.card>

    <div class="mb-6 grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(0,1fr)]">
        <x-ui.card title="Quarterly Initiatives" description="Prioritized programs by quarter with ownership and delivery status.">
            <div class="space-y-6">
                @foreach ($roadmap['quarters'] as $quarter)
                    <section class="rounded-xl border border-cyra-border/70 bg-cyra-navy/40 p-5">
                        <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <x-ui.icon name="calendar" class="h-4 w-4 text-cyra-accent" />
                                    <h3 class="text-base font-semibold text-cyra-text">{{ $quarter['label'] }}</h3>
                                </div>
                                <p class="mt-1 text-xs text-cyra-muted">{{ $quarter['period'] }} · {{ $quarter['theme'] ?? 'Strategic programs' }}</p>
                            </div>
                            <x-ui.badge :variant="$phaseVariants[$quarter['status'] ?? 'planned'] ?? 'default'">
                                {{ str_replace('_', ' ', $quarter['status'] ?? 'planned') }}
                            </x-ui.badge>
                        </div>

                        <div class="space-y-3">
                            @foreach ($quarter['initiatives'] as $initiative)
                                <div class="rounded-lg border border-cyra-border/60 bg-cyra-navy/60 px-4 py-3">
                                    <div class="flex flex-wrap items-start justify-between gap-3">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-cyra-text">{{ $initiative['title'] }}</p>
                                            <p class="mt-1 text-xs text-cyra-muted">Owner: {{ $initiative['owner'] }}</p>
                                        </div>
                                        <x-ui.badge :variant="$initiativeVariants[$initiative['status'] ?? 'planned'] ?? 'default'">
                                            {{ str_replace('-', ' ', $initiative['status'] ?? 'planned') }}
                                        </x-ui.badge>
                                    </div>
                                    @if (($initiative['progress'] ?? 0) > 0)
                                        <div class="mt-3">
                                            <div class="mb-1 flex items-center justify-between text-xs">
                                                <span class="text-cyra-muted">Progress</span>
                                                <span class="text-cyra-text">{{ $initiative['progress'] }}%</span>
                                            </div>
                                            <div class="h-1.5 overflow-hidden rounded-full bg-cyra-border/60">
                                                <div class="h-full rounded-full bg-cyra-primary" style="width: {{ $initiative['progress'] }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </x-ui.card>

        <div class="space-y-6">
            <x-ui.card title="Key Milestones" description="Executive checkpoints across the strategic horizon.">
                <div class="space-y-3">
                    @foreach ($roadmap['milestones'] as $milestone)
                        <div class="flex items-start gap-3 rounded-lg border border-cyra-border/70 px-4 py-3">
                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-cyra-primary/10 text-cyra-accent">
                                <x-ui.icon name="spark" class="h-4 w-4" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-cyra-text">{{ $milestone['title'] }}</p>
                                <p class="mt-1 text-xs text-cyra-muted">{{ $milestone['target'] }} · {{ $milestone['owner'] }}</p>
                            </div>
                            <x-ui.badge :variant="$milestoneVariants[$milestone['status'] ?? 'planned'] ?? 'default'">
                                {{ str_replace('-', ' ', $milestone['status'] ?? 'planned') }}
                            </x-ui.badge>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.card title="Delivery Portfolio" description="Active client programs aligned to the strategic roadmap.">
                @if (count($roadmap['projects']) === 0)
                    <x-ui.empty-state title="No active projects" description="Project milestones will appear here once programs are tracked." />
                @else
                    <div class="space-y-3">
                        @foreach ($roadmap['projects'] as $project)
                            <a
                                href="{{ route('admin.projects.edit', $project['reference']) }}"
                                class="block rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3 transition hover:border-cyra-primary/40 hover:bg-cyra-surface/40"
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-medium text-cyra-text">{{ $project['name'] }}</p>
                                        <p class="mt-1 text-xs text-cyra-muted">{{ $project['client_name'] }} · {{ $project['phase_label'] ?? $project['phase'] }}</p>
                                    </div>
                                    <x-ui.badge :variant="$project['status_variant'] ?? 'primary'">{{ $project['status_label'] ?? $project['status'] }}</x-ui.badge>
                                </div>
                                <div class="mt-3">
                                    <div class="mb-1 flex items-center justify-between text-xs">
                                        <span class="text-cyra-muted">Progress</span>
                                        <span class="text-cyra-text">{{ $project['progress'] }}%</span>
                                    </div>
                                    <div class="h-1.5 overflow-hidden rounded-full bg-cyra-border/60">
                                        <div class="h-full rounded-full bg-cyra-primary" style="width: {{ $project['progress'] }}%"></div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </x-ui.card>
        </div>
    </div>

    <x-ui.card title="Platform Module Roadmap" description="Full 25-module build plan with current completion status.">
        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($roadmap['modules'] as $module)
                <article class="flex items-center justify-between gap-4 rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3">
                    <p class="text-sm font-medium text-cyra-text">
                        <span class="mr-2 text-cyra-muted">#{{ str_pad((string) $module['id'], 2, '0', STR_PAD_LEFT) }}</span>
                        {{ $module['name'] }}
                    </p>
                    <x-ui.status-badge :status="$module['status']" />
                </article>
            @endforeach
        </div>
    </x-ui.card>
@endsection
