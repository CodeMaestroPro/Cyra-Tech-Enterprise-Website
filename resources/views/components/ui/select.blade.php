@props([
    'label' => null,
    'name',
    'options' => [],
    'placeholder' => null,
    'required' => false,
])

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @if ($label)
        <x-ui.label :for="$name">{{ $label }}</x-ui.label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if($required) required @endif
        @class([
            'cyra-input',
            'cyra-input-error' => $errors->has($name),
        ])
        aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected(old($name) == $value)>{{ $text }}</option>
        @endforeach
        {{ $slot }}
    </select>

    @error($name)
        <p class="text-sm text-cyra-danger" role="alert">{{ $message }}</p>
    @enderror
</div>
