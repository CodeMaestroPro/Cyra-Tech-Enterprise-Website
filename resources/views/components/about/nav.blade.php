@props(['items' => []])

<nav aria-label="About section navigation" class="border-b border-cyra-border/80 bg-cyra-navy/50 backdrop-blur-sm">
    <div class="cyra-container">
        <ul class="-mb-px flex gap-1 overflow-x-auto py-3">
            @foreach ($items as $item)
                <li class="shrink-0">
                    <a
                        href="{{ $item['url'] }}"
                        @class([
                            'inline-flex rounded-full px-4 py-2 text-sm font-medium transition-all whitespace-nowrap',
                            'bg-cyra-primary text-white shadow-sm shadow-cyra-primary/20' => $item['active'],
                            'text-cyra-muted hover:bg-cyra-light/50 hover:text-cyra-text' => ! $item['active'],
                        ])
                        @if ($item['active']) aria-current="page" @endif
                    >
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
