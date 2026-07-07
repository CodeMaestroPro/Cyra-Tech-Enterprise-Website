@extends('layouts.app')

@section('title', 'Platform Initialization')

@section('content')
    @php
        $completedModules = collect($modules)->where('status', 'completed')->count();
    @endphp

    <main id="main-content" class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <header class="mb-8 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="mb-2 text-sm font-medium uppercase tracking-[0.2em] text-cyra-accent">
                    Module 01 Complete
                </p>
                <h1 class="text-3xl font-bold text-cyra-text sm:text-4xl">
                    <span class="cyra-gradient-text">{{ $platform['name'] }}</span> Enterprise Platform
                </h1>
                <p class="mt-3 max-w-2xl text-base text-cyra-muted">{{ $platform['tagline'] }}</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <x-ui.button href="{{ url('/api/v1/health') }}" variant="secondary" target="_blank" rel="noreferrer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                    Health Check
                </x-ui.button>
                <x-ui.button href="{{ url('/api/v1/platform/status') }}" variant="secondary" target="_blank" rel="noreferrer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2" />
                    </svg>
                    Platform API
                </x-ui.button>
            </div>
        </header>

        <section aria-label="Platform metrics" class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-ui.metric-card label="Platform Version" value="v{{ $platform['version'] }}" accent="text-cyra-accent">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </x-slot:icon>
            </x-ui.metric-card>

            <x-ui.metric-card
                label="Module Progress"
                value="{{ $completedModules }}/{{ count($modules) }}"
                accent="text-cyra-primary"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </x-slot:icon>
            </x-ui.metric-card>

            <x-ui.metric-card
                label="Completion"
                value="{{ $platform['modules']['progress'] }}%"
                accent="text-cyra-success"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot:icon>
            </x-ui.metric-card>

            <x-ui.metric-card
                label="Database"
                value="{{ $platform['database']['connected'] ? 'Connected' : 'Disconnected' }}"
                accent="{{ $platform['database']['connected'] ? 'text-cyra-success' : 'text-cyra-danger' }}"
                value-id="database-status-value"
                data-initial-connected="{{ $platform['database']['connected'] ? 'true' : 'false' }}"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                    </svg>
                </x-slot:icon>
            </x-ui.metric-card>
        </section>

        <div class="grid gap-6 lg:grid-cols-3">
            <x-ui.card
                title="Technology Stack"
                description="Enterprise foundation configured for modular development."
                class="lg:col-span-1"
            >
                <dl class="space-y-3 text-sm">
                    @foreach ($platform['stack'] as $key => $value)
                        <div class="flex items-center justify-between gap-4 border-b border-cyra-border/60 pb-3 last:border-0 last:pb-0">
                            <dt class="capitalize text-cyra-muted">{{ $key }}</dt>
                            <dd class="font-medium text-cyra-text">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-ui.card>

            <x-ui.card
                title="Development Roadmap"
                description="25-module build plan. Module 01 is complete and ready for Module 02."
                class="lg:col-span-2"
            >
                <div class="max-h-[28rem] space-y-2 overflow-y-auto pr-1">
                    @foreach ($modules as $module)
                        <article class="flex items-center justify-between gap-4 rounded-lg border border-cyra-border/70 bg-cyra-navy/60 px-4 py-3">
                            <p class="text-sm font-medium text-cyra-text">
                                <span class="mr-2 text-cyra-muted">#{{ str_pad($module['id'], 2, '0', STR_PAD_LEFT) }}</span>
                                {{ $module['name'] }}
                            </p>
                            <x-ui.status-badge :status="$module['status']" />
                        </article>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

        <footer class="mt-8 rounded-xl border border-cyra-success/30 bg-cyra-success/10 px-6 py-4">
            <p class="text-sm text-cyra-text">
                <strong class="font-semibold text-cyra-success">Module 01: Project Initialization</strong>
                is complete. The platform foundation is ready. Proceed with
                <strong class="font-semibold">Module 02: Authentication &amp; RBAC</strong> when ready.
            </p>
        </footer>

        <p id="platform-live-status" class="sr-only" aria-live="polite"></p>
    </main>
@endsection
