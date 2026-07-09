@props(['section'])

@php
    $content = $section['content'] ?? [];
    $commitment = $content['commitment'] ?? [];
    $community = $content['community'] ?? [];
    $values = $commitment['values'] ?? [];
    $stats = $community['stats'] ?? [];
@endphp

<section class="cyra-section cyra-section-soft" aria-label="Our commitment and community impact">
    <div class="cyra-container">
        <div class="grid gap-10 lg:grid-cols-2 lg:gap-12">
            <div data-animate="fade-up">
                @if (! empty($commitment['eyebrow']))
                    <p class="cyra-caption">{{ $commitment['eyebrow'] }}</p>
                @endif
                <h2 class="cyra-heading-2 mt-3 sm:text-4xl">
                    {{ $commitment['title'] ?? '' }}
                </h2>

                <ul class="mt-8 space-y-5">
                    @foreach ($values as $value)
                        <li class="flex gap-4">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-cyra-primary/10 text-cyra-primary">
                                <x-homepage.icon :name="$value['icon'] ?? 'spark'" />
                            </span>
                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-[0.08em] text-cyra-text sm:text-base">{{ $value['title'] }}</h3>
                                <p class="mt-1.5 text-sm font-semibold leading-relaxed text-cyra-text/85">{{ $value['description'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="space-y-6">
                <figure class="cyra-commitment-image" data-animate="scale-in">
                    <img
                        src="{{ asset('images/homepage/our-commitment.png') }}"
                        alt="Our Commitment: Leadership, Technology, and Impact empowering the next generation of technology leaders."
                        class="cyra-commitment-image__photo"
                        loading="lazy"
                        decoding="async"
                        width="1024"
                        height="801"
                    >
                    <span class="cyra-commitment-image__glow" aria-hidden="true"></span>
                </figure>

                <div class="cyra-card p-6 sm:p-8" data-animate="fade-up">
                    @if (! empty($community['eyebrow']))
                        <p class="cyra-caption">{{ $community['eyebrow'] }}</p>
                    @endif

                    <dl class="mt-4 grid grid-cols-2 gap-4 sm:gap-6">
                        @foreach ($stats as $stat)
                            <div>
                                <dt class="text-xs font-bold uppercase tracking-wide text-cyra-muted">{{ $stat['label'] }}</dt>
                                <dd class="mt-1 text-2xl font-bold text-cyra-primary sm:text-3xl">{{ $stat['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    @if (! empty($community['action']))
                        <div class="mt-6">
                            <x-ui.button href="{{ route($community['action']['route']) }}" variant="primary">
                                {{ $community['action']['label'] }}
                            </x-ui.button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
