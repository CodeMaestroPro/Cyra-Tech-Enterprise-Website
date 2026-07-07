@extends('layouts.admin')

@section('title', 'CMS Pages')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Pages'],
    ]" class="mb-6" />

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <x-ui.section-heading
            eyebrow="Module 19 Complete"
            title="Content Management"
            description="Manage published legal pages, policy content, and draft editorial pages from the Cyra-Tech CMS."
        />

        @if (auth()->user()?->hasPermission('cms.create'))
            <x-ui.button href="{{ route('admin.cms.create') }}">Create Page</x-ui.button>
        @endif
    </div>

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="danger" class="mb-6">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 md:grid-cols-3">
        <x-ui.metric-card label="Total Pages" :value="(string) $catalog['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Published" :value="(string) $catalog['summary']['published']" accent="text-cyra-success" />
        <x-ui.metric-card label="Drafts" :value="(string) $catalog['summary']['draft']" accent="text-cyra-warning" />
    </div>

    <x-ui.card title="Pages" description="Filter by status and manage draft or published content.">
        <div class="mb-6 flex flex-wrap gap-2">
            @foreach (['all' => 'All', 'published' => 'Published', 'draft' => 'Draft'] as $value => $label)
                <a
                    href="{{ route('admin.cms.index', ['status' => $value]) }}"
                    @class([
                        'rounded-full border px-3 py-1 text-xs font-medium transition',
                        'border-cyra-primary bg-cyra-primary/15 text-cyra-accent' => $statusFilter === $value,
                        'border-cyra-border text-cyra-muted hover:border-cyra-primary/40 hover:text-cyra-text' => $statusFilter !== $value,
                    ])
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if (count($catalog['pages']) === 0)
            <x-ui.empty-state
                title="No pages found"
                description="Create a page or adjust your status filter."
            />
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cyra-border text-sm">
                    <thead>
                        <tr class="text-left text-cyra-muted">
                            <th class="px-4 py-3 font-medium">Title</th>
                            <th class="px-4 py-3 font-medium">Slug</th>
                            <th class="px-4 py-3 font-medium">Template</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Updated</th>
                            <th class="px-4 py-3 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyra-border/70">
                        @foreach ($catalog['pages'] as $page)
                            <tr>
                                <td class="px-4 py-4">
                                    <p class="font-medium text-cyra-text">{{ $page['title'] }}</p>
                                    @if (! empty($page['excerpt']))
                                        <p class="mt-1 text-xs text-cyra-muted">{{ $page['excerpt'] }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-cyra-muted">{{ $page['slug'] }}</td>
                                <td class="px-4 py-4 text-cyra-muted">{{ $page['template_label'] }}</td>
                                <td class="px-4 py-4">
                                    <x-ui.badge :variant="$page['status'] === 'published' ? 'success' : 'warning'">
                                        {{ ucfirst($page['status']) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-4 py-4 text-cyra-muted">
                                    {{ $page['updated_at'] ? \Illuminate\Support\Carbon::parse($page['updated_at'])->format('M j, Y') : '—' }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @if (auth()->user()?->hasPermission('cms.update'))
                                            <x-ui.button href="{{ route('admin.cms.edit', $page['slug']) }}" size="sm" variant="secondary">
                                                Edit
                                            </x-ui.button>
                                        @endif
                                        @if ($page['status'] === 'published')
                                            <x-ui.button href="{{ route('pages.show', $page['slug']) }}" size="sm" variant="ghost" target="_blank">
                                                View
                                            </x-ui.button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-ui.card>
@endsection
