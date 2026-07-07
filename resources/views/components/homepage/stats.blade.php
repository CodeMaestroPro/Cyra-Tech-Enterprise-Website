@props(['section'])

@php
    $items = $section['content']['items'] ?? [];
@endphp

<section class="border-b border-cyra-border/60 bg-cyra-navy/40" aria-label="Company metrics">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <dl class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($items as $item)
                <div class="rounded-xl border border-cyra-border/70 bg-cyra-surface/50 px-5 py-6 text-center">
                    <dt class="text-sm text-cyra-muted">{{ $item['label'] }}</dt>
                    <dd class="mt-2 text-3xl font-bold text-cyra-text">
                        <span
                            data-stat-value="{{ $item['value'] }}"
                            data-stat-suffix="{{ $item['suffix'] ?? '' }}"
                        >0{{ $item['suffix'] ?? '' }}</span>
                    </dd>
                </div>
            @endforeach
        </dl>
    </div>
</section>
