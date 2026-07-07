<?php

namespace Database\Seeders;

use App\Models\ProductOffering;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $sort = 1;

        foreach (config('cyra.products.items', []) as $product) {
            ProductOffering::query()->updateOrCreate(
                ['slug' => $product['slug']],
                [
                    'category' => $product['category'],
                    'title' => $product['title'],
                    'tagline' => $product['tagline'],
                    'summary' => $product['summary'],
                    'description' => $product['description'],
                    'badge' => $product['badge'] ?? null,
                    'features' => $product['features'] ?? [],
                    'use_cases' => $product['use_cases'] ?? [],
                    'icon' => $product['icon'] ?? 'spark',
                    'sort_order' => $sort++,
                    'is_active' => true,
                    'is_featured' => $product['is_featured'] ?? true,
                ],
            );
        }
    }
}
