@props(['block'])

<section>
    <div class="grid gap-6 md:grid-cols-2">
        @foreach ($block['items'] ?? [] as $item)
            <article class="cyra-card p-6">
                <p class="cyra-caption">{{ $item['label'] }}</p>
                <blockquote class="mt-3 text-xl font-semibold leading-snug text-cyra-text">
                    “{{ $item['quote'] }}”
                </blockquote>
                <p class="mt-4 text-sm leading-relaxed text-cyra-muted">{{ $item['description'] }}</p>
            </article>
        @endforeach
    </div>
</section>
