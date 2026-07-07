<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    public function run(): void
    {
        $sort = 1;

        foreach (config('cyra.about.pages', []) as $page) {
            AboutPage::query()->updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'route_name' => $page['route_name'],
                    'nav_label' => $page['nav_label'],
                    'eyebrow' => $page['eyebrow'] ?? null,
                    'title' => $page['title'],
                    'description' => $page['description'] ?? null,
                    'content' => $page['content'] ?? [],
                    'seo' => $page['seo'] ?? [],
                    'sort_order' => $sort++,
                    'is_active' => true,
                ],
            );
        }
    }
}
