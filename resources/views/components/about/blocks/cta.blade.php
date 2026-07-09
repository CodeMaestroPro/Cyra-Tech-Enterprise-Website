@props(['block'])

@php
    $action = $block['action'] ?? null;
@endphp

<section>
    <div class="cyra-cta-premium">
        <div class="cyra-cta-premium-glow" aria-hidden="true"></div>
        <div class="relative">
            <h2 class="cyra-heading-2">{{ $block['title'] ?? '' }}</h2>
            @if (! empty($block['description']))
                <p class="mt-4 max-w-2xl text-base leading-relaxed text-cyra-muted">{{ $block['description'] }}</p>
            @endif
            @if ($action)
                <div class="mt-6">
                    <x-ui.button href="{{ route($action['route']) }}">
                        {{ $action['label'] }}
                    </x-ui.button>
                </div>
            @endif
        </div>
    </div>
</section>
