@props(['offering'])

<article
    class="cyra-card-interactive flex h-full flex-col p-6"
    data-solution-card
    data-solution-category="{{ $offering['category'] }}"
>
    <div class="mb-4 flex items-start justify-between gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
            <x-homepage.icon :name="$offering['icon'] ?? 'spark'" />
        </div>
        <x-ui.badge variant="primary">{{ $offering['category_label'] }}</x-ui.badge>
    </div>

    <h3 class="text-lg font-semibold text-cyra-text">{{ $offering['title'] }}</h3>
    <p class="mt-2 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $offering['summary'] }}</p>

    @if (! empty($offering['outcomes']))
        <ul class="mt-4 space-y-1 text-xs text-cyra-success">
            @foreach (array_slice($offering['outcomes'], 0, 2) as $outcome)
                <li>• {{ $outcome }}</li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('solutions.show', $offering['slug']) }}" class="mt-6 text-sm font-medium text-cyra-primary hover:text-cyra-primary-hover">
        Explore solution →
    </a>
</article>
