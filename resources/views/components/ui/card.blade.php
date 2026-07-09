@props([
    'title' => null,
    'description' => null,
    'interactive' => false,
])

<section {{ $attributes->merge(['class' => ($interactive ? 'cyra-card-interactive' : 'cyra-card') . ' p-4 sm:p-6']) }}>
    @if ($title || $description)
        <header class="mb-4">
            @if ($title)
                <h2 class="text-lg font-semibold text-cyra-text">{{ $title }}</h2>
            @endif
            @if ($description)
                <p class="mt-1 text-sm text-cyra-muted">{{ $description }}</p>
            @endif
        </header>
    @endif

    {{ $slot }}

    @isset($footer)
        <footer class="mt-4 border-t border-cyra-border pt-4">
            {{ $footer }}
        </footer>
    @endisset
</section>
