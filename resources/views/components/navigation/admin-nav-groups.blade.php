@props([
    'groups' => [],
    'mobileLinks' => false,
])

@foreach ($groups as $group)
    <div>
        <p class="mb-2 flex items-center gap-2 px-3 text-xs font-semibold uppercase tracking-wide text-cyra-muted">
            <span class="flex h-6 w-6 items-center justify-center rounded-md bg-cyra-surface/60 text-cyra-accent">
                <x-ui.icon :name="$group['icon'] ?? 'folder'" class="h-3.5 w-3.5" />
            </span>
            {{ $group['label'] }}
        </p>
        <ul class="space-y-1">
            @foreach ($group['items'] as $item)
                <li>
                    @if ($item['available'])
                        <a
                            href="{{ $item['url'] }}"
                            @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all duration-200',
                                'bg-cyra-surface text-cyra-text shadow-sm shadow-cyra-primary/10' => $item['active'],
                                'text-cyra-muted hover:bg-cyra-surface/60 hover:text-cyra-text hover:translate-x-0.5' => ! $item['active'],
                            ])
                            @if ($mobileLinks) data-admin-nav-link @endif
                        >
                            <span @class([
                                'flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border transition-colors',
                                'border-cyra-primary/40 bg-cyra-primary/15 text-cyra-accent' => $item['active'],
                                'border-cyra-border/60 bg-cyra-navy/60 text-cyra-muted group-hover:text-cyra-accent' => ! $item['active'],
                            ])>
                                <x-ui.icon :name="$item['icon'] ?? 'default'" class="h-4 w-4" />
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @else
                        <span class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-cyra-muted/70" title="Coming in a future module">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-cyra-border/40 bg-cyra-navy/40 text-cyra-muted/50">
                                <x-ui.icon :name="$item['icon'] ?? 'default'" class="h-4 w-4" />
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endforeach
