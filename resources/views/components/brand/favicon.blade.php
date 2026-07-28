@php
    $favicon = asset(config('cyra.brand.favicon', 'favicon.ico'));
    $icon = asset(config('cyra.brand.icon', 'images/brand/cyra-tech-icon.png'));
@endphp

<link rel="icon" href="{{ $favicon }}" sizes="any">
<link rel="icon" href="{{ $icon }}" type="image/png" sizes="512x512">
<link rel="apple-touch-icon" href="{{ $icon }}">
<meta name="msapplication-TileImage" content="{{ $icon }}">
<meta name="application-name" content="{{ config('cyra.name') }}">
<meta name="apple-mobile-web-app-title" content="{{ config('cyra.name') }}">
