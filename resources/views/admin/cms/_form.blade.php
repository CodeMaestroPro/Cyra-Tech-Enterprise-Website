<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <x-ui.card title="Page Details">
        <div class="grid gap-6 md:grid-cols-2">
            <x-ui.input
                name="title"
                label="Title"
                :value="$page['title'] ?? null"
                required
            />

            @if ($page === null)
                <x-ui.input
                    name="slug"
                    label="Slug"
                    placeholder="privacy-policy"
                    required
                />
            @else
                <div class="space-y-2">
                    <x-ui.label>Slug</x-ui.label>
                    <p class="rounded-lg border border-cyra-border bg-cyra-navy px-4 py-2.5 text-sm text-cyra-muted">
                        {{ $page['slug'] }}
                    </p>
                </div>
            @endif

            <x-ui.select name="template" label="Template" required placeholder="Select template">
                @foreach ($templates as $template)
                    <option
                        value="{{ $template['slug'] }}"
                        @selected(old('template', $page['template'] ?? '') === $template['slug'])
                    >
                        {{ $template['label'] }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select name="status" label="Status" required placeholder="Select status">
                @foreach ($statuses as $status)
                    <option
                        value="{{ $status }}"
                        @selected(old('status', $page['status'] ?? 'draft') === $status)
                    >
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.input
                name="eyebrow"
                label="Eyebrow"
                :value="$page['eyebrow'] ?? null"
            />

            <x-ui.input
                name="sort_order"
                label="Sort Order"
                type="number"
                :value="$page['sort_order'] ?? 0"
            />
        </div>

        <div class="mt-6 space-y-6">
            <x-ui.input
                name="excerpt"
                label="Excerpt"
                :value="$page['excerpt'] ?? null"
            />

            <x-ui.textarea
                name="description"
                label="Description"
                rows="3"
            >{{ old('description', $page['description'] ?? '') }}</x-ui.textarea>

            <x-ui.textarea
                name="body"
                label="Body Content"
                rows="12"
                placeholder="Enter page content. Separate paragraphs with a blank line."
            >{{ old('body', $page['body'] ?? '') }}</x-ui.textarea>
        </div>
    </x-ui.card>

    <x-ui.card title="SEO Metadata">
        <div class="grid gap-6 md:grid-cols-2">
            <x-ui.input
                name="seo_title"
                label="SEO Title"
                :value="$page['seo']['title'] ?? null"
            />

            <x-ui.input
                name="seo_description"
                label="SEO Description"
                :value="$page['seo']['description'] ?? null"
            />
        </div>
    </x-ui.card>

    <div class="flex flex-wrap gap-3">
        <x-ui.button type="submit">
            {{ $page === null ? 'Create Page' : 'Save Changes' }}
        </x-ui.button>
        <x-ui.button href="{{ route('admin.cms.index') }}" variant="secondary">
            Cancel
        </x-ui.button>
    </div>
</form>
