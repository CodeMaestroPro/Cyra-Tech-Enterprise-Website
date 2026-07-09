@props(['program'])

<article
    class="cyra-card-interactive flex h-full flex-col p-6"
    data-partner-card
    data-partner-category="{{ $program['category'] }}"
>
    <div class="mb-4 flex items-start justify-between gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
            <x-homepage.icon :name="$program['icon'] ?? 'spark'" />
        </div>
        @if (! empty($program['badge']))
            <x-ui.badge variant="purple">{{ $program['badge'] }}</x-ui.badge>
        @endif
    </div>

    <p class="text-xs font-semibold uppercase tracking-wide text-cyra-muted">{{ $program['partner_type'] }}</p>
    <h3 class="mt-2 text-lg font-semibold text-cyra-text">{{ $program['title'] }}</h3>
    <p class="mt-1 text-sm font-medium text-cyra-accent">{{ $program['tagline'] }}</p>
    <p class="mt-3 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $program['summary'] }}</p>

    <div class="mt-4 flex flex-wrap gap-2 text-xs text-cyra-muted">
        <span>{{ $program['region'] }}</span>
        <span>•</span>
        <span>{{ $program['engagement_model'] }}</span>
    </div>

    <a href="{{ route('partner-hub.show', $program['slug']) }}" class="mt-6 text-sm font-medium text-cyra-primary hover:text-cyra-primary-hover">
        View program →
    </a>
</article>
