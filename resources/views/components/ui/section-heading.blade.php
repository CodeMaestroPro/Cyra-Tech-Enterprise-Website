@props([
    'eyebrow' => null,
    'title',
    'description' => null,
])

<header {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @if ($eyebrow)
        <p class="cyra-caption">{{ $eyebrow }}</p>
    @endif
    <h2 class="cyra-heading-2">{{ $title }}</h2>
    @if ($description)
        <p class="cyra-body max-w-3xl">{{ $description }}</p>
    @endif
</header>
