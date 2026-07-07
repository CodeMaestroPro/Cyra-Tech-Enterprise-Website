@props(['variant' => 'default'])

@php
    $styles = [
        'default' => 'bg-cyra-border/50 text-cyra-text border-cyra-border',
        'primary' => 'bg-cyra-primary/15 text-cyra-accent border-cyra-primary/30',
        'success' => 'bg-cyra-success/15 text-cyra-success border-cyra-success/30',
        'warning' => 'bg-cyra-warning/15 text-cyra-warning border-cyra-warning/30',
        'danger' => 'bg-cyra-danger/15 text-cyra-danger border-cyra-danger/30',
        'purple' => 'bg-cyra-purple/15 text-cyra-purple border-cyra-purple/30',
    ];
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium '
        . ($styles[$variant] ?? $styles['default']),
]) }}>
    {{ $slot }}
</span>
