@extends('layouts.admin')

@section('title', $catalog['config']['label'])

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => $catalog['config']['label']],
    ]" class="mb-6" />

    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <x-ui.section-heading
            :eyebrow="$catalog['config']['label']"
            :title="$catalog['config']['label']"
            :description="$catalog['config']['description']"
        />

        @if (auth()->user()?->hasPermission('cms.create'))
            <x-ui.button href="{{ route('admin.'.$module.'.create') }}">
                Add {{ $catalog['config']['singular'] }}
            </x-ui.button>
        @endif
    </div>

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="danger" class="mb-6">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card label="Total" :value="(string) $catalog['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Active" :value="(string) $catalog['summary']['active']" accent="text-cyra-success" />
        <x-ui.metric-card label="Inactive" :value="(string) $catalog['summary']['inactive']" accent="text-cyra-warning" />
        <x-ui.metric-card label="Featured" :value="(string) $catalog['summary']['featured']" accent="text-cyra-primary" />
    </div>

    <x-ui.card :title="$catalog['config']['label']" description="Manage catalog entries and visibility on the public site.">
        @if (count($catalog['categories']) > 0)
            <div class="mb-6 flex flex-wrap gap-2">
                <a
                    href="{{ route('admin.'.$module.'.index', ['category' => 'all']) }}"
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
                        href="{{ route('admin.'.$module.'.index', ['category' => $category['slug']]) }}"
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
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyra-border text-sm">
                <thead>
                    <tr class="text-left text-cyra-muted">
                        <th class="px-4 py-3 font-medium">Title</th>
                        @if (count($catalog['categories']) > 0)
                            <th class="px-4 py-3 font-medium">Category</th>
                        @endif
                        <th class="px-4 py-3 font-medium">Status</th>
                        @if (auth()->user()?->hasPermission('cms.update'))
                            <th class="px-4 py-3 font-medium">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyra-border/70">
                    @forelse ($catalog['records'] as $record)
                        <tr>
                            <td class="px-4 py-4">
                                <p class="font-medium text-cyra-text">{{ $record['display_title'] }}</p>
                                <p class="text-xs text-cyra-muted">{{ $record['slug'] }}</p>
                            </td>
                            @if (count($catalog['categories']) > 0)
                                <td class="px-4 py-4">
                                    @if (! empty($record['category_label']))
                                        <x-ui.badge variant="primary">{{ $record['category_label'] }}</x-ui.badge>
                                    @else
                                        <span class="text-cyra-muted">—</span>
                                    @endif
                                </td>
                            @endif
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <x-ui.badge :variant="$record['is_active'] ? 'success' : 'default'">
                                        {{ $record['is_active'] ? 'Active' : 'Inactive' }}
                                    </x-ui.badge>
                                    @if ($record['is_featured'])
                                        <x-ui.badge variant="warning">Featured</x-ui.badge>
                                    @endif
                                </div>
                            </td>
                            @if (auth()->user()?->hasPermission('cms.update'))
                                <td class="px-4 py-4">
                                    <x-ui.button href="{{ $record['edit_url'] }}" variant="secondary" size="sm">
                                        Edit
                                    </x-ui.button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-cyra-muted">
                                No {{ strtolower($catalog['config']['label']) }} found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
