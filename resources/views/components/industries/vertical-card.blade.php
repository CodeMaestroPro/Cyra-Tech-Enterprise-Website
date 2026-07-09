@props(['vertical'])

<article
    class="cyra-card-interactive flex h-full flex-col p-6"
    data-industry-card
    data-industry-category="{{ $vertical['category'] }}"
>
    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
        <x-homepage.icon :name="$vertical['icon'] ?? 'spark'" />
    </div>

    <h3 class="text-lg font-semibold text-cyra-text">{{ $vertical['title'] }}</h3>
    <p class="mt-1 text-sm font-medium text-cyra-accent">{{ $vertical['tagline'] }}</p>
    <p class="mt-3 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $vertical['summary'] }}</p>

    <a href="{{ route('industries.show', $vertical['slug']) }}" class="mt-6 text-sm font-medium text-cyra-primary hover:text-cyra-primary-hover">
        Explore industry →
    </a>
</article>
