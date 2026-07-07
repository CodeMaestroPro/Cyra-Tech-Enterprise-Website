@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <section aria-label="Dashboard overview">
        <header class="mb-8">
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-cyra-accent">Module 02 Complete</p>
            <h2 class="mt-2 text-2xl font-bold text-cyra-text">Authentication &amp; RBAC Active</h2>
            <p class="mt-2 max-w-3xl text-sm text-cyra-muted">
                Secure session authentication, role-based permissions, and admin access controls are now enabled.
            </p>
        </header>

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.metric-card label="Platform Version" value="v{{ $platform['version'] }}" accent="text-cyra-accent">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </x-slot:icon>
            </x-ui.metric-card>

            <x-ui.metric-card label="Your Role" value="{{ $user->getPrimaryRoleName() ?? 'Unassigned' }}" accent="text-cyra-primary">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </x-slot:icon>
            </x-ui.metric-card>

            <x-ui.metric-card label="Permissions" value="{{ $user->getPermissionSlugs()->count() }}" accent="text-cyra-success">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </x-slot:icon>
            </x-ui.metric-card>
        </div>

        <x-ui.card title="Assigned Permissions" description="Effective permissions for your account." class="mt-6">
            <div class="flex flex-wrap gap-2">
                @forelse ($user->getPermissionSlugs() as $permission)
                    <x-ui.status-badge status="completed">{{ $permission }}</x-ui.status-badge>
                @empty
                    <p class="text-sm text-cyra-muted">No permissions assigned.</p>
                @endforelse
            </div>
        </x-ui.card>

        <x-ui.card title="Platform Modules" class="mt-6">
            <div class="grid gap-2 md:grid-cols-2">
                @foreach ($modules as $module)
                    <div class="flex items-center justify-between rounded-lg border border-cyra-border/70 bg-cyra-navy/60 px-4 py-3">
                        <span class="text-sm text-cyra-text">{{ $module['name'] }}</span>
                        <x-ui.status-badge :status="$module['status']" />
                    </div>
                @endforeach
            </div>
        </x-ui.card>
    </section>
@endsection
