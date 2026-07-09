@props([
    'size' => 'md',
    'variant' => 'compact',
    'href' => null,
])

@php
    $sizes = [
        'sm' => ['compact' => 'h-9', 'full' => 'h-16'],
        'md' => ['compact' => 'h-11', 'full' => 'h-24'],
        'lg' => ['compact' => 'h-12', 'full' => 'h-32'],
        'xl' => ['compact' => 'h-14', 'full' => 'h-36'],
    ];

    $sizeKey = array_key_exists($size, $sizes) ? $size : 'md';
    $variantKey = in_array($variant, ['compact', 'full'], true) ? $variant : 'compact';
    $heightClass = $sizes[$sizeKey][$variantKey];
    $maxWidthClass = $variantKey === 'full' ? 'max-w-[16rem] sm:max-w-xs' : 'max-w-[11rem] sm:max-w-[12rem]';
    $logoUrl = asset(config('cyra.brand.logo', 'images/brand/cyra-tech-logo.png'));
    $logoAlt = config('cyra.brand.logo_alt', config('cyra.name'));
    $homeUrl = $href ?? route('home');
@endphp

<a
    {{ $attributes->merge([
        'href' => $homeUrl,
        'class' => 'cyra-brand-logo group inline-flex shrink-0 items-center',
        'aria-label' => config('cyra.name') . ' home',
    ]) }}
>
    <img
        src="{{ $logoUrl }}"
        alt="{{ $logoAlt }}"
        @class([
            $heightClass,
            $maxWidthClass,
            'w-auto object-contain transition-opacity duration-200 group-hover:opacity-90',
        ])
        width="1024"
        height="1024"
        decoding="async"
        loading="eager"
    >
</a>
