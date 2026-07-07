@props(['variant' => 'info'])

@php
    $styles = [
        'success' => 'border-cyra-success/30 bg-cyra-success/10 text-cyra-success',
        'error' => 'border-cyra-danger/30 bg-cyra-danger/10 text-cyra-danger',
        'info' => 'border-cyra-primary/30 bg-cyra-primary/10 text-cyra-text',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-lg border px-4 py-3 text-sm ' . ($styles[$variant] ?? $styles['info'])]) }} role="alert">
    {{ $slot }}
</div>
