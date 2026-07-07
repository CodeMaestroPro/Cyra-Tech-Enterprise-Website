@props(['block'])

<section class="py-10" aria-label="Key metrics">
    <dl class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($block['items'] ?? [] as $item)
            <div class="rounded-xl border border-cyra-border/70 bg-cyra-surface/50 px-5 py-6 text-center">
                <dt class="text-sm text-cyra-muted">{{ $item['label'] }}</dt>
                <dd class="mt-2 text-2xl font-bold text-cyra-text">{{ $item['value'] }}</dd>
            </div>
        @endforeach
    </dl>
</section>
