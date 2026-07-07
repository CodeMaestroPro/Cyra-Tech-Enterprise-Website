@props(['status' => 'pending'])

@php
    $styles = [
        'completed' => 'bg-cyra-success/15 text-cyra-success border-cyra-success/30',
        'in_progress' => 'bg-cyra-primary/15 text-cyra-accent border-cyra-primary/30',
        'pending' => 'bg-cyra-border/40 text-cyra-muted border-cyra-border',
        'blocked' => 'bg-cyra-danger/15 text-cyra-danger border-cyra-danger/30',
    ];

    $labels = [
        'completed' => 'Completed',
        'in_progress' => 'In Progress',
        'pending' => 'Pending',
        'blocked' => 'Blocked',
    ];
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium capitalize '
        . ($styles[$status] ?? $styles['pending']),
]) }}>
    {{ $labels[$status] ?? $status }}
</span>
