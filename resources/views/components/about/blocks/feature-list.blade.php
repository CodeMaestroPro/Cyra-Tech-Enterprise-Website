@props(['block'])

<section>
    @if (! empty($block['title']))
        <div class="mb-8 max-w-2xl">
            <h2 class="cyra-heading-2">{{ $block['title'] }}</h2>
        </div>
    @endif
    <div class="grid gap-5 sm:grid-cols-2">
        @foreach ($block['items'] ?? [] as $item)
            <article class="group rounded-2xl border border-cyra-border/80 bg-cyra-surface/70 p-6 transition-all duration-300 hover:-translate-y-0.5 hover:border-cyra-primary/35 hover:shadow-lg hover:shadow-cyra-primary/5 sm:p-7">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-cyra-primary/10 text-sm font-bold text-cyra-primary">
                    {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                </div>
                <h3 class="text-lg font-semibold tracking-tight text-cyra-text">{{ $item['title'] }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-cyra-muted sm:text-[0.95rem]">{{ $item['description'] }}</p>
            </article>
        @endforeach
    </div>
</section>
