@props(['id', 'labels' => []])

<div {{ $attributes->merge(['class' => 'space-y-4']) }} data-cyra-tabs="{{ $id }}">
    <div role="tablist" aria-label="Tabs" class="flex flex-wrap gap-2 border-b border-cyra-border">
        @foreach ($labels as $index => $label)
            <button
                type="button"
                role="tab"
                aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                data-cyra-tab-trigger="{{ $index }}"
                @class([
                    'border-b-2 px-4 py-2 text-sm font-medium transition-colors',
                    'cyra-tab-active' => $index === 0,
                    'cyra-tab-inactive' => $index !== 0,
                ])
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{ $slot }}
</div>
