@props(['profile'])

<article class="cyra-card-interactive flex h-full flex-col overflow-hidden p-0">
    <div class="aspect-[4/5] overflow-hidden bg-cyra-navy">
        @if (! empty($profile['photo_url']))
            <img
                src="{{ $profile['photo_url'] }}"
                alt="{{ $profile['name'] }}"
                class="h-full w-full object-cover object-top"
                loading="lazy"
                decoding="async"
            >
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-cyra-primary to-cyra-accent text-4xl font-bold text-white">
                {{ $profile['initials'] }}
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-6">
        <div>
            <h3 class="text-lg font-semibold text-cyra-text">{{ $profile['name'] }}</h3>
            <p class="mt-1 text-sm text-cyra-accent">{{ $profile['title'] }}</p>
        </div>

        <p class="mt-4 flex-1 text-sm leading-relaxed text-cyra-muted">
            {{ \Illuminate\Support\Str::limit($profile['bio'], 160) }}
        </p>

        @if (! empty($profile['focus_areas']))
            <ul class="mt-4 flex flex-wrap gap-2">
                @foreach ($profile['focus_areas'] as $focus)
                    <li>
                        <x-ui.badge variant="primary">{{ $focus }}</x-ui.badge>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-6">
            <button
                type="button"
                class="text-sm font-medium text-cyra-primary hover:text-cyra-primary-hover"
                data-leadership-open
                data-profile="{{ e(json_encode($profile)) }}"
                aria-haspopup="dialog"
            >
                View full profile →
            </button>
        </div>
    </div>
</article>
