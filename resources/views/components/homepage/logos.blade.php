@props(['section'])

@php
    $items = $section['content']['items'] ?? [];
@endphp

<section class="border-b border-cyra-border/60 py-12" aria-labelledby="homepage-partners-title">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-ui.section-heading
            :eyebrow="$section['eyebrow'] ?? null"
            :title="$section['title'] ?? ''"
            id="homepage-partners-title"
            class="mb-8 text-center"
        />

        <ul class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($items as $item)
                <li class="flex items-center justify-center rounded-lg border border-cyra-border/60 bg-cyra-surface/40 px-4 py-5 text-center text-sm font-semibold text-cyra-muted">
                    {{ $item }}
                </li>
            @endforeach
        </ul>
    </div>
</section>
