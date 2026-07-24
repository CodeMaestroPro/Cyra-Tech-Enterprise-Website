@props(['section'])

@php
    $content = $section['content'] ?? [];
    $items = $content['items'] ?? [];
    $action = $content['action'] ?? null;
@endphp

<section class="cyra-section cyra-section-dark" aria-labelledby="homepage-{{ $section['slug'] }}-title">
    <div class="cyra-container">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12">
            <div class="lg:col-span-4" data-animate="fade-up">
                @if (! empty($section['eyebrow']))
                    <p class="cyra-caption text-cyra-primary">{{ $section['eyebrow'] }}</p>
                @endif
                <h2 id="homepage-{{ $section['slug'] }}-title" class="mt-3 text-2xl font-bold tracking-tight sm:text-4xl">
                    {{ $section['title'] ?? '' }}
                </h2>
                @if (! empty($section['description']))
                    <p class="mt-4 text-sm leading-relaxed text-white/70 sm:text-base">
                        {{ $section['description'] }}
                    </p>
                @endif
                @if ($action)
                    <div class="mt-8 hidden lg:block">
                        <x-ui.button href="{{ route($action['route']) }}" variant="outline-white">
                            {{ $action['label'] }}
                        </x-ui.button>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-8">
                <div class="grid gap-4 sm:grid-cols-2" data-animate-stagger>
                    @foreach ($items as $item)
                        @php
                            $href = ! empty($item['route_params'])
                                ? route($item['route'], $item['route_params'])
                                : route($item['route']);
                        @endphp
                        <article class="cyra-product-card" data-animate="fade-up">
                            <div class="cyra-product-card-media">
                                @if (! empty($item['image']))
                                    <img
                                        src="{{ asset($item['image']) }}"
                                        alt="{{ $item['title'] }} product preview"
                                        class="cyra-product-card-image"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                @else
                                    <div class="aspect-[16/9] bg-gradient-to-br from-blue-600/30 via-blue-900/40 to-slate-900"></div>
                                @endif
                            </div>
                            <div class="cyra-product-card-body">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="cyra-product-card-title">{{ $item['title'] }}</h3>
                                    @if (! empty($item['badge']))
                                        <span class="cyra-product-card-badge">
                                            {{ $item['badge'] }}
                                        </span>
                                    @endif
                                </div>
                                <p class="cyra-product-card-copy">{{ $item['description'] }}</p>
                                <a href="{{ $href }}" class="cyra-product-card-link">
                                    Learn More
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($action)
                    <div class="mt-8 flex justify-center lg:hidden" data-animate="fade-up">
                        <x-ui.button href="{{ route($action['route']) }}" variant="outline-white">
                            {{ $action['label'] }}
                        </x-ui.button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
