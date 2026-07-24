@props(['block'])

@php
    $items = $block['items'] ?? [];
    $columns = count($items) === 1 ? 'md:grid-cols-1' : 'md:grid-cols-2';
@endphp

<section>
    <div class="grid gap-6 {{ $columns }}">
        @foreach ($items as $item)
            <article class="rounded-2xl border border-cyra-border/80 bg-white p-7 shadow-sm shadow-slate-200/60 sm:p-8">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-cyra-primary">{{ $item['label'] }}</p>
                <blockquote class="mt-4 text-xl font-semibold leading-snug text-cyra-text sm:text-2xl sm:leading-snug">
                    “{{ $item['quote'] }}”
                </blockquote>
                @if (! empty($item['description']))
                    <p class="mt-4 max-w-2xl text-sm leading-relaxed text-cyra-muted sm:text-base">{{ $item['description'] }}</p>
                @endif
            </article>
        @endforeach
    </div>
</section>
