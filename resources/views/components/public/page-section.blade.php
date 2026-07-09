@props([
    'variant' => 'default',
    'title' => null,
    'titleId' => null,
    'eyebrow' => null,
    'description' => null,
    'headingClass' => 'cyra-section-heading',
])

@php
    $sectionClass = match ($variant) {
        'soft' => 'cyra-section cyra-section-soft border-t border-cyra-border',
        'band' => 'cyra-section cyra-section-band',
        'compact' => 'cyra-section-compact',
        'tight' => 'cyra-section-tight',
        default => 'cyra-section',
    };
@endphp

<section
    @if ($titleId) aria-labelledby="{{ $titleId }}" @endif
    {{ $attributes->merge(['class' => $sectionClass]) }}
>
    <div class="cyra-container">
        @if ($title)
            <x-ui.section-heading
                :eyebrow="$eyebrow"
                :title="$title"
                :description="$description"
                :id="$titleId"
                :class="$headingClass"
            />
        @endif

        {{ $slot }}
    </div>
</section>
