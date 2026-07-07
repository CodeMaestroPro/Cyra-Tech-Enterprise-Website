@props(['program'])

<article
    class="cyra-card flex h-full flex-col p-6 transition-colors hover:border-cyra-primary/40"
    data-community-card
    data-community-category="{{ $program['category'] }}"
>
    <div class="mb-4 flex items-start justify-between gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-cyra-primary/15 text-cyra-accent">
            <x-homepage.icon :name="$program['icon'] ?? 'spark'" />
        </div>
        @if (! empty($program['badge']))
            <x-ui.badge variant="purple">{{ $program['badge'] }}</x-ui.badge>
        @endif
    </div>

    <h3 class="text-lg font-semibold text-cyra-text">{{ $program['title'] }}</h3>
    <p class="mt-1 text-sm font-medium text-cyra-accent">{{ $program['tagline'] }}</p>
    <p class="mt-3 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $program['summary'] }}</p>

    @if (! empty($program['schedule']))
        <p class="mt-4 text-xs uppercase tracking-wide text-cyra-muted">{{ $program['schedule'] }}</p>
    @endif

    <a href="{{ route('community.show', $program['slug']) }}" class="mt-6 text-sm font-medium text-cyra-accent hover:text-cyra-primary">
        View program →
    </a>
</article>
