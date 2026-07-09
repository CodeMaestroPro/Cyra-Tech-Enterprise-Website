@props(['section'])

@php
    $content = $section['content'] ?? [];
    $items = $content['items'] ?? [];
    $action = $content['action'] ?? null;
    $productThemes = [
        'Cyra HRMS' => 'from-blue-600/30 via-indigo-900/40 to-slate-900',
        'Cyra LMS' => 'from-cyan-600/30 via-blue-900/40 to-slate-900',
        'Cyra CRM' => 'from-violet-600/30 via-blue-900/40 to-slate-900',
        'Cyra Projects' => 'from-emerald-600/30 via-blue-900/40 to-slate-900',
    ];
@endphp

<section class="cyra-section cyra-section-dark" aria-labelledby="homepage-{{ $section['slug'] }}-title">
    <div class="cyra-container">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12">
            <div class="lg:col-span-4" data-animate="fade-up">
                @if (! empty($section['eyebrow']))
                    <p class="cyra-caption text-cyra-primary">{{ $section['eyebrow'] }}</p>
                @endif
                <h2 id="homepage-{{ $section['slug'] }}-title" class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                    {{ $section['title'] ?? '' }}
                </h2>
                @if (! empty($section['description']))
                    <p class="mt-4 text-base leading-relaxed text-white/70">
                        {{ $section['description'] }}
                    </p>
                @endif
                @if ($action)
                    <div class="mt-8">
                        <x-ui.button href="{{ route($action['route']) }}" variant="outline-white">
                            {{ $action['label'] }}
                        </x-ui.button>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-8">
                <div class="grid gap-4 sm:grid-cols-2" data-animate-stagger>
                    @foreach ($items as $item)
                        @php($theme = $productThemes[$item['title']] ?? 'from-blue-600/30 via-blue-900/40 to-slate-900')
                        <article class="cyra-product-card" data-animate="fade-up">
                            <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br {{ $theme }}">
                                <div class="absolute inset-4 rounded-lg border border-white/10 bg-[#0d1528]/80 p-3 shadow-inner">
                                    <div class="mb-3 flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-red-400"></span>
                                        <span class="h-2 w-2 rounded-full bg-yellow-400"></span>
                                        <span class="h-2 w-2 rounded-full bg-green-400"></span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="h-2 w-2/3 rounded bg-white/20"></div>
                                        <div class="grid grid-cols-3 gap-2">
                                            <div class="h-10 rounded bg-cyra-primary/30"></div>
                                            <div class="h-10 rounded bg-white/10"></div>
                                            <div class="h-10 rounded bg-white/10"></div>
                                        </div>
                                        <div class="h-16 rounded bg-white/5"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="text-lg font-semibold text-white">{{ $item['title'] }}</h3>
                                    @if (! empty($item['badge']))
                                        <span class="rounded-full bg-cyra-primary/20 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-cyra-primary">
                                            {{ $item['badge'] }}
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-2 text-sm leading-relaxed text-white/70">{{ $item['description'] }}</p>
                                <a href="{{ route($item['route']) }}" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-cyra-primary hover:text-white">
                                    Learn More
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
