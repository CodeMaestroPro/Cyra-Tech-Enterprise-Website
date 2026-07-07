@props(['section'])

@php
    $content = $section['content'] ?? [];
    $items = $content['items'] ?? [];
    $action = $content['action'] ?? null;
    $columns = $content['columns'] ?? (count($items) <= 3 ? count($items) : 3);
    $gridClass = match ((int) $columns) {
        2 => 'md:grid-cols-2',
        4 => 'md:grid-cols-2 xl:grid-cols-4',
        6 => 'md:grid-cols-2 xl:grid-cols-3',
        default => 'md:grid-cols-2 xl:grid-cols-3',
    };
@endphp

<section @class([
    'border-b border-cyra-border/60 py-16',
    'bg-cyra-navy/20' => ($section['slug'] ?? '') === 'industries',
]) aria-labelledby="homepage-{{ $section['slug'] }}-title">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-ui.section-heading
            :eyebrow="$section['eyebrow'] ?? null"
            :title="$section['title'] ?? ''"
            :description="$section['description'] ?? null"
            id="homepage-{{ $section['slug'] }}-title"
            class="mb-10"
        />

        <div @class(['grid gap-6', $gridClass])>
            @foreach ($items as $item)
                <article class="cyra-card flex h-full flex-col p-6">
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="text-lg font-semibold text-cyra-text">{{ $item['title'] }}</h3>
                        @if (! empty($item['badge']))
                            <x-ui.badge variant="primary">{{ $item['badge'] }}</x-ui.badge>
                        @endif
                    </div>
                    <p class="mt-3 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $item['description'] }}</p>
                    @if (! empty($item['metric']))
                        <p class="mt-4 text-sm font-semibold text-cyra-success">{{ $item['metric'] }}</p>
                    @endif
                    @if (! empty($item['meta']))
                        <p class="mt-4 text-xs uppercase tracking-wide text-cyra-muted">{{ $item['meta'] }}</p>
                    @endif
                    @if (! empty($item['route']))
                        <a href="{{ route($item['route']) }}" class="mt-4 text-sm font-medium text-cyra-accent hover:text-cyra-primary">
                            Read more →
                        </a>
                    @endif
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
