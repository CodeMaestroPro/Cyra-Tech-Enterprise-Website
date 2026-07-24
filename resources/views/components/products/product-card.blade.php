@props(['product'])

<article
    class="cyra-card-interactive flex h-full flex-col overflow-hidden p-0"
    data-product-card
    data-product-category="{{ $product['category'] }}"
>
    @if (! empty($product['image']))
        <div class="border-b border-cyra-border/70 bg-[#0b1730] px-2 pt-2 sm:px-3 sm:pt-3">
            <img
                src="{{ asset($product['image']) }}"
                alt="{{ $product['title'] }} product preview"
                class="block max-h-44 w-full rounded-t-md object-contain sm:max-h-56"
                loading="lazy"
                decoding="async"
            >
        </div>
    @else
        <div class="flex items-start justify-between gap-3 px-6 pt-6">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyra-primary/10 text-cyra-primary shadow-sm shadow-cyra-primary/10">
                <x-homepage.icon :name="$product['icon'] ?? 'spark'" />
            </div>
            @if (! empty($product['badge']))
                <x-ui.badge variant="purple">{{ $product['badge'] }}</x-ui.badge>
            @endif
        </div>
    @endif

    <div class="flex flex-1 flex-col p-4 sm:p-6">
        <div class="mb-2 flex items-start justify-between gap-3">
            <h3 class="text-base font-semibold text-cyra-text sm:text-lg">{{ $product['title'] }}</h3>
            @if (! empty($product['image']) && ! empty($product['badge']))
                <x-ui.badge variant="purple">{{ $product['badge'] }}</x-ui.badge>
            @endif
        </div>
        <p class="text-sm font-medium text-cyra-accent">{{ $product['tagline'] }}</p>
        <p class="mt-3 flex-1 text-sm leading-relaxed text-cyra-muted">{{ $product['summary'] }}</p>

        <a href="{{ route('products.show', $product['slug']) }}" class="mt-6 text-sm font-medium text-cyra-primary hover:text-cyra-primary-hover">
            View product →
        </a>
    </div>
</article>
