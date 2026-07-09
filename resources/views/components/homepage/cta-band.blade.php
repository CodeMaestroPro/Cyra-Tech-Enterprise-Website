@props(['section'])

@php
    $content = $section['content'] ?? [];
    $action = $content['action'] ?? null;
    $actions = $content['actions'] ?? [];
    $variant = $content['variant'] ?? 'surface';
    $wrapperClass = match ($variant) {
        'gradient', 'primary' => 'cyra-cta-premium px-5 py-8 sm:px-10 sm:py-10',
        default => 'cyra-card px-5 py-8 sm:px-10 sm:py-10',
    };
@endphp

<section class="cyra-section" aria-labelledby="homepage-{{ $section['slug'] }}-title">
    <div class="cyra-container">
        <div @class([$wrapperClass]) data-animate="fade-up">
            @if (in_array($variant, ['gradient', 'primary'], true))
                <div class="cyra-cta-premium-glow" aria-hidden="true"></div>
            @endif
            <div @class(['max-w-3xl', 'relative' => in_array($variant, ['gradient', 'primary'], true)])>
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
                                :variant="$item['variant'] === 'primary' ? 'primary' : 'outline'"
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
