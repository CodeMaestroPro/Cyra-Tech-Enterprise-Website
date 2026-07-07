<?php

namespace Database\Seeders;

use App\Models\IndustryVertical;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $sort = 1;

        foreach (config('cyra.industries.verticals', []) as $vertical) {
            IndustryVertical::query()->updateOrCreate(
                ['slug' => $vertical['slug']],
                [
                    'category' => $vertical['category'],
                    'title' => $vertical['title'],
                    'tagline' => $vertical['tagline'],
                    'summary' => $vertical['summary'],
                    'description' => $vertical['description'],
                    'challenges' => $vertical['challenges'] ?? [],
                    'capabilities' => $vertical['capabilities'] ?? [],
                    'compliance' => $vertical['compliance'] ?? [],
                    'icon' => $vertical['icon'] ?? 'spark',
                    'sort_order' => $sort++,
                    'is_active' => true,
                    'is_featured' => $vertical['is_featured'] ?? true,
                ],
            );
        }
    }
}
