<?php

namespace App\Services;

use App\Models\ProductOffering;
use App\Repositories\ProductOfferingRepository;

class ProductService extends BaseService
{
    public function __construct(
        private readonly ProductOfferingRepository $productOfferingRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getProducts(): array
    {
        $products = $this->productOfferingRepository
            ->getActiveProducts()
            ->map(fn (ProductOffering $product) => $this->formatProduct($product))
            ->values()
            ->all();

        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.products.hero', []),
            'categories' => config('cyra.products.categories', []),
            'products' => $products,
            'featured' => collect($products)->where('is_featured', true)->values()->all(),
            'ecosystem' => config('cyra.products.ecosystem', []),
            'cta' => config('cyra.products.cta', []),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getProduct(string $slug): ?array
    {
        $product = $this->productOfferingRepository->findActiveBySlug($slug);

        if ($product === null) {
            return null;
        }

        return $this->formatProduct($product);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.products.seo', []);

        return [
            'title' => $seo['title'] ?? 'Products | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Explore Cyra-Tech platform products for operations, analytics, security, and enterprise automation.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatProduct(ProductOffering $product): array
    {
        $categoryLabel = collect(config('cyra.products.categories', []))
            ->firstWhere('slug', $product->category)['label'] ?? ucfirst($product->category);

        return [
            'slug' => $product->slug,
            'category' => $product->category,
            'category_label' => $categoryLabel,
            'title' => $product->title,
            'tagline' => $product->tagline,
            'summary' => $product->summary,
            'description' => $product->description,
            'badge' => $product->badge,
            'features' => $product->features ?? [],
            'use_cases' => $product->use_cases ?? [],
            'icon' => $product->icon,
            'is_featured' => $product->is_featured,
        ];
    }
}
