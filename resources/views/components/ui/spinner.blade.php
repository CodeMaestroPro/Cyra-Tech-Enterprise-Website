@props(['size' => 'md', 'label' => 'Loading'])

@php
    $sizes = [
        'sm' => 'h-4 w-4 border-2',
        'md' => 'h-6 w-6 border-2',
        'lg' => 'h-8 w-8 border-[3px]',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2']) }} role="status" aria-live="polite">
    <span
        class="cyra-spinner inline-block rounded-full border-cyra-border border-t-cyra-accent {{ $sizes[$size] ?? $sizes['md'] }}"
        aria-hidden="true"
    ></span>
    <span class="sr-only">{{ $label }}</span>
</div>
