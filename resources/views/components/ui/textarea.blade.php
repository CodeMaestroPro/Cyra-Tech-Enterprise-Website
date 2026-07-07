@props([
    'label' => null,
    'name',
    'rows' => 4,
    'placeholder' => null,
    'required' => false,
])

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @if ($label)
        <x-ui.label :for="$name">{{ $label }}</x-ui.label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @class([
            'cyra-input min-h-[6rem] resize-y',
            'cyra-input-error' => $errors->has($name),
        ])
        aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
    >{{ old($name) }}</textarea>

    @error($name)
        <p class="text-sm text-cyra-danger" role="alert">{{ $message }}</p>
    @enderror
</div>
