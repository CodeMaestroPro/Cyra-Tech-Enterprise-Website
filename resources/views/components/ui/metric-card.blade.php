@props([
    'label',
    'value',
    'accent' => 'text-cyra-accent',
    'valueId' => null,
])

<div {{ $attributes->merge(['class' => 'cyra-card-interactive p-4 sm:p-5']) }}>
    <div class="mb-3 flex items-center justify-between">
        <span class="text-sm text-cyra-muted">{{ $label }}</span>
        <span class="{{ $accent }}" aria-hidden="true">{{ $icon ?? '' }}</span>
    </div>
    <p
        @if($valueId) id="{{ $valueId }}" @endif
        class="text-2xl font-semibold text-cyra-text"
    >{{ $value }}</p>
</div>
