@props(['items' => []])

<nav aria-label="Breadcrumb" {{ $attributes }}>
    <ol class="flex flex-wrap items-center gap-2 text-sm text-cyra-muted">
        @foreach ($items as $index => $item)
            <li class="inline-flex items-center gap-2">
                @if ($index > 0)
                    <span aria-hidden="true">/</span>
                @endif
                @if (! empty($item['href']) && $index < count($items) - 1)
                    <a href="{{ $item['href'] }}" class="hover:text-cyra-text">{{ $item['label'] }}</a>
                @else
                    <span @class(['text-cyra-text' => $index === count($items) - 1]) aria-current="{{ $index === count($items) - 1 ? 'page' : false }}">
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
