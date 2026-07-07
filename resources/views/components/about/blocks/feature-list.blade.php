@props(['block'])

<section class="py-10">
    @if (! empty($block['title']))
        <h2 class="cyra-heading-3 mb-6">{{ $block['title'] }}</h2>
    @endif
    <div class="grid gap-4 md:grid-cols-2">
        @foreach ($block['items'] ?? [] as $item)
            <article class="cyra-card p-6">
                <h3 class="text-lg font-semibold text-cyra-text">{{ $item['title'] }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-cyra-muted">{{ $item['description'] }}</p>
            </article>
        @endforeach
    </div>
</section>
