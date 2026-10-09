@props(['icon' => 'check'])

@php
    $icons = [
        'code' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />',
        'mentor' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-7-3.5l7 3.5 7-3.5" />',
        'check' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'cyra-it-highlight-icon']) }} aria-hidden="true">
    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        {!! $icons[$icon] ?? $icons['check'] !!}
    </svg>
</span>
