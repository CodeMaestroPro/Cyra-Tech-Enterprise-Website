@props(['block'])

@php
    $items = $block['items'] ?? [];
    $columns = count($items) === 1 ? 'md:grid-cols-1' : 'md:grid-cols-2';
@endphp

<section>
    <div class="grid gap-6 {{ $columns }}">
        @foreach ($items as $item)
            <article class="relative overflow-hidden rounded-2xl border border-[#1e3a5f] bg-gradient-to-br from-[#000b26] via-[#001233] to-[#030711] p-7 sm:p-8">
                <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,color-mix(in_srgb,#0052ff_22%,transparent),transparent_42%)]" aria-hidden="true"></div>
                <div class="relative">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-cyra-accent">{{ $item['label'] }}</p>
                    <blockquote class="mt-4 text-xl font-semibold leading-snug text-white sm:text-2xl sm:leading-snug">
                        “{{ $item['quote'] }}”
                    </blockquote>
                    @if (! empty($item['description']))
                        <p class="mt-4 max-w-2xl text-sm leading-relaxed text-white/70 sm:text-base">{{ $item['description'] }}</p>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
</section>
