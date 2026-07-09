@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'target' => null,
    'rel' => null,
])

@php
    $variants = [
        'primary' => 'bg-cyra-primary text-white hover:bg-cyra-primary-hover shadow-sm shadow-cyra-primary/20',
        'secondary' => 'border border-cyra-border bg-cyra-surface text-cyra-text hover:border-cyra-primary/50 hover:bg-cyra-soft',
        'ghost' => 'text-cyra-muted hover:bg-cyra-soft hover:text-cyra-text',
        'success' => 'bg-cyra-success text-white hover:bg-cyra-success/90',
        'danger' => 'bg-cyra-danger text-white hover:bg-cyra-danger/90',
        'outline' => 'border border-cyra-primary/40 bg-cyra-primary/10 text-cyra-accent hover:border-cyra-primary hover:bg-cyra-primary/20 hover:text-cyra-text',
        'outline-white' => 'border border-white/30 bg-white/10 text-white hover:bg-white/20',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
    ];

    $classes = 'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-all duration-200 ease-out focus-visible:outline-none active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-60 '
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
