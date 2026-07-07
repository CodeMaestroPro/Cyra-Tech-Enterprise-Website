<?php

namespace Database\Seeders;

use App\Models\CareerOpening;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        $sort = 1;

        foreach (config('cyra.careers.openings', []) as $opening) {
            CareerOpening::query()->updateOrCreate(
                ['slug' => $opening['slug']],
                [
                    'category' => $opening['category'],
                    'title' => $opening['title'],
                    'department' => $opening['department'],
                    'location' => $opening['location'],
                    'work_type' => $opening['work_type'],
                    'tagline' => $opening['tagline'],
                    'summary' => $opening['summary'],
                    'description' => $opening['description'],
                    'responsibilities' => $opening['responsibilities'] ?? [],
                    'requirements' => $opening['requirements'] ?? [],
                    'experience_level' => $opening['experience_level'] ?? null,
                    'badge' => $opening['badge'] ?? null,
                    'icon' => $opening['icon'] ?? 'spark',
                    'sort_order' => $sort++,
                    'is_active' => true,
                    'is_featured' => $opening['is_featured'] ?? true,
                ],
            );
        }
    }
}
