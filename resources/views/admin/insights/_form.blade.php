<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <x-ui.card title="Article Details">
        <div class="grid gap-6 md:grid-cols-2">
            <x-ui.input
                name="title"
                label="Title"
                :value="old('title', $article['title'] ?? null)"
                required
            />

            @if ($article === null)
                <x-ui.input
                    name="slug"
                    label="Slug"
                    placeholder="executive-guide-ai-readiness"
                    required
                />
            @else
                <div class="space-y-2">
                    <x-ui.label>Slug</x-ui.label>
                    <p class="rounded-lg border border-cyra-border bg-cyra-navy px-4 py-2.5 text-sm text-cyra-muted">
                        {{ $article['slug'] }}
                    </p>
                </div>
            @endif

            <x-ui.select name="category" label="Category" required placeholder="Select category">
                @foreach ($options['categories'] as $category)
                    <option
                        value="{{ $category['slug'] }}"
                        @selected(old('category', $article['category'] ?? '') === $category['slug'])
                    >
                        {{ $category['label'] }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.input
                name="author"
                label="Author"
                :value="old('author', $article['author'] ?? null)"
                required
            />

            <x-ui.input
                name="read_time"
                label="Read Time"
                placeholder="8 min read"
                :value="old('read_time', $article['read_time'] ?? null)"
                required
            />

            <x-ui.input
                name="published_label"
                label="Published Label"
                placeholder="March 2026"
                :value="old('published_label', $article['published_label'] ?? null)"
            />

            <x-ui.input
                name="badge"
                label="Badge"
                :value="old('badge', $article['badge'] ?? null)"
            />

            <x-ui.input
                name="icon"
                label="Icon Key"
                placeholder="ai"
                :value="old('icon', $article['icon'] ?? 'spark')"
            />

            <x-ui.input
                name="sort_order"
                label="Sort Order"
                type="number"
                min="0"
                :value="old('sort_order', $article['sort_order'] ?? 0)"
            />
        </div>

        <div class="mt-6 space-y-6">
            <x-ui.input
                name="tagline"
                label="Tagline"
                :value="old('tagline', $article['tagline'] ?? null)"
                required
            />

            <x-ui.input
                name="summary"
                label="Summary"
                :value="old('summary', $article['summary'] ?? null)"
                required
            />

            <x-ui.textarea
                name="description"
                label="Description"
                rows="6"
                required
            >{{ old('description', $article['description'] ?? '') }}</x-ui.textarea>
        </div>
    </x-ui.card>

    <x-ui.card title="Topics & Takeaways" description="Enter one item per line.">
        <div class="grid gap-6">
            <x-ui.textarea
                name="topics"
                label="Topics"
                rows="4"
            >{{ old('topics', $article['topics_text'] ?? '') }}</x-ui.textarea>

            <x-ui.textarea
                name="takeaways"
                label="Key Takeaways"
                rows="4"
            >{{ old('takeaways', $article['takeaways_text'] ?? '') }}</x-ui.textarea>
        </div>
    </x-ui.card>

    @if ($article !== null)
        <x-ui.card title="Visibility">
            <div class="flex flex-wrap gap-6">
                <label class="inline-flex items-center gap-2 text-sm text-cyra-muted">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', $article['is_active'] ?? true))
                        class="h-4 w-4 rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary/30"
                    />
                    <span>Active on Insights hub</span>
                </label>

                <label class="inline-flex items-center gap-2 text-sm text-cyra-muted">
                    <input
                        type="checkbox"
                        name="is_featured"
                        value="1"
                        @checked(old('is_featured', $article['is_featured'] ?? false))
                        class="h-4 w-4 rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary/30"
                    />
                    <span>Featured article</span>
                </label>
            </div>
        </x-ui.card>
    @else
        <x-ui.card title="Visibility">
            <div class="flex flex-wrap gap-6">
                <x-ui.checkbox name="is_active" label="Active on Insights hub" :checked="true" />
                <x-ui.checkbox name="is_featured" label="Featured article" :checked="false" />
            </div>
        </x-ui.card>
    @endif

    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button type="submit">
            {{ $article === null ? 'Create Article' : 'Save Changes' }}
        </x-ui.button>

        <x-ui.button href="{{ route('admin.insights.index') }}" variant="secondary">
            Cancel
        </x-ui.button>

        @if ($article !== null)
            <x-ui.button href="{{ $article['public_url'] }}" variant="outline" target="_blank" rel="noopener">
                View Public
            </x-ui.button>
        @endif

        @if ($article !== null && auth()->user()?->hasPermission('cms.delete'))
            <x-ui.button
                type="submit"
                form="delete-insight-form"
                variant="danger"
                class="ml-auto"
                onclick="return confirm('Delete this insight article permanently?')"
            >
                Delete Article
            </x-ui.button>
        @endif
    </div>
</form>

@if ($article !== null && auth()->user()?->hasPermission('cms.delete'))
    <form
        id="delete-insight-form"
        method="POST"
        action="{{ route('admin.insights.destroy', $article['slug']) }}"
        class="hidden"
    >
        @csrf
        @method('DELETE')
    </form>
@endif
