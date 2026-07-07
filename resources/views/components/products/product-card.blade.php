@props(['product'])

<article
    class="cyra-card flex h-full flex-col p-6 transition-colors hover:border-cyra-primary/40"
    data-product-card
    data-product-category="{{ $product['category'] }}"
>
    <div class="mb-4 flex items-start justify-between gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-cyra-primary/15 text-cyra-accent">
            <x-homepage.icon :name="$product['icon'] ?? 'spark'" />
        </div>
        @if (! empty($product['badge']))
            <x-ui.badge variant="purple">{{ $product['badge'] }}</x-ui.badge>
        @endif
    </div>

    <h3 class="text-lg font-semibold text-cyra-text">{{ $product['title'] }}</h3>
    <p class="mt-1 text-sm font-medium text-cyra-accent">{{ $product['tagline'] }}</p>
    <p class="mt-3 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $product['summary'] }}</p>

    <a href="{{ route('products.show', $product['slug']) }}" class="mt-6 text-sm font-medium text-cyra-accent hover:text-cyra-primary">
        View product →
    </a>
</article>
