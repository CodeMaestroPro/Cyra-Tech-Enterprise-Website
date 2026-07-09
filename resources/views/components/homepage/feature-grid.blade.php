@props(['section'])

@php
    $content = $section['content'] ?? [];
    $items = $content['items'] ?? [];
    $action = $content['action'] ?? null;
@endphp

<section class="cyra-section border-b border-cyra-border/60" aria-labelledby="homepage-{{ $section['slug'] }}-title">
    <div class="cyra-container">
        <x-ui.section-heading
            :eyebrow="$section['eyebrow'] ?? null"
            :title="$section['title'] ?? ''"
            :description="$section['description'] ?? null"
            id="homepage-{{ $section['slug'] }}-title"
            class="cyra-section-heading"
        />

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4" data-animate-stagger>
            @foreach ($items as $item)
                <article class="cyra-card-interactive flex h-full flex-col p-5 sm:p-6" data-animate="fade-up">
                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
                        <x-homepage.icon :name="$item['icon'] ?? 'spark'" />
                    </div>
                    <h3 class="text-lg font-semibold text-cyra-text">{{ $item['title'] }}</h3>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $item['description'] }}</p>
                    <a href="{{ route($item['route']) }}" class="mt-4 text-sm font-medium text-cyra-primary hover:text-cyra-primary-hover">
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
