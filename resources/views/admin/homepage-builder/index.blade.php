@extends('layouts.admin')

@section('title', 'Homepage Builder')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Homepage Builder'],
    ]" class="mb-6" />

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <x-ui.section-heading
            eyebrow="Digital Headquarters"
            title="Homepage Builder"
            description="{{ $builder['description'] }}"
        />

        <x-ui.button href="{{ $builder['preview_url'] }}" target="_blank" rel="noopener noreferrer">
            View Live Homepage
        </x-ui.button>
    </div>

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card label="Total Sections" :value="(string) $builder['summary']['total_sections']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Active" :value="(string) $builder['summary']['active_sections']" accent="text-cyra-success" />
        <x-ui.metric-card label="Inactive" :value="(string) $builder['summary']['inactive_sections']" accent="text-cyra-warning" />
        <x-ui.metric-card label="Section Types" :value="(string) $builder['summary']['section_types']" accent="text-cyra-primary" />
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)]">
        <x-ui.card title="Homepage Sections" description="Ordered sections rendered on the public homepage at /.">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cyra-border text-sm">
                    <thead>
                        <tr class="text-left text-cyra-muted">
                            <th class="px-4 py-3 font-medium">Order</th>
                            <th class="px-4 py-3 font-medium">Section</th>
                            <th class="px-4 py-3 font-medium">Type</th>
                            <th class="px-4 py-3 font-medium">Content</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyra-border/70">
                        @foreach ($builder['sections'] as $section)
                            <tr>
                                <td class="px-4 py-4 text-cyra-muted">{{ $section['sort_order'] }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-start gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyra-border/70 bg-cyra-surface/60 text-cyra-accent">
                                            <x-ui.icon :name="$section['type_icon']" class="h-4 w-4" />
                                        </span>
                                        <div class="min-w-0">
                                            <p class="font-medium text-cyra-text">
                                                {{ $section['title'] ?: ucfirst(str_replace('-', ' ', $section['slug'])) }}
                                            </p>
                                            <p class="mt-0.5 text-xs text-cyra-muted">{{ $section['slug'] }}</p>
                                            @if ($section['eyebrow'])
                                                <p class="mt-1 text-xs text-cyra-accent">{{ $section['eyebrow'] }}</p>
                                            @endif
                                            @if ($section['description'])
                                                <p class="mt-1 line-clamp-2 text-xs text-cyra-muted">{{ $section['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <x-ui.badge variant="default">{{ $section['type_label'] }}</x-ui.badge>
                                </td>
                                <td class="px-4 py-4 text-cyra-muted">{{ $section['content_summary'] }}</td>
                                <td class="px-4 py-4">
                                    <x-ui.badge :variant="$section['is_active'] ? 'success' : 'default'">
                                        {{ $section['is_active'] ? 'Active' : 'Inactive' }}
                                    </x-ui.badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <div class="space-y-6">
            <x-ui.card title="SEO & Metadata" description="Search and social metadata for the homepage.">
                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-cyra-muted">Title</dt>
                        <dd class="mt-1 text-cyra-text">{{ $builder['seo']['title'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-cyra-muted">Description</dt>
                        <dd class="mt-1 leading-relaxed text-cyra-muted">{{ $builder['seo']['description'] }}</dd>
                    </div>
                    @if (count($builder['seo']['keywords'] ?? []) > 0)
                        <div>
                            <dt class="mb-2 text-xs font-semibold uppercase tracking-wide text-cyra-muted">Keywords</dt>
                            <dd class="flex flex-wrap gap-2">
                                @foreach ($builder['seo']['keywords'] as $keyword)
                                    <x-ui.badge variant="default">{{ $keyword }}</x-ui.badge>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                </dl>
            </x-ui.card>

            <x-ui.card title="Section Types" description="Component types used across the homepage layout.">
                <div class="space-y-3">
                    @foreach ($builder['section_types'] as $type)
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-cyra-primary/10 text-cyra-accent">
                                    <x-ui.icon :name="$type['icon']" class="h-4 w-4" />
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-cyra-text">{{ $type['label'] }}</p>
                                    <p class="text-xs text-cyra-muted">{{ $type['active_count'] }}/{{ $type['count'] }} active</p>
                                </div>
                            </div>
                            <x-ui.badge variant="primary">{{ $type['count'] }}</x-ui.badge>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-2">
        <x-ui.card title="Quick Links" description="Related workspaces for homepage content and assets.">
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($builder['quick_links'] as $link)
                    <a
                        href="{{ $link['href'] }}"
                        @if ($link['external']) target="_blank" rel="noopener noreferrer" @endif
                        class="group rounded-xl border border-cyra-border/70 bg-cyra-navy/50 p-4 transition hover:border-cyra-primary/40 hover:bg-cyra-primary/5"
                    >
                        <div class="flex items-start gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyra-primary/30 bg-cyra-primary/10 text-cyra-accent">
                                <x-ui.icon :name="$link['icon']" class="h-4 w-4" />
                            </span>
                            <div>
                                <p class="text-sm font-medium text-cyra-text group-hover:text-cyra-accent">{{ $link['label'] }}</p>
                                @if ($link['description'])
                                    <p class="mt-1 text-xs text-cyra-muted">{{ $link['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </x-ui.card>

        <x-ui.card title="Homepage Assets" description="Static media referenced by homepage section components.">
            @if (count($builder['assets']) === 0)
                <x-ui.empty-state
                    title="No assets configured"
                    description="Homepage asset references will appear here."
                />
            @else
                <div class="space-y-3">
                    @foreach ($builder['assets'] as $asset)
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-cyra-text">{{ $asset['label'] }}</p>
                                <p class="truncate text-xs text-cyra-muted">{{ $asset['path'] }}</p>
                            </div>
                            <x-ui.badge :variant="$asset['exists'] ? 'success' : 'warning'">
                                {{ $asset['exists'] ? 'Present' : 'Missing' }}
                            </x-ui.badge>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-ui.card>
    </div>

    @if (count($builder['workspace_notes']) > 0)
        <x-ui.card title="Workspace Notes" description="How homepage content is managed in the current release.">
            <ul class="space-y-2 text-sm text-cyra-muted">
                @foreach ($builder['workspace_notes'] as $note)
                    <li class="flex items-start gap-2">
                        <x-ui.icon name="spark" class="mt-0.5 h-4 w-4 shrink-0 text-cyra-accent" />
                        <span>{{ $note }}</span>
                    </li>
                @endforeach
            </ul>
        </x-ui.card>
    @endif
@endsection
