@props(['opening'])

<article
    class="cyra-card-interactive flex h-full flex-col p-6"
    data-career-card
    data-career-category="{{ $opening['category'] }}"
>
    <div class="mb-4 flex items-start justify-between gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
            <x-homepage.icon :name="$opening['icon'] ?? 'spark'" />
        </div>
        @if (! empty($opening['badge']))
            <x-ui.badge variant="purple">{{ $opening['badge'] }}</x-ui.badge>
        @endif
    </div>

    <p class="text-xs font-semibold uppercase tracking-wide text-cyra-muted">{{ $opening['department'] }}</p>
    <h3 class="mt-2 text-lg font-semibold text-cyra-text">{{ $opening['title'] }}</h3>
    <p class="mt-1 text-sm font-medium text-cyra-accent">{{ $opening['tagline'] }}</p>
    <p class="mt-3 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $opening['summary'] }}</p>

    <div class="mt-4 flex flex-wrap gap-2 text-xs text-cyra-muted">
        <span>{{ $opening['location'] }}</span>
        <span>•</span>
        <span>{{ $opening['work_type'] }}</span>
    </div>

    <a href="{{ route('careers.show', $opening['slug']) }}" class="mt-6 text-sm font-medium text-cyra-primary hover:text-cyra-primary-hover">
        View role →
    </a>
</article>
