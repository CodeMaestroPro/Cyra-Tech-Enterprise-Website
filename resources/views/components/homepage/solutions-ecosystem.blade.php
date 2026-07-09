@props(['section'])

@php
    $content = $section['content'] ?? [];
    $items = $content['items'] ?? [];
    $action = $content['action'] ?? null;
@endphp

<section class="cyra-section" aria-labelledby="homepage-{{ $section['slug'] }}-title">
    <div class="cyra-container">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12">
            <div class="lg:col-span-4" data-animate="fade-up">
                @if (! empty($section['eyebrow']))
                    <p class="cyra-caption">{{ $section['eyebrow'] }}</p>
                @endif
                <h2 id="homepage-{{ $section['slug'] }}-title" class="cyra-heading-2 mt-3 sm:text-4xl">
                    {{ $section['title'] ?? '' }}
                </h2>
                @if (! empty($section['description']))
                    <p class="mt-4 text-base leading-relaxed text-cyra-muted">
                        {{ $section['description'] }}
                    </p>
                @endif
                @if ($action)
                    <div class="mt-8">
                        <x-ui.button href="{{ route($action['route']) }}" variant="primary">
                            {{ $action['label'] }}
                        </x-ui.button>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-8">
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-2" data-animate-stagger>
                    @foreach ($items as $item)
                        <a href="{{ route($item['route']) }}" class="cyra-solution-card group" data-animate="fade-up">
                            <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg bg-cyra-primary/10 text-cyra-primary">
                                <x-homepage.icon :name="$item['icon'] ?? 'spark'" />
                            </div>
                            <h3 class="text-lg font-semibold text-cyra-text">{{ $item['title'] }}</h3>
                            <p class="mt-2 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $item['description'] }}</p>
                            <span class="mt-5 inline-flex h-8 w-8 items-center justify-center self-end rounded-full bg-cyra-primary/10 text-cyra-primary transition-colors group-hover:bg-cyra-primary group-hover:text-white">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
