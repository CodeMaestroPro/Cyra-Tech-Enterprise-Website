@extends('layouts.admin')

@section('title', 'Enterprise Settings')

@section('content')
    <x-ui.breadcrumb :items="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Enterprise Settings']]" class="mb-6" />
    <x-ui.section-heading eyebrow="System Workspace" title="Enterprise Settings" description="{{ $settings['description'] }}" class="cyra-section-heading" />

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="Version" :value="$settings['summary']['platform_version']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Environment" :value="ucfirst($settings['summary']['environment'])" accent="text-cyra-primary" />
        <x-ui.metric-card label="Modules" :value="$settings['summary']['modules_completed'].'/'.$settings['summary']['modules_total']" accent="text-cyra-success" />
        <x-ui.metric-card label="Setting Groups" :value="(string) $settings['summary']['setting_groups']" accent="text-cyra-purple" />
        <x-ui.metric-card label="Database" :value="$settings['summary']['database_connected']" accent="text-cyra-warning" />
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)]">
        <div class="space-y-6">
            @foreach ($settings['groups'] as $group)
                <x-ui.card :title="$group['label']" :description="$group['description']">
                    <dl class="divide-y divide-cyra-border/70">
                        @foreach ($group['settings'] as $setting)
                            <div class="flex flex-col gap-1 py-3 sm:flex-row sm:items-center sm:justify-between">
                                <dt class="text-sm text-cyra-muted">{{ $setting['label'] }}</dt>
                                <dd class="text-sm font-medium text-cyra-text">{{ $setting['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </x-ui.card>
            @endforeach
        </div>

        <div class="space-y-6">
            <x-ui.card title="Technology Stack" description="Core platform stack components.">
                <dl class="space-y-3 text-sm">
                    @foreach ($settings['stack'] as $key => $value)
                        <div class="flex justify-between gap-4 rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3">
                            <dt class="text-cyra-muted">{{ ucfirst($key) }}</dt>
                            <dd class="font-medium text-cyra-text">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-ui.card>
            @if (count($settings['workspace_notes']) > 0)
                <x-ui.card title="Workspace Notes" description="Settings management guidance.">
                    <ul class="space-y-2 text-sm text-cyra-muted">@foreach ($settings['workspace_notes'] as $note)<li class="flex items-start gap-2"><x-ui.icon name="spark" class="mt-0.5 h-4 w-4 shrink-0 text-cyra-accent" /><span>{{ $note }}</span></li>@endforeach</ul>
                </x-ui.card>
            @endif
        </div>
    </div>
@endsection
