@props(['section'])

@php
    $content = $section['content'] ?? [];
    $actions = $content['actions'] ?? [];
@endphp

<section class="relative overflow-hidden border-b border-cyra-border/60" aria-labelledby="homepage-hero-title">
    <div class="cyra-container relative cyra-section-hero-inner">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            <div data-animate="fade-up">
                @if (! empty($content['badge']))
                    <p class="cyra-hero-badge">{{ $content['badge'] }}</p>
                @endif

                <h1 id="homepage-hero-title" class="cyra-display mt-6 uppercase leading-[1.05] tracking-tight sm:text-5xl lg:text-6xl">
                    <span class="block">{{ $content['title_line_1'] ?? 'INNOVATING TODAY.' }}</span>
                    <span class="mt-1 block cyra-gradient-text">{{ $content['title_line_2'] ?? 'EMPOWERING TOMORROW.' }}</span>
                </h1>

                @if (! empty($section['description']))
                    <p class="mt-6 max-w-xl text-base leading-relaxed text-cyra-muted sm:text-lg">
                        {{ $section['description'] }}
                    </p>
                @endif

                @if (count($actions) > 0)
                    <div class="mt-8 flex flex-wrap gap-3">
                        @foreach ($actions as $action)
                            <x-ui.button
                                href="{{ route($action['route']) }}"
                                :variant="$action['variant'] === 'primary' ? 'primary' : 'outline'"
                            >
                                @if (($action['icon'] ?? null) === 'play')
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                @endif
                                {{ $action['label'] }}
                            </x-ui.button>
                        @endforeach
                    </div>
                @endif
            </div>

            <figure class="cyra-hero-image" data-animate="scale-in">
                <img
                    src="{{ asset('images/homepage/hero-visual.png') }}"
                    alt="Cyra-Tech Enterprise: AI and automation, cloud solutions, cybersecurity, and software development."
                    class="cyra-hero-image__photo"
                    loading="eager"
                    decoding="async"
                    fetchpriority="high"
                    width="1024"
                    height="720"
                >
                <span class="cyra-hero-image__glow" aria-hidden="true"></span>
            </figure>
        </div>
    </div>
</section>
