@extends('layouts.admin')

@section('title', 'Media Library')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Media Library'],
    ]" class="mb-6" />

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <x-ui.section-heading
            eyebrow="Module 20 Complete"
            title="Media Library"
            description="Upload, organize, and manage brand, marketing, portfolio, and document assets for the Cyra-Tech platform."
        />
    </div>

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="danger" class="mb-6">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 md:grid-cols-3">
        <x-ui.metric-card label="Total Assets" :value="(string) $catalog['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Active" :value="(string) $catalog['summary']['active']" accent="text-cyra-success" />
        <x-ui.metric-card label="Inactive" :value="(string) $catalog['summary']['inactive']" accent="text-cyra-warning" />
    </div>

    @if (auth()->user()?->hasPermission('media.upload'))
        <x-ui.card title="Upload Asset" description="Add images, SVG brand files, PDFs, or text documents to the library." class="mb-6">
            <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="grid gap-6 md:grid-cols-2">
                @csrf

                <div class="md:col-span-2">
                    <x-ui.label for="file">File</x-ui.label>
                    <input
                        id="file"
                        name="file"
                        type="file"
                        required
                        class="mt-2 block w-full rounded-lg border border-cyra-border bg-cyra-navy px-4 py-2.5 text-sm text-cyra-text file:mr-4 file:rounded-md file:border-0 file:bg-cyra-primary file:px-3 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-cyra-primary-hover"
                    />
                    @error('file')
                        <p class="mt-2 text-sm text-cyra-danger" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <x-ui.input name="title" label="Title" placeholder="Optional display title" />
                <x-ui.select name="category" label="Category" required placeholder="Select category">
                    @foreach ($catalog['categories'] as $category)
                        <option value="{{ $category['slug'] }}" @selected(old('category') === $category['slug'])>
                            {{ $category['label'] }}
                        </option>
                    @endforeach
                </x-ui.select>

                <x-ui.input name="alt_text" label="Alt Text" placeholder="Accessibility description for images" />
                <x-ui.input name="caption" label="Caption" />

                <div class="md:col-span-2">
                    <x-ui.textarea name="description" label="Description" rows="3">{{ old('description') }}</x-ui.textarea>
                </div>

                <div class="md:col-span-2">
                    <x-ui.button type="submit">Upload Asset</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    @endif

    <x-ui.card title="Asset Library" description="Filter by category and manage metadata for CMS and marketing integrations.">
        <div class="mb-6 flex flex-wrap gap-2">
            <a
                href="{{ route('admin.media.index', ['category' => 'all']) }}"
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
                    href="{{ route('admin.media.index', ['category' => $category['slug']]) }}"
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

        @if (count($catalog['assets']) === 0)
            <x-ui.empty-state
                title="No assets found"
                description="Upload a file or adjust your category filter."
            />
        @else
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($catalog['assets'] as $asset)
                    <article class="overflow-hidden rounded-xl border border-cyra-border/70 bg-cyra-navy/60">
                        <div class="flex aspect-video items-center justify-center border-b border-cyra-border/70 bg-cyra-midnight/40 p-4">
                            @if ($asset['is_image'])
                                <img
                                    src="{{ $asset['url'] }}"
                                    alt="{{ $asset['alt_text'] ?? $asset['title'] }}"
                                    class="max-h-full max-w-full object-contain"
                                />
                            @else
                                <div class="text-center">
                                    <p class="text-3xl font-semibold uppercase text-cyra-accent">{{ $asset['extension'] }}</p>
                                    <p class="mt-2 text-xs text-cyra-muted">{{ $asset['mime_type'] }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-3 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-medium text-cyra-text">{{ $asset['title'] }}</h3>
                                    <p class="mt-1 text-xs text-cyra-muted">{{ $asset['category_label'] }} · {{ $asset['size_label'] }}</p>
                                </div>
                                <x-ui.badge :variant="$asset['is_active'] ? 'success' : 'warning'">
                                    {{ $asset['is_active'] ? 'Active' : 'Inactive' }}
                                </x-ui.badge>
                            </div>

                            @if (! empty($asset['caption']))
                                <p class="text-sm text-cyra-muted">{{ $asset['caption'] }}</p>
                            @endif

                            <div class="flex flex-wrap gap-2">
                                @if (auth()->user()?->hasPermission('media.update'))
                                    <x-ui.button href="{{ route('admin.media.edit', $asset['uuid']) }}" size="sm" variant="secondary">
                                        Edit
                                    </x-ui.button>
                                @endif
                                <x-ui.button href="{{ $asset['url'] }}" size="sm" variant="ghost" target="_blank" rel="noopener">
                                    Open
                                </x-ui.button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </x-ui.card>
@endsection
