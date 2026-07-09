@props([
    'breadcrumb' => [],
    'eyebrow' => null,
    'title',
    'description' => null,
    'icon' => null,
])

<section {{ $attributes->merge(['class' => 'cyra-page-hero']) }} aria-labelledby="page-hero-title">
    <div class="cyra-page-hero-glow" aria-hidden="true"></div>
    <div class="cyra-container relative cyra-section-hero-inner">
        @if (count($breadcrumb) > 0)
            <x-ui.breadcrumb :items="$breadcrumb" />
        @endif

        @if ($icon || isset($meta))
            <div class="mt-6 flex items-start gap-4 sm:gap-5">
                @if ($icon)
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
                        <x-homepage.icon :name="$icon" />
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    @isset($meta)
                        <div>{{ $meta }}</div>
                    @endisset
                    @if ($eyebrow)
                        <p class="cyra-hero-badge mt-0">{{ $eyebrow }}</p>
                    @endif
                    <h1 id="page-hero-title" class="mt-3 cyra-display">{{ $title }}</h1>
                    @if ($description)
                        <p class="mt-4 max-w-3xl text-lg leading-relaxed text-cyra-muted">{{ $description }}</p>
                    @endif
                    @if (isset($actions))
                        <div class="mt-6">{{ $actions }}</div>
                    @endif
                </div>
            </div>
        @else
            @if ($eyebrow)
                <p class="cyra-hero-badge mt-6">{{ $eyebrow }}</p>
            @endif
            <h1 id="page-hero-title" @class(['cyra-display', 'mt-6' => ! $eyebrow, 'mt-4' => $eyebrow])>{{ $title }}</h1>
            @if ($description)
                <p class="mt-4 max-w-3xl text-lg leading-relaxed text-cyra-muted">{{ $description }}</p>
            @endif
            @if (isset($actions))
                <div class="mt-6">{{ $actions }}</div>
            @endif
        @endif

        {{ $slot }}
    </div>
</section>
