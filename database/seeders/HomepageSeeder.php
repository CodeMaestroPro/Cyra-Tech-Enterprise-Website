<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        $sort = 1;

        foreach (config('cyra.homepage.sections', []) as $section) {
            HomepageSection::query()->updateOrCreate(
                ['slug' => $section['slug']],
                [
                    'type' => $section['type'],
                    'eyebrow' => $section['eyebrow'] ?? null,
                    'title' => $section['title'] ?? null,
                    'description' => $section['description'] ?? null,
                    'content' => $section['content'] ?? [],
                    'sort_order' => $sort++,
                    'is_active' => true,
                ],
            );
        }
    }
}
