@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'autocomplete' => null,
    'placeholder' => null,
])

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @if ($label)
        <x-ui.label :for="$name">{{ $label }}</x-ui.label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @class([
            'block w-full rounded-lg border bg-cyra-navy px-4 py-2.5 text-sm text-cyra-text placeholder:text-cyra-muted focus:border-cyra-primary focus:outline-none focus:ring-2 focus:ring-cyra-primary/30',
            'border-cyra-danger' => $errors->has($name),
            'border-cyra-border' => ! $errors->has($name),
        ])
        aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
        @if($errors->has($name)) aria-describedby="{{ $name }}-error" @endif
    />

    @error($name)
        <p id="{{ $name }}-error" class="text-sm text-cyra-danger" role="alert">{{ $message }}</p>
    @enderror
</div>
