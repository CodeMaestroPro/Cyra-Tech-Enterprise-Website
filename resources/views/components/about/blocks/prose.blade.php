@props(['block'])

<section>
    @if (! empty($block['title']))
        <h2 class="cyra-heading-3 mb-4">{{ $block['title'] }}</h2>
    @endif
    <div class="space-y-4 text-base leading-relaxed text-cyra-muted">
        @foreach ($block['paragraphs'] ?? [] as $paragraph)
            <p>{{ $paragraph }}</p>
        @endforeach
    </div>
</section>
