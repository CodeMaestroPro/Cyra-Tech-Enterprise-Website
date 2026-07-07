@props([
    'title' => 'Nothing here yet',
    'description' => 'Content will appear once data is available.',
])

<div {{ $attributes->merge(['class' => 'cyra-card flex flex-col items-center justify-center px-6 py-12 text-center']) }}>
    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-cyra-border bg-cyra-navy text-cyra-accent">
        {{ $icon ?? '∅' }}
    </div>
    <h3 class="cyra-heading-3">{{ $title }}</h3>
    <p class="cyra-body mt-2 max-w-md">{{ $description }}</p>
    @if (isset($action))
        <div class="mt-6">{{ $action }}</div>
    @endif
</div>
