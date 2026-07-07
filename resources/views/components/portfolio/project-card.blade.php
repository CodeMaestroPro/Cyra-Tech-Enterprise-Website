@props(['project'])

<article
    class="cyra-card flex h-full flex-col p-6 transition-colors hover:border-cyra-primary/40"
    data-portfolio-card
    data-portfolio-category="{{ $project['category'] }}"
>
    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg bg-cyra-primary/15 text-cyra-accent">
        <x-homepage.icon :name="$project['icon'] ?? 'spark'" />
    </div>

    <p class="text-xs font-semibold uppercase tracking-wide text-cyra-muted">{{ $project['client_name'] }}</p>
    <h3 class="mt-2 text-lg font-semibold text-cyra-text">{{ $project['title'] }}</h3>
    <p class="mt-1 text-sm font-medium text-cyra-accent">{{ $project['tagline'] }}</p>
    <p class="mt-3 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $project['summary'] }}</p>

    @if (! empty($project['metrics'][0]['value']))
        <p class="mt-4 text-sm font-semibold text-cyra-success">{{ $project['metrics'][0]['value'] }} {{ strtolower($project['metrics'][0]['label'] ?? '') }}</p>
    @endif

    <a href="{{ route('portfolio.show', $project['slug']) }}" class="mt-6 text-sm font-medium text-cyra-accent hover:text-cyra-primary">
        View case study →
    </a>
</article>
