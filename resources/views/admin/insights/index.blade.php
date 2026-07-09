@extends('layouts.admin')

@section('title', 'Insights')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Insights'],
    ]" class="mb-6" />

    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <x-ui.section-heading
            eyebrow="Growth Workspace"
            title="Insights"
            description="{{ $insights['description'] }}"
        />

        @if (auth()->user()?->hasPermission('cms.create'))
            <x-ui.button href="{{ route('admin.insights.create') }}">Add Article</x-ui.button>
        @endif
    </div>

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="danger" class="mb-6">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 md:grid-cols-3">
        <x-ui.metric-card label="Total Articles" :value="(string) $insights['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Active" :value="(string) $insights['summary']['active']" accent="text-cyra-success" />
        <x-ui.metric-card label="Featured" :value="(string) $insights['summary']['featured']" accent="text-cyra-primary" />
    </div>

    <x-ui.card title="Article Library" description="Thought leadership content published on the public Insights hub.">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyra-border text-sm">
                <thead>
                    <tr class="text-left text-cyra-muted">
                        <th class="px-4 py-3 font-medium">Article</th>
                        <th class="px-4 py-3 font-medium">Category</th>
                        <th class="px-4 py-3 font-medium">Author</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyra-border/70">
                    @forelse ($insights['articles'] as $article)
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyra-primary/30 bg-cyra-primary/10 text-cyra-accent">
                                        <x-ui.icon :name="$article['icon']" class="h-4 w-4" />
                                    </span>
                                    <div>
                                        <p class="font-medium text-cyra-text">{{ $article['title'] }}</p>
                                        <p class="text-xs text-cyra-muted">{{ $article['slug'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <x-ui.badge variant="primary">{{ $article['category_label'] }}</x-ui.badge>
                            </td>
                            <td class="px-4 py-4 text-cyra-muted">{{ $article['author'] }}</td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <x-ui.badge :variant="$article['is_active'] ? 'success' : 'default'">
                                        {{ $article['is_active'] ? 'Active' : 'Inactive' }}
                                    </x-ui.badge>
                                    @if ($article['is_featured'])
                                        <x-ui.badge variant="warning">Featured</x-ui.badge>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @if (auth()->user()?->hasPermission('cms.update'))
                                        <x-ui.button href="{{ $article['edit_url'] }}" variant="secondary" size="sm">
                                            Edit
                                        </x-ui.button>
                                    @endif
                                    <x-ui.button href="{{ $article['public_url'] }}" variant="outline" size="sm" target="_blank" rel="noopener">
                                        View Public
                                    </x-ui.button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-cyra-muted">
                                No insight articles found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
