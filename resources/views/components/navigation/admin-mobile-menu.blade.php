@props([
    'navigation' => [],
])

@php
    $groups = $navigation['groups'] ?? [];
@endphp

<div
    id="admin-mobile-navigation"
    class="fixed inset-0 z-50 hidden lg:hidden"
    data-admin-nav-panel
    aria-hidden="true"
>
    <div class="absolute inset-0 bg-cyra-midnight/80 backdrop-blur-sm transition-opacity duration-300" data-admin-nav-backdrop></div>

    <div class="absolute inset-y-0 left-0 flex w-full max-w-xs flex-col border-r border-cyra-border bg-cyra-navy shadow-2xl transition-transform duration-300 ease-out -translate-x-full" data-admin-nav-drawer>
        <div class="flex items-center justify-between border-b border-cyra-border px-4 py-4">
            <x-brand.logo size="sm" variant="compact" />
            <button
                type="button"
                class="rounded-lg border border-cyra-border p-2 text-cyra-muted transition-colors hover:bg-cyra-surface hover:text-cyra-text"
                aria-label="Close admin navigation menu"
                data-admin-nav-close
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 space-y-6 overflow-y-auto p-4" aria-label="Admin mobile navigation">
            <x-navigation.admin-nav-groups :groups="$groups" :mobile-links="true" />
        </nav>

        <div class="border-t border-cyra-border/70 p-4">
            <a
                href="{{ route('home') }}"
                target="_blank"
                rel="noopener"
                class="flex items-center justify-center gap-2 rounded-lg border border-cyra-border bg-cyra-surface/60 px-4 py-2.5 text-sm font-medium text-cyra-text transition hover:border-cyra-primary/40"
                data-admin-nav-link
            >
                <x-ui.icon name="external-link" class="h-4 w-4" />
                View Website
            </a>
        </div>
    </div>
</div>
