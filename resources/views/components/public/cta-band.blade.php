@props([
    'title',
    'description' => null,
    'action' => null,
    'actions' => [],
])

<section class="cyra-section-footer">
    <div class="cyra-container">
        <div class="cyra-cta-premium" data-animate="fade-up">
            <div class="cyra-cta-premium-glow" aria-hidden="true"></div>
            <div class="relative">
                <h2 class="cyra-heading-2 max-w-2xl">{{ $title }}</h2>
                @if ($description)
                    <p class="mt-4 max-w-2xl text-base leading-relaxed text-cyra-muted">{{ $description }}</p>
                @endif

                @php
                    $ctaActions = count($actions) > 0 ? $actions : ($action ? [$action] : []);
                @endphp

                @if (count($ctaActions) > 0)
                    <div class="mt-6 flex flex-wrap gap-3">
                        @foreach ($ctaActions as $index => $ctaAction)
                            <x-ui.button
                                href="{{ route($ctaAction['route'], $ctaAction['params'] ?? []) }}"
                                :variant="$index === 0 ? 'primary' : 'outline'"
                            >
                                {{ $ctaAction['label'] }}
                            </x-ui.button>
                        @endforeach
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>
</section>
