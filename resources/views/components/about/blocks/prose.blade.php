@props(['block'])

@php
    $paragraphs = $block['paragraphs'] ?? [];
@endphp

<section class="about-prose">
    @if (! empty($block['title']))
        <h2 class="cyra-heading-2">{{ $block['title'] }}</h2>
    @endif

    <div @class(['mt-5 space-y-5' => ! empty($block['title']), 'space-y-5' => empty($block['title'])])>
        @foreach ($paragraphs as $index => $paragraph)
            <p @class([
                'leading-relaxed text-cyra-muted',
                'text-lg text-cyra-text sm:text-xl sm:leading-relaxed' => $index === 0,
                'text-base sm:text-lg' => $index > 0,
            ])>
                {{ $paragraph }}
            </p>
        @endforeach
    </div>
</section>
