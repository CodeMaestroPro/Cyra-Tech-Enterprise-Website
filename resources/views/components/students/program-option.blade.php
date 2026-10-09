@props([
    'slug',
    'label',
    'description' => '',
    'selected' => false,
])

<label class="cyra-tp-program {{ $selected ? 'is-selected' : '' }}" data-program-option="{{ $slug }}">
    <input
        type="radio"
        name="program"
        value="{{ $slug }}"
        class="sr-only"
        @checked($selected)
        required
    >
    <span class="cyra-tp-program-radio" aria-hidden="true"></span>
    <span class="min-w-0 flex-1">
        <span class="cyra-tp-program-title">{{ $label }}</span>
        @if ($description)
            <span class="cyra-tp-program-description">{{ $description }}</span>
        @endif
    </span>
</label>
