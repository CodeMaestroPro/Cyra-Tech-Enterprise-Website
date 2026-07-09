@props(['section'])

@php
    $items = $section['content']['items'] ?? [];
    $variant = $section['content']['variant'] ?? 'grid';
@endphp

@if ($variant === 'bar')
    <section class="cyra-stats-bar" aria-label="Company metrics">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <dl class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6" data-animate-stagger>
                @foreach ($items as $item)
                    <div class="flex flex-col items-center text-center sm:items-start sm:text-left xl:items-center xl:text-center" data-animate="fade-up">
                        <dt class="flex items-center gap-2 text-xs font-medium uppercase tracking-[0.12em] text-white/70">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyra-primary/20 text-cyra-primary">
                                <x-homepage.icon :name="$item['icon'] ?? 'spark'" class="h-4 w-4 text-white" />
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </dt>
                        <dd class="mt-3 text-3xl font-bold text-white sm:text-4xl">
                            <span
                                data-stat-value="{{ $item['value'] }}"
                                data-stat-suffix="{{ $item['suffix'] ?? '' }}"
                            >0{{ $item['suffix'] ?? '' }}</span>
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>
@else
    <section class="border-b border-cyra-border bg-cyra-soft cyra-section-compact" aria-label="Company metrics">
        <div class="cyra-container">
            <dl class="grid gap-4 sm:grid-cols-2 sm:gap-6 xl:grid-cols-4" data-animate-stagger>
                @foreach ($items as $item)
                    <div class="cyra-card px-4 py-5 text-center sm:px-5 sm:py-6" data-animate="scale-in">
                        <dt class="text-sm text-cyra-muted">{{ $item['label'] }}</dt>
                        <dd class="mt-2 text-3xl font-bold text-cyra-text">
                            <span
                                data-stat-value="{{ $item['value'] }}"
                                data-stat-suffix="{{ $item['suffix'] ?? '' }}"
                            >0{{ $item['suffix'] ?? '' }}</span>
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>
@endif
