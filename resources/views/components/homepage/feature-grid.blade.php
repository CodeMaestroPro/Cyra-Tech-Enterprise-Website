@props(['section'])

@php
    $content = $section['content'] ?? [];
    $items = $content['items'] ?? [];
    $action = $content['action'] ?? null;
@endphp

<section class="border-b border-cyra-border/60 py-16" aria-labelledby="homepage-{{ $section['slug'] }}-title">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-ui.section-heading
            :eyebrow="$section['eyebrow'] ?? null"
            :title="$section['title'] ?? ''"
            :description="$section['description'] ?? null"
            id="homepage-{{ $section['slug'] }}-title"
            class="mb-10"
        />

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($items as $item)
                <article class="cyra-card flex h-full flex-col p-6 transition-colors hover:border-cyra-primary/40">
                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg bg-cyra-primary/15 text-cyra-accent">
                        <x-homepage.icon :name="$item['icon'] ?? 'spark'" />
                    </div>
                    <h3 class="text-lg font-semibold text-cyra-text">{{ $item['title'] }}</h3>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $item['description'] }}</p>
                    <a href="{{ route($item['route']) }}" class="mt-4 text-sm font-medium text-cyra-accent hover:text-cyra-primary">
                        Learn more →
                    </a>
                </article>
            @endforeach
        </div>

        @if ($action)
            <div class="mt-8">
                <x-ui.button href="{{ route($action['route']) }}" variant="secondary">
                    {{ $action['label'] }}
                </x-ui.button>
            </div>
        @endif
    </div>
</section>
