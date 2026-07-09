@props(['block'])

<section>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($block['items'] ?? [] as $item)
            <article class="cyra-card p-6">
                <h3 class="text-lg font-semibold text-cyra-text">{{ $item['title'] }}</h3>
                <p class="mt-3 text-sm leading-relaxed text-cyra-muted">{{ $item['description'] }}</p>
            </article>
        @endforeach
    </div>
</section>
