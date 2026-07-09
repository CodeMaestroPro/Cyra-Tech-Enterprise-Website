@extends('layouts.admin')

@section('title', 'Partners')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Partners'],
    ]" class="mb-6" />

    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <x-ui.section-heading
            eyebrow="Partner Hub"
            title="Partners"
            description="Manage partner programs displayed on the public Partner Hub."
        />

        @if (auth()->user()?->hasPermission('partners.create'))
            <x-ui.button href="{{ route('admin.partners.create') }}">Add Partner</x-ui.button>
        @endif
    </div>

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="danger" class="mb-6">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card label="Total Partners" :value="(string) $catalog['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Active" :value="(string) $catalog['summary']['active']" accent="text-cyra-success" />
        <x-ui.metric-card label="Inactive" :value="(string) $catalog['summary']['inactive']" accent="text-cyra-warning" />
        <x-ui.metric-card label="Featured" :value="(string) $catalog['summary']['featured']" accent="text-cyra-primary" />
    </div>

    <x-ui.card title="Partner Programs" description="Filter by category and manage Partner Hub listings.">
        <div class="mb-6 flex flex-wrap gap-2">
            <a
                href="{{ route('admin.partners.index', ['category' => 'all']) }}"
                @class([
                    'rounded-full border px-3 py-1 text-xs font-medium transition',
                    'border-cyra-primary bg-cyra-primary/15 text-cyra-accent' => $categoryFilter === 'all',
                    'border-cyra-border text-cyra-muted hover:border-cyra-primary/40 hover:text-cyra-text' => $categoryFilter !== 'all',
                ])
            >
                All
            </a>
            @foreach ($catalog['categories'] as $category)
                <a
                    href="{{ route('admin.partners.index', ['category' => $category['slug']]) }}"
                    @class([
                        'rounded-full border px-3 py-1 text-xs font-medium transition',
                        'border-cyra-primary bg-cyra-primary/15 text-cyra-accent' => $categoryFilter === $category['slug'],
                        'border-cyra-border text-cyra-muted hover:border-cyra-primary/40 hover:text-cyra-text' => $categoryFilter !== $category['slug'],
                    ])
                >
                    {{ $category['label'] }}
                </a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyra-border text-sm">
                <thead>
                    <tr class="text-left text-cyra-muted">
                        <th class="px-4 py-3 font-medium">Partner</th>
                        <th class="px-4 py-3 font-medium">Category</th>
                        <th class="px-4 py-3 font-medium">Type</th>
                        <th class="px-4 py-3 font-medium">Region</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        @if (auth()->user()?->hasPermission('partners.update'))
                            <th class="px-4 py-3 font-medium">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyra-border/70">
                    @forelse ($catalog['programs'] as $program)
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyra-primary/30 bg-cyra-primary/10 text-cyra-accent">
                                        <x-ui.icon :name="$program['icon']" class="h-4 w-4" />
                                    </span>
                                    <div>
                                        <p class="font-medium text-cyra-text">{{ $program['title'] }}</p>
                                        <p class="text-xs text-cyra-muted">{{ $program['slug'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <x-ui.badge variant="primary">{{ $program['category_label'] }}</x-ui.badge>
                            </td>
                            <td class="px-4 py-4 text-cyra-text">{{ $program['partner_type'] }}</td>
                            <td class="px-4 py-4 text-cyra-muted">{{ $program['region'] }}</td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <x-ui.badge :variant="$program['is_active'] ? 'success' : 'default'">
                                        {{ $program['is_active'] ? 'Active' : 'Inactive' }}
                                    </x-ui.badge>
                                    @if ($program['is_featured'])
                                        <x-ui.badge variant="warning">Featured</x-ui.badge>
                                    @endif
                                </div>
                            </td>
                            @if (auth()->user()?->hasPermission('partners.update'))
                                <td class="px-4 py-4">
                                    <x-ui.button href="{{ $program['edit_url'] }}" variant="secondary" size="sm">
                                        Edit
                                    </x-ui.button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-cyra-muted">
                                No partners found for this category.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
