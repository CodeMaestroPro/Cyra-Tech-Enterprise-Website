@props(['block'])

<section>
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($block['items'] ?? [] as $item)
            <article class="rounded-2xl border border-cyra-border/80 bg-cyra-surface/70 p-6 transition-all duration-300 hover:-translate-y-0.5 hover:border-cyra-primary/35 hover:shadow-lg hover:shadow-cyra-primary/5 sm:p-7">
                <h3 class="text-lg font-semibold tracking-tight text-cyra-text">{{ $item['title'] }}</h3>
                <p class="mt-3 text-sm leading-relaxed text-cyra-muted">{{ $item['description'] }}</p>
            </article>
        @endforeach
    </div>
</section>
