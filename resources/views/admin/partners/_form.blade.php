<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <x-ui.card title="Partner Details">
        <div class="grid gap-6 md:grid-cols-2">
            <x-ui.input
                name="title"
                label="Title"
                :value="old('title', $program['title'] ?? null)"
                required
            />

            @if ($program === null)
                <x-ui.input
                    name="slug"
                    label="Slug"
                    placeholder="cloud-alliance-program"
                    required
                />
            @else
                <div class="space-y-2">
                    <x-ui.label>Slug</x-ui.label>
                    <p class="rounded-lg border border-cyra-border bg-cyra-navy px-4 py-2.5 text-sm text-cyra-muted">
                        {{ $program['slug'] }}
                    </p>
                </div>
            @endif

            <x-ui.select name="category" label="Category" required placeholder="Select category">
                @foreach ($options['categories'] as $category)
                    <option
                        value="{{ $category['slug'] }}"
                        @selected(old('category', $program['category'] ?? '') === $category['slug'])
                    >
                        {{ $category['label'] }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.input
                name="partner_type"
                label="Partner Type"
                :value="old('partner_type', $program['partner_type'] ?? null)"
                required
            />

            <x-ui.input
                name="region"
                label="Region"
                :value="old('region', $program['region'] ?? null)"
                required
            />

            <x-ui.input
                name="engagement_model"
                label="Engagement Model"
                :value="old('engagement_model', $program['engagement_model'] ?? null)"
                required
            />

            <x-ui.input
                name="badge"
                label="Badge"
                :value="old('badge', $program['badge'] ?? null)"
            />

            <x-ui.input
                name="icon"
                label="Icon Key"
                placeholder="cloud"
                :value="old('icon', $program['icon'] ?? 'spark')"
            />

            <x-ui.input
                name="sort_order"
                label="Sort Order"
                type="number"
                min="0"
                :value="old('sort_order', $program['sort_order'] ?? 0)"
            />
        </div>

        <div class="mt-6 space-y-6">
            <x-ui.input
                name="tagline"
                label="Tagline"
                :value="old('tagline', $program['tagline'] ?? null)"
                required
            />

            <x-ui.input
                name="summary"
                label="Summary"
                :value="old('summary', $program['summary'] ?? null)"
                required
            />

            <x-ui.textarea
                name="description"
                label="Description"
                rows="5"
                required
            >{{ old('description', $program['description'] ?? '') }}</x-ui.textarea>
        </div>
    </x-ui.card>

    <x-ui.card title="Program Content" description="Enter one item per line for list fields.">
        <div class="grid gap-6">
            <x-ui.textarea
                name="benefits"
                label="Benefits"
                rows="4"
            >{{ old('benefits', $program['benefits_text'] ?? '') }}</x-ui.textarea>

            <x-ui.textarea
                name="requirements"
                label="Requirements"
                rows="4"
            >{{ old('requirements', $program['requirements_text'] ?? '') }}</x-ui.textarea>

            <x-ui.textarea
                name="enablement"
                label="Enablement"
                rows="4"
            >{{ old('enablement', $program['enablement_text'] ?? '') }}</x-ui.textarea>
        </div>
    </x-ui.card>

    @if ($program !== null)
        <x-ui.card title="Visibility">
            <div class="flex flex-wrap gap-6">
                <label class="inline-flex items-center gap-2 text-sm text-cyra-muted">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', $program['is_active'] ?? true))
                        class="h-4 w-4 rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary/30"
                    />
                    <span>Active on Partner Hub</span>
                </label>

                <label class="inline-flex items-center gap-2 text-sm text-cyra-muted">
                    <input
                        type="checkbox"
                        name="is_featured"
                        value="1"
                        @checked(old('is_featured', $program['is_featured'] ?? false))
                        class="h-4 w-4 rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary/30"
                    />
                    <span>Featured partner</span>
                </label>
            </div>
        </x-ui.card>
    @else
        <x-ui.card title="Visibility">
            <div class="flex flex-wrap gap-6">
                <x-ui.checkbox name="is_active" label="Active on Partner Hub" :checked="true" />
                <x-ui.checkbox name="is_featured" label="Featured partner" :checked="false" />
            </div>
        </x-ui.card>
    @endif

    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button type="submit">
            {{ $program === null ? 'Create Partner' : 'Save Changes' }}
        </x-ui.button>

        <x-ui.button href="{{ route('admin.partners.index') }}" variant="secondary">
            Cancel
        </x-ui.button>

        @if ($program !== null)
            <x-ui.button href="{{ $program['public_url'] }}" variant="outline" target="_blank" rel="noopener">
                View Public Page
            </x-ui.button>
        @endif

        @if ($program !== null && auth()->user()?->hasPermission('partners.delete'))
            <x-ui.button
                type="submit"
                form="delete-partner-form"
                variant="danger"
                class="ml-auto"
                onclick="return confirm('Delete this partner permanently?')"
            >
                Delete Partner
            </x-ui.button>
        @endif
    </div>
</form>

@if ($program !== null && auth()->user()?->hasPermission('partners.delete'))
    <form
        id="delete-partner-form"
        method="POST"
        action="{{ route('admin.partners.destroy', $program['slug']) }}"
        class="hidden"
    >
        @csrf
        @method('DELETE')
    </form>
@endif
