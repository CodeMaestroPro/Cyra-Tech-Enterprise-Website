@props(['block'])

<section aria-label="Company timeline">
    <ol class="relative space-y-8 border-l border-cyra-border/80 pl-6">
        @foreach ($block['items'] ?? [] as $item)
            <li class="relative" data-timeline-item>
                <span class="absolute -left-[1.78rem] top-1 flex h-3 w-3 rounded-full bg-cyra-accent ring-4 ring-cyra-midnight"></span>
                <p class="text-sm font-semibold uppercase tracking-wide text-cyra-accent">{{ $item['year'] }}</p>
                <h3 class="mt-1 text-lg font-semibold text-cyra-text">{{ $item['title'] }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-cyra-muted">{{ $item['description'] }}</p>
            </li>
        @endforeach
    </ol>
</section>
