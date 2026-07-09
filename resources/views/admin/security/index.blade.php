@extends('layouts.admin')

@section('title', 'Security')

@section('content')
    <x-ui.breadcrumb :items="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Security']]" class="mb-6" />
    <x-ui.section-heading eyebrow="System Workspace" title="Security" description="{{ $security['description'] }}" class="cyra-section-heading" />

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="Posture Score" :value="$security['summary']['posture_score'].'%'" accent="text-cyra-success" />
        <x-ui.metric-card label="Controls Enabled" :value="$security['summary']['controls_enabled'].'/'.$security['summary']['total_controls']" accent="text-cyra-primary" />
        <x-ui.metric-card label="Active Users" :value="(string) $security['summary']['active_users']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Health Status" :value="ucfirst($security['summary']['health_status'])" accent="text-cyra-success" />
        <x-ui.metric-card label="Compliance Frameworks" :value="(string) $security['summary']['compliance_frameworks']" accent="text-cyra-purple" />
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)]">
        <x-ui.card title="Security Controls" description="Active platform security controls and monitoring status.">
            <div class="space-y-3">
                @foreach ($security['controls'] as $control)
                    <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-cyra-primary/10 text-cyra-accent">
                                    <x-ui.icon :name="$control['icon']" class="h-4 w-4" />
                                </span>
                                <div>
                                    <p class="font-medium text-cyra-text">{{ $control['label'] }}</p>
                                    <p class="text-xs text-cyra-muted">{{ $control['category'] }}</p>
                                    <p class="mt-1 text-xs leading-relaxed text-cyra-muted">{{ $control['description'] }}</p>
                                </div>
                            </div>
                            <x-ui.badge :variant="$control['status_variant']">{{ $control['status_label'] }}</x-ui.badge>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.card>

        <div class="space-y-6">
            <x-ui.card title="Infrastructure Health" description="Live health checks from platform optimization.">
                <div class="space-y-3">
                    @foreach ($security['health_checks'] as $check)
                        <div class="flex items-center justify-between rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3">
                            <div class="flex items-center gap-3">
                                <x-ui.icon :name="$check['icon']" class="h-4 w-4 text-cyra-accent" />
                                <div>
                                    <p class="text-sm font-medium text-cyra-text">{{ $check['label'] }}</p>
                                    <p class="text-xs text-cyra-muted">{{ $check['description'] }}</p>
                                </div>
                            </div>
                            <x-ui.badge :variant="$check['status'] === 'pass' ? 'success' : ($check['status'] === 'warn' ? 'warning' : 'danger')">{{ $check['status_label'] }}</x-ui.badge>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            @if (! empty($security['portal_security']['title']))
                <x-ui.card title="{{ $security['portal_security']['title'] }}" description="{{ $security['portal_security']['description'] ?? '' }}">
                    <ul class="space-y-2 text-sm text-cyra-muted">
                        @foreach ($security['portal_security']['points'] ?? [] as $point)
                            <li class="flex items-start gap-2"><x-ui.icon name="shield" class="mt-0.5 h-4 w-4 shrink-0 text-cyra-accent" /><span>{{ $point }}</span></li>
                        @endforeach
                    </ul>
                </x-ui.card>
            @endif
        </div>
    </div>

    <x-ui.card title="Compliance Readiness" description="Enterprise compliance frameworks and alignment status." class="mb-6">
        <div class="grid gap-4 md:grid-cols-2">
            @foreach ($security['compliance'] as $item)
                <article class="rounded-xl border border-cyra-border/70 bg-cyra-navy/50 p-4">
                    <div class="mb-2 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <x-ui.icon :name="$item['icon']" class="h-4 w-4 text-cyra-accent" />
                            <p class="font-medium text-cyra-text">{{ $item['framework'] }}</p>
                        </div>
                        <x-ui.badge variant="primary">{{ $item['status'] }}</x-ui.badge>
                    </div>
                    <p class="text-xs leading-relaxed text-cyra-muted">{{ $item['description'] }}</p>
                </article>
            @endforeach
        </div>
    </x-ui.card>

    <div class="grid gap-6 xl:grid-cols-2">
        <x-ui.card title="Quick Links" description="Related system workspaces.">
            <div class="grid gap-3">
                @foreach ($security['quick_links'] as $link)
                    <a href="{{ $link['href'] }}" class="group rounded-xl border border-cyra-border/70 bg-cyra-navy/50 p-4 transition hover:border-cyra-primary/40 hover:bg-cyra-primary/5">
                        <div class="flex items-start gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyra-primary/30 bg-cyra-primary/10 text-cyra-accent"><x-ui.icon :name="$link['icon']" class="h-4 w-4" /></span>
                            <div><p class="text-sm font-medium text-cyra-text group-hover:text-cyra-accent">{{ $link['label'] }}</p><p class="mt-1 text-xs text-cyra-muted">{{ $link['description'] }}</p></div>
                        </div>
                    </a>
                @endforeach
            </div>
        </x-ui.card>
        @if (count($security['workspace_notes']) > 0)
            <x-ui.card title="Workspace Notes" description="Security workspace guidance.">
                <ul class="space-y-2 text-sm text-cyra-muted">@foreach ($security['workspace_notes'] as $note)<li class="flex items-start gap-2"><x-ui.icon name="spark" class="mt-0.5 h-4 w-4 shrink-0 text-cyra-accent" /><span>{{ $note }}</span></li>@endforeach</ul>
            </x-ui.card>
        @endif
    </div>
@endsection
