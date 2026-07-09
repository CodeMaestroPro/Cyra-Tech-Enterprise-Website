@extends('layouts.admin')

@section('title', 'Users & Roles')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Users & Roles'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="System Workspace"
        title="Users & Roles"
        description="{{ $access['description'] }}"
        class="cyra-section-heading"
    />

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div></div>
        @if (auth()->user()?->can('create', App\Models\User::class))
            <x-ui.button href="{{ route('admin.users-roles.create') }}">Create User</x-ui.button>
        @endif
    </div>

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="danger" class="mb-6">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-6">
        <x-ui.metric-card label="Total Users" :value="(string) $access['summary']['total_users']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Active Users" :value="(string) $access['summary']['active_users']" accent="text-cyra-success" />
        <x-ui.metric-card label="Inactive Users" :value="(string) $access['summary']['inactive_users']" accent="text-cyra-warning" />
        <x-ui.metric-card label="Roles" :value="(string) $access['summary']['total_roles']" accent="text-cyra-primary" />
        <x-ui.metric-card label="System Roles" :value="(string) $access['summary']['system_roles']" accent="text-cyra-purple" />
        <x-ui.metric-card label="Permissions" :value="(string) $access['summary']['total_permissions']" accent="text-cyra-success" />
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)]">
        <x-ui.card title="Platform Users" description="Authenticated accounts with role assignments and access status.">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cyra-border text-sm">
                    <thead>
                        <tr class="text-left text-cyra-muted">
                            <th class="px-4 py-3 font-medium">User</th>
                            <th class="px-4 py-3 font-medium">Roles</th>
                            <th class="px-4 py-3 font-medium">Permissions</th>
                            <th class="px-4 py-3 font-medium">Last Login</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            @if (auth()->user()?->hasPermission('users.update'))
                                <th class="px-4 py-3 font-medium">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyra-border/70">
                        @foreach ($access['users'] as $user)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-start gap-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-cyra-primary/30 bg-cyra-primary/10 text-sm font-semibold text-cyra-accent">
                                            {{ $user['initials'] }}
                                        </span>
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <p class="font-medium text-cyra-text">{{ $user['name'] }}</p>
                                                @if ($user['is_client'])
                                                    <x-ui.badge variant="warning">Client</x-ui.badge>
                                                @endif
                                            </div>
                                            <p class="text-xs text-cyra-muted">{{ $user['email'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($user['roles'] as $role)
                                            <x-ui.badge variant="primary">{{ $role }}</x-ui.badge>
                                        @empty
                                            <x-ui.badge variant="default">Unassigned</x-ui.badge>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-cyra-text">{{ $user['permission_count'] }}</td>
                                <td class="px-4 py-4">
                                    <p class="text-cyra-text">{{ $user['last_login_at'] ?? '—' }}</p>
                                    <p class="mt-0.5 text-xs text-cyra-muted">{{ $user['last_login_ago'] }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <x-ui.badge :variant="$user['is_active'] ? 'success' : 'default'">
                                        {{ $user['is_active'] ? 'Active' : 'Inactive' }}
                                    </x-ui.badge>
                                </td>
                                @if (auth()->user()?->hasPermission('users.update'))
                                    <td class="px-4 py-4">
                                        <x-ui.button href="{{ $user['edit_url'] }}" variant="secondary" size="sm">
                                            Edit
                                        </x-ui.button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <x-ui.card title="Role Distribution" description="User count per RBAC role.">
            <div class="space-y-3">
                @foreach ($access['role_distribution'] as $role)
                    <div class="flex items-center justify-between gap-3 rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-cyra-primary/10 text-cyra-accent">
                                <x-ui.icon :name="$role['icon']" class="h-4 w-4" />
                            </span>
                            <p class="text-sm font-medium text-cyra-text">{{ $role['name'] }}</p>
                        </div>
                        <x-ui.badge variant="primary">{{ $role['user_count'] }}</x-ui.badge>
                    </div>
                @endforeach
            </div>
        </x-ui.card>
    </div>

    <x-ui.card title="Roles & Permissions" description="System roles and their assigned permission scopes." class="mb-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($access['roles'] as $role)
                <article class="rounded-xl border border-cyra-border/70 bg-cyra-navy/50 p-5">
                    <div class="mb-3 flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-cyra-border/70 bg-cyra-surface/60 text-cyra-accent">
                                <x-ui.icon :name="$role['icon']" class="h-5 w-5" />
                            </span>
                            <div>
                                <p class="font-medium text-cyra-text">{{ $role['name'] }}</p>
                                <p class="text-xs text-cyra-muted">{{ $role['slug'] }}</p>
                            </div>
                        </div>
                        @if ($role['is_system'])
                            <x-ui.badge variant="default">System</x-ui.badge>
                        @endif
                    </div>

                    <p class="mb-4 text-xs leading-relaxed text-cyra-muted">{{ $role['description'] }}</p>

                    <div class="mb-3 flex items-center justify-between text-xs">
                        <span class="text-cyra-muted">{{ $role['user_count'] }} {{ $role['user_count'] === 1 ? 'user' : 'users' }}</span>
                        <span class="font-medium text-cyra-text">{{ $role['permission_count'] }} permissions</span>
                    </div>

                    <div class="flex flex-wrap gap-1">
                        @foreach ($role['permissions'] as $permission)
                            <x-ui.badge variant="default">{{ $permission }}</x-ui.badge>
                        @endforeach
                        @if ($role['permissions_overflow'] > 0)
                            <x-ui.badge variant="default">+{{ $role['permissions_overflow'] }}</x-ui.badge>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </x-ui.card>

    <div class="mb-6 grid gap-6 xl:grid-cols-2">
        <x-ui.card title="Permission Groups" description="All platform permissions organized by functional area.">
            <div class="space-y-4">
                @foreach ($access['permission_groups'] as $group)
                    <div class="rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-4">
                        <div class="mb-3 flex items-center justify-between gap-2">
                            <p class="text-sm font-medium text-cyra-text">{{ $group['group'] }}</p>
                            <x-ui.badge variant="primary">{{ $group['count'] }}</x-ui.badge>
                        </div>
                        <div class="flex flex-wrap gap-1">
                            @foreach ($group['permissions'] as $permission)
                                <x-ui.badge variant="default" title="{{ $permission['slug'] }}">{{ $permission['name'] }}</x-ui.badge>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.card>

        <div class="space-y-6">
            <x-ui.card title="Quick Links" description="Related system administration workspaces.">
                <div class="grid gap-3">
                    @foreach ($access['quick_links'] as $link)
                        <a
                            href="{{ $link['href'] }}"
                            class="group rounded-xl border border-cyra-border/70 bg-cyra-navy/50 p-4 transition hover:border-cyra-primary/40 hover:bg-cyra-primary/5"
                        >
                            <div class="flex items-start gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyra-primary/30 bg-cyra-primary/10 text-cyra-accent">
                                    <x-ui.icon :name="$link['icon']" class="h-4 w-4" />
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-cyra-text group-hover:text-cyra-accent">{{ $link['label'] }}</p>
                                    <p class="mt-1 text-xs text-cyra-muted">{{ $link['description'] }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </x-ui.card>

            @if (count($access['workspace_notes']) > 0)
                <x-ui.card title="Workspace Notes" description="How RBAC is managed in the current release.">
                    <ul class="space-y-2 text-sm text-cyra-muted">
                        @foreach ($access['workspace_notes'] as $note)
                            <li class="flex items-start gap-2">
                                <x-ui.icon name="spark" class="mt-0.5 h-4 w-4 shrink-0 text-cyra-accent" />
                                <span>{{ $note }}</span>
                            </li>
                        @endforeach
                    </ul>
                </x-ui.card>
            @endif
        </div>
    </div>
@endsection
