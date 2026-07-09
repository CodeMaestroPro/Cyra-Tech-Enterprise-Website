@extends('layouts.admin')

@section('title', 'Navigation')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Navigation'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="Digital Headquarters"
        title="Admin Navigation"
        :description="$navigation['description']"
        class="mb-8"
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 md:grid-cols-3">
        <x-ui.metric-card label="Total Items" :value="(string) $navigation['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Active" :value="(string) $navigation['summary']['active']" accent="text-cyra-success" />
        <x-ui.metric-card label="Available" :value="(string) $navigation['summary']['available']" accent="text-cyra-primary" />
    </div>

    @foreach ($navigation['groups'] as $groupLabel => $items)
        <x-ui.card :title="$groupLabel" class="mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cyra-border text-sm">
                    <thead>
                        <tr class="text-left text-cyra-muted">
                            <th class="px-4 py-3 font-medium">Label</th>
                            <th class="px-4 py-3 font-medium">Route</th>
                            <th class="px-4 py-3 font-medium">Order</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            @if (auth()->user()?->hasPermission('cms.update'))
                                <th class="px-4 py-3 font-medium">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyra-border/70">
                        @foreach ($items as $item)
                            <tr>
                                <td class="px-4 py-4 font-medium text-cyra-text">{{ $item['label'] }}</td>
                                <td class="px-4 py-4 text-xs text-cyra-muted">{{ $item['route_name'] ?? $item['url'] ?? '—' }}</td>
                                <td class="px-4 py-4 text-cyra-muted">{{ $item['sort_order'] }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        <x-ui.badge :variant="$item['is_active'] ? 'success' : 'default'">
                                            {{ $item['is_active'] ? 'Active' : 'Inactive' }}
                                        </x-ui.badge>
                                        <x-ui.badge :variant="$item['is_available'] ? 'primary' : 'warning'">
                                            {{ $item['is_available'] ? 'Available' : 'Unavailable' }}
                                        </x-ui.badge>
                                    </div>
                                </td>
                                @if (auth()->user()?->hasPermission('cms.update'))
                                    <td class="px-4 py-4">
                                        <x-ui.button href="{{ $item['edit_url'] }}" variant="secondary" size="sm">
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
    @endforeach
@endsection
