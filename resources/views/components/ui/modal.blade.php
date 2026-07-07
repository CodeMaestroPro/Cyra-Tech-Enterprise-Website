@props([
    'id',
    'title',
    'description' => null,
])

<div
    id="{{ $id }}"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
    data-cyra-modal
>
    <div class="cyra-card w-full max-w-lg p-6">
        <div class="mb-4 flex items-start justify-between gap-4">
            <div>
                <h2 id="{{ $id }}-title" class="cyra-heading-3">{{ $title }}</h2>
                @if ($description)
                    <p class="cyra-body mt-1">{{ $description }}</p>
                @endif
            </div>
            <button
                type="button"
                class="rounded-lg p-2 text-cyra-muted hover:bg-cyra-navy hover:text-cyra-text"
                data-cyra-modal-close
                aria-label="Close dialog"
            >
                ✕
            </button>
        </div>

        <div>{{ $slot }}</div>

        @isset($footer)
            <div class="mt-6 flex justify-end gap-3 border-t border-cyra-border pt-4">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
