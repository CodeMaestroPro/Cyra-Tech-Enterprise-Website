@props([
    'class' => '',
])

<button
    type="button"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center rounded-lg border border-cyra-border p-2 text-cyra-muted transition-colors hover:bg-cyra-soft hover:text-cyra-text '.$class,
        'data-cyra-theme-toggle' => true,
        'aria-label' => 'Switch to light mode',
        'title' => 'Switch to light mode',
        'aria-pressed' => 'false',
    ]) }}
>
    <svg data-cyra-theme-icon="moon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
    <svg data-cyra-theme-icon="sun" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364l-1.414-1.414M7.05 7.05L5.636 5.636m12.728 0l-1.414 1.414M7.05 16.95l-1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z" />
    </svg>
</button>
