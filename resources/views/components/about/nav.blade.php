@props(['items' => []])

<nav aria-label="About section navigation" class="border-b border-cyra-border/70">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <ul class="-mb-px flex gap-1 overflow-x-auto py-2">
            @foreach ($items as $item)
                <li class="shrink-0">
                    <a
                        href="{{ $item['url'] }}"
                        @class([
                            'inline-flex rounded-lg px-3 py-2 text-sm font-medium transition-colors whitespace-nowrap',
                            'bg-cyra-surface text-cyra-text' => $item['active'],
                            'text-cyra-muted hover:bg-cyra-surface/60 hover:text-cyra-text' => ! $item['active'],
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
