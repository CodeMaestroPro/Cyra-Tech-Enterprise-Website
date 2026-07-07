@props(['block'])

@php
    $action = $block['action'] ?? null;
@endphp

<section class="py-10">
    <div class="rounded-2xl border border-cyra-primary/30 bg-gradient-to-r from-cyra-primary/15 via-cyra-navy to-cyra-accent/10 px-6 py-10 sm:px-10">
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
</section>
