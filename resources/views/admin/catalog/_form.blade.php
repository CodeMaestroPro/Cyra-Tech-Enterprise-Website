<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <x-ui.card title="{{ $config['label'] }} Details">
        <div class="grid gap-6 md:grid-cols-2">
            @foreach ($options['fields'] as $field)
                @php
                    $name = $field['name'];
                    $type = $field['type'] ?? 'text';
                    $grid = ($field['grid'] ?? 'full') === 'full' ? 'md:col-span-2' : '';
                    $value = old($name, $record[$name] ?? $field['default'] ?? null);
                @endphp

                @if ($type === 'slug' && $record !== null)
                    <div class="{{ $grid }} space-y-2">
                        <x-ui.label>{{ $field['label'] }}</x-ui.label>
                        <p class="rounded-lg border border-cyra-border bg-cyra-navy px-4 py-2.5 text-sm text-cyra-muted">
                            {{ $record['slug'] }}
                        </p>
                    </div>
                @elseif ($type === 'category')
                    <div class="{{ $grid }}">
                        <x-ui.select name="{{ $name }}" :label="$field['label']" :required="($field['required'] ?? false)" placeholder="Select category">
                            @foreach ($options['categories'] as $category)
                                <option value="{{ $category['slug'] }}" @selected($value === $category['slug'])>
                                    {{ $category['label'] }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </div>
                @elseif ($type === 'select')
                    <div class="{{ $grid }}">
                        <x-ui.select name="{{ $name }}" :label="$field['label']" :required="($field['required'] ?? false)" placeholder="Select option">
                            @foreach ($field['options'] ?? [] as $optionValue => $optionLabel)
                                <option value="{{ $optionValue }}" @selected($value === $optionValue)>
                                    {{ $optionLabel }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </div>
                @elseif ($type === 'textarea' || $type === 'list')
                    <div class="{{ $grid }}">
                        <x-ui.textarea
                            name="{{ $name }}"
                            :label="$field['label']"
                            :rows="$field['rows'] ?? 4"
                            :required="($field['required'] ?? false)"
                        >{{ $type === 'list' ? old($name, $record[$name.'_text'] ?? '') : old($name, $record[$name] ?? '') }}</x-ui.textarea>
                    </div>
                @elseif ($type === 'number')
                    <div class="{{ $grid }}">
                        <x-ui.input
                            name="{{ $name }}"
                            :label="$field['label']"
                            type="number"
                            min="0"
                            :value="$value"
                            :required="($field['required'] ?? false)"
                        />
                    </div>
                @else
                    <div class="{{ $grid }}">
                        <x-ui.input
                            name="{{ $name }}"
                            :label="$field['label']"
                            :type="match ($type) {
                                'email' => 'email',
                                'url' => 'url',
                                default => 'text',
                            }"
                            :value="$value"
                            :required="($field['required'] ?? false)"
                        />
                    </div>
                @endif
            @endforeach
        </div>
    </x-ui.card>

    <x-ui.card title="Visibility">
        <div class="flex flex-wrap gap-6">
            <label class="inline-flex items-center gap-2 text-sm text-cyra-muted">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', $record['is_active'] ?? true))
                    class="h-4 w-4 rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary/30"
                />
                <span>Active on public site</span>
            </label>

            <label class="inline-flex items-center gap-2 text-sm text-cyra-muted">
                <input
                    type="checkbox"
                    name="is_featured"
                    value="1"
                    @checked(old('is_featured', $record['is_featured'] ?? false))
                    class="h-4 w-4 rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary/30"
                />
                <span>Featured</span>
            </label>
        </div>
    </x-ui.card>

    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button type="submit">
            {{ $record === null ? 'Create '.$config['singular'] : 'Save Changes' }}
        </x-ui.button>

        <x-ui.button href="{{ route('admin.'.$module.'.index') }}" variant="secondary">
            Cancel
        </x-ui.button>

        @if ($record !== null && ! empty($record['public_url']))
            <x-ui.button href="{{ $record['public_url'] }}" variant="outline" target="_blank" rel="noopener">
                View Public Page
            </x-ui.button>
        @endif

        @if ($record !== null && auth()->user()?->hasPermission('cms.update'))
            <x-ui.button
                type="submit"
                form="delete-catalog-form"
                variant="danger"
                class="ml-auto"
                onclick="return confirm('Delete this entry permanently?')"
            >
                Delete {{ $config['singular'] }}
            </x-ui.button>
        @endif
    </div>
</form>

@if ($record !== null && auth()->user()?->hasPermission('cms.update'))
    <form
        id="delete-catalog-form"
        method="POST"
        action="{{ route('admin.'.$module.'.destroy', $record['slug']) }}"
        class="hidden"
    >
        @csrf
        @method('DELETE')
    </form>
@endif
