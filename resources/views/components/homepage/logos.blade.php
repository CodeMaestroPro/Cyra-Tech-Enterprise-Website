@props(['section'])

@php
    $items = $section['content']['items'] ?? [];
@endphp

<section class="cyra-section-tight cyra-section-band" aria-labelledby="homepage-partners-title">
    <div class="cyra-container">
        <div class="flex flex-col items-center gap-6 lg:flex-row lg:justify-between">
            @if (! empty($section['title']))
                <p id="homepage-partners-title" class="max-w-xs text-center text-xs font-semibold uppercase tracking-[0.16em] text-cyra-muted lg:max-w-sm lg:text-left">
                    {{ $section['title'] }}
                </p>
            @endif

            <ul class="grid w-full grid-cols-2 gap-4 sm:grid-cols-3 lg:flex lg:flex-1 lg:items-center lg:justify-end lg:gap-8 xl:gap-10">
                @foreach ($items as $item)
                    <li class="flex items-center justify-center lg:justify-end">
                        <span class="cyra-partner-logo">{{ $item }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
