@props([
    'navigation' => [],
])

@php
    $brand = $navigation['brand'] ?? config('cyra.navigation.brand', []);
    $groups = $navigation['groups'] ?? [];
@endphp

<aside class="hidden w-72 shrink-0 border-r border-cyra-border bg-cyra-navy/80 lg:block">
    <div class="sticky top-0 flex h-screen flex-col overflow-y-auto p-4 sm:p-6">
        <x-brand.logo size="sm" variant="compact" />
        <p class="mt-3 flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.2em] text-cyra-accent">
            <x-ui.icon name="command" class="h-4 w-4" />
            Command Center
        </p>

        <nav class="mt-8 space-y-6" aria-label="Admin navigation">
            <x-navigation.admin-nav-groups :groups="$groups" />
        </nav>

        <div class="mt-auto border-t border-cyra-border/70 pt-6">
            <a
                href="{{ route('home') }}"
                target="_blank"
                rel="noopener"
                class="flex items-center justify-center gap-2 rounded-lg border border-cyra-border bg-cyra-surface/60 px-4 py-2.5 text-sm font-medium text-cyra-text transition-all duration-200 hover:border-cyra-primary/40 hover:bg-cyra-surface"
            >
                View Website
                <x-ui.icon name="external-link" class="h-4 w-4" />
            </a>
        </div>
    </div>
</aside>
