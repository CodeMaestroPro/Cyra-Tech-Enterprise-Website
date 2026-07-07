@extends('layouts.admin')

@section('title', 'Edit Media Asset')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Media Library', 'href' => route('admin.media.index')],
        ['label' => $asset['title']],
    ]" class="mb-6" />

    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <x-ui.section-heading
            :title="$asset['title']"
            :description="$asset['description'] ?? 'Update metadata, category, and availability for this media asset.'"
        />

        <x-ui.button href="{{ $asset['url'] }}" variant="secondary" target="_blank" rel="noopener">
            Open File
        </x-ui.button>
    </div>

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    <div class="grid gap-6 xl:grid-cols-[320px,minmax(0,1fr)]">
        <x-ui.card title="Preview">
            <div class="flex aspect-square items-center justify-center rounded-lg border border-cyra-border/70 bg-cyra-midnight/40 p-4">
                @if ($asset['is_image'])
                    <img
                        src="{{ $asset['url'] }}"
                        alt="{{ $asset['alt_text'] ?? $asset['title'] }}"
                        class="max-h-full max-w-full object-contain"
                    />
                @else
                    <div class="text-center">
                        <p class="text-4xl font-semibold uppercase text-cyra-accent">{{ $asset['extension'] }}</p>
                        <p class="mt-2 text-sm text-cyra-muted">{{ $asset['mime_type'] }}</p>
                    </div>
                @endif
            </div>

            <dl class="mt-6 space-y-3 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-cyra-muted">Filename</dt>
                    <dd class="text-right text-cyra-text">{{ $asset['filename'] }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-cyra-muted">Size</dt>
                    <dd class="text-right text-cyra-text">{{ $asset['size_label'] }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-cyra-muted">Uploaded By</dt>
                    <dd class="text-right text-cyra-text">{{ $asset['uploaded_by'] ?? 'System' }}</dd>
                </div>
            </dl>
        </x-ui.card>

        <x-ui.card title="Asset Details">
            <form method="POST" action="{{ route('admin.media.update', $asset['uuid']) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-6 md:grid-cols-2">
                    <x-ui.input name="title" label="Title" :value="$asset['title']" required />

                    <x-ui.select name="category" label="Category" required placeholder="Select category">
                        @foreach ($categories as $category)
                            <option
                                value="{{ $category['slug'] }}"
                                @selected(old('category', $asset['category']) === $category['slug'])
                            >
                                {{ $category['label'] }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.input name="alt_text" label="Alt Text" :value="$asset['alt_text']" />
                    <x-ui.input name="caption" label="Caption" :value="$asset['caption']" />
                    <x-ui.input name="sort_order" label="Sort Order" type="number" :value="$asset['sort_order']" />
                </div>

                <x-ui.textarea name="description" label="Description" rows="4">{{ old('description', $asset['description'] ?? '') }}</x-ui.textarea>

                <label class="flex items-center gap-3 text-sm text-cyra-text">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', $asset['is_active']))
                        class="rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary/30"
                    />
                    Asset is active and available to public integrations
                </label>

                <div class="flex flex-wrap gap-3">
                    <x-ui.button type="submit">Save Changes</x-ui.button>
                    <x-ui.button href="{{ route('admin.media.index') }}" variant="secondary">Back to Library</x-ui.button>
                </div>
            </form>

            @if (auth()->user()?->hasPermission('media.delete'))
                <form method="POST" action="{{ route('admin.media.destroy', $asset['uuid']) }}" class="mt-8 border-t border-cyra-border/70 pt-6">
                    @csrf
                    @method('DELETE')
                    <p class="mb-4 text-sm text-cyra-muted">Permanently remove this asset and its stored file.</p>
                    <x-ui.button type="submit" variant="danger">Delete Asset</x-ui.button>
                </form>
            @endif
        </x-ui.card>
    </div>
@endsection
