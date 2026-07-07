@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'target' => null,
    'rel' => null,
])

@php
    $variants = [
        'primary' => 'bg-cyra-primary text-white hover:bg-cyra-primary-hover',
        'secondary' => 'border border-cyra-border bg-cyra-navy text-cyra-text hover:border-cyra-primary/50',
        'ghost' => 'text-cyra-muted hover:bg-cyra-surface hover:text-cyra-text',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
    ];

    $classes = 'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-colors focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-60 '
        . ($variants[$variant] ?? $variants['primary']) . ' '
        . ($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" @if($target) target="{{ $target }}" @endif @if($rel) rel="{{ $rel }}" @endif {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }}>
        {{ $slot }}
    </button>
@endif
