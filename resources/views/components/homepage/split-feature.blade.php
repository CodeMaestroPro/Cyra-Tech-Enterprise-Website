@props(['section'])

@php
    $content = $section['content'] ?? [];
    $bullets = $content['bullets'] ?? [];
    $action = $content['action'] ?? null;
@endphp

<section class="cyra-section border-b border-cyra-border/60" aria-labelledby="homepage-{{ $section['slug'] }}-title">
    <div class="cyra-container">
        <div class="grid items-center gap-10 lg:grid-cols-2">
            <div data-animate="fade-up">
                <x-ui.section-heading
                    :eyebrow="$section['eyebrow'] ?? null"
                    :title="$section['title'] ?? ''"
                    :description="$section['description'] ?? null"
                    id="homepage-{{ $section['slug'] }}-title"
                />

                @if ($action)
                    <div class="mt-8">
                        <x-ui.button href="{{ route($action['route']) }}">
                            {{ $action['label'] }}
                        </x-ui.button>
                    </div>
                @endif
            </div>

            <div class="cyra-card-interactive p-5 sm:p-6 lg:p-8" data-animate="slide-left">
                <ul class="space-y-4">
                    @foreach ($bullets as $bullet)
                        <li class="flex items-start gap-3">
                            <span class="mt-1 flex h-6 w-6 items-center justify-center rounded-full bg-cyra-accent/15 text-cyra-accent">
                                <x-homepage.icon />
                            </span>
                            <span class="text-sm leading-relaxed text-cyra-text">{{ $bullet }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
