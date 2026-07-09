@props(['article'])

<article
    class="cyra-card-interactive flex h-full flex-col p-6"
    data-insight-card
    data-insight-category="{{ $article['category'] }}"
>
    <div class="mb-4 flex items-start justify-between gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
            <x-homepage.icon :name="$article['icon'] ?? 'spark'" />
        </div>
        @if (! empty($article['badge']))
            <x-ui.badge variant="purple">{{ $article['badge'] }}</x-ui.badge>
        @endif
    </div>

    <h3 class="text-lg font-semibold text-cyra-text">{{ $article['title'] }}</h3>
    <p class="mt-1 text-sm font-medium text-cyra-accent">{{ $article['tagline'] }}</p>
    <p class="mt-3 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $article['summary'] }}</p>

    <p class="mt-4 text-xs uppercase tracking-wide text-cyra-muted">{{ $article['read_time'] }}</p>

    <a href="{{ route('insights.show', $article['slug']) }}" class="mt-6 text-sm font-medium text-cyra-primary hover:text-cyra-primary-hover">
        Read article →
    </a>
</article>
