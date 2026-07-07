@props(['section'])

@php
    $content = $section['content'] ?? [];
    $action = $content['action'] ?? null;
    $actions = $content['actions'] ?? [];
    $variant = $content['variant'] ?? 'surface';
    $wrapperClass = match ($variant) {
        'gradient' => 'border-cyra-primary/30 bg-gradient-to-r from-cyra-primary/20 via-cyra-navy to-cyra-accent/10',
        'primary' => 'border-cyra-primary/30 bg-cyra-primary/10',
        default => 'border-cyra-border/70 bg-cyra-surface/40',
    };
@endphp

<section class="py-12" aria-labelledby="homepage-{{ $section['slug'] }}-title">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div @class(['rounded-2xl border px-6 py-10 sm:px-10', $wrapperClass])>
            <div class="max-w-3xl">
                @if (! empty($section['eyebrow']))
                    <p class="cyra-caption">{{ $section['eyebrow'] }}</p>
                @endif
                <h2 id="homepage-{{ $section['slug'] }}-title" class="mt-3 cyra-heading-2">
                    {{ $section['title'] }}
                </h2>
                @if (! empty($section['description']))
                    <p class="mt-4 text-base leading-relaxed text-cyra-muted">{{ $section['description'] }}</p>
                @endif

                @if ($action)
                    <div class="mt-6">
                        <x-ui.button href="{{ route($action['route']) }}">
                            {{ $action['label'] }}
                        </x-ui.button>
                    </div>
                @endif

                @if (count($actions) > 0)
                    <div class="mt-6 flex flex-wrap gap-3">
                        @foreach ($actions as $item)
                            <x-ui.button
                                href="{{ route($item['route']) }}"
                                :variant="$item['variant'] === 'primary' ? 'primary' : 'secondary'"
                            >
                                {{ $item['label'] }}
                            </x-ui.button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
