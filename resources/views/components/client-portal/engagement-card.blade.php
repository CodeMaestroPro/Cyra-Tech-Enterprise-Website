@props(['engagement'])

<article class="cyra-card-interactive flex h-full flex-col p-6">
    <div class="mb-4 flex items-start justify-between gap-3">
        <x-ui.badge variant="primary">{{ $engagement['phase'] }}</x-ui.badge>
        <x-ui.badge variant="purple">{{ $engagement['status_label'] }}</x-ui.badge>
    </div>

    <h3 class="text-lg font-semibold text-cyra-text">{{ $engagement['title'] }}</h3>
    <p class="mt-1 text-sm font-medium text-cyra-accent">{{ $engagement['tagline'] }}</p>
    <p class="mt-3 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $engagement['summary'] }}</p>

    <div class="mt-4">
        <div class="mb-2 flex items-center justify-between text-xs text-cyra-muted">
            <span>Progress</span>
            <span>{{ $engagement['progress'] }}%</span>
        </div>
        <div class="h-2 overflow-hidden rounded-full bg-cyra-navy">
            <div class="h-full rounded-full bg-cyra-primary" style="width: {{ $engagement['progress'] }}%"></div>
        </div>
    </div>

    <a href="{{ route('client-portal.engagements.show', $engagement['slug']) }}" class="mt-6 text-sm font-medium text-cyra-primary hover:text-cyra-primary-hover">
        View engagement →
    </a>
</article>
