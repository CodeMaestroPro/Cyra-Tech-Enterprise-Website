@extends('layouts.admin')

@section('title', 'Client Portal')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Client Portal'],
    ]" class="mb-6" />

    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <x-ui.section-heading
            eyebrow="Executive"
            title="Client Portal"
            :description="$catalog['description']"
        />

        @if (auth()->user()?->hasPermission('cms.create'))
            <x-ui.button href="{{ route('admin.client-portal.create') }}">Add Client Account</x-ui.button>
        @endif
    </div>

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 md:grid-cols-3">
        <x-ui.metric-card label="Total Accounts" :value="(string) $catalog['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Active" :value="(string) $catalog['summary']['active']" accent="text-cyra-success" />
        <x-ui.metric-card label="Inactive" :value="(string) $catalog['summary']['inactive']" accent="text-cyra-warning" />
    </div>

    <x-ui.card title="Client Accounts">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyra-border text-sm">
                <thead>
                    <tr class="text-left text-cyra-muted">
                        <th class="px-4 py-3 font-medium">Account</th>
                        <th class="px-4 py-3 font-medium">Industry</th>
                        <th class="px-4 py-3 font-medium">Engagements</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        @if (auth()->user()?->hasPermission('cms.update'))
                            <th class="px-4 py-3 font-medium">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyra-border/70">
                    @forelse ($catalog['accounts'] as $account)
                        <tr>
                            <td class="px-4 py-4">
                                <p class="font-medium text-cyra-text">{{ $account['name'] }}</p>
                                <p class="text-xs text-cyra-muted">{{ $account['slug'] }}</p>
                            </td>
                            <td class="px-4 py-4 text-cyra-muted">{{ $account['industry'] ?? '—' }}</td>
                            <td class="px-4 py-4 text-cyra-muted">{{ $account['engagements_count'] }}</td>
                            <td class="px-4 py-4">
                                <x-ui.badge :variant="$account['is_active'] ? 'success' : 'default'">
                                    {{ $account['is_active'] ? 'Active' : 'Inactive' }}
                                </x-ui.badge>
                            </td>
                            @if (auth()->user()?->hasPermission('cms.update'))
                                <td class="px-4 py-4">
                                    <x-ui.button href="{{ $account['edit_url'] }}" variant="secondary" size="sm">Edit</x-ui.button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-cyra-muted">No client accounts yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
