@props(['initiative'])

<article
    class="cyra-card-interactive flex h-full flex-col p-6"
    data-innovation-card
    data-innovation-category="{{ $initiative['category'] }}"
>
    <div class="mb-4 flex items-start justify-between gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
            <x-homepage.icon :name="$initiative['icon'] ?? 'spark'" />
        </div>
        @if (! empty($initiative['badge']))
            <x-ui.badge variant="purple">{{ $initiative['badge'] }}</x-ui.badge>
        @endif
    </div>

    <h3 class="text-lg font-semibold text-cyra-text">{{ $initiative['title'] }}</h3>
    <p class="mt-1 text-sm font-medium text-cyra-accent">{{ $initiative['tagline'] }}</p>
    <p class="mt-3 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $initiative['summary'] }}</p>

    @if (! empty($initiative['timeline']))
        <p class="mt-4 text-xs uppercase tracking-wide text-cyra-muted">{{ $initiative['timeline'] }}</p>
    @endif

    <a href="{{ route('innovation-lab.show', $initiative['slug']) }}" class="mt-6 text-sm font-medium text-cyra-primary hover:text-cyra-primary-hover">
        Explore program →
    </a>
</article>
