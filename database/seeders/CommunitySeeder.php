<?php

namespace Database\Seeders;

use App\Models\CommunityProgram;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    public function run(): void
    {
        $sort = 1;

        foreach (config('cyra.community.programs', []) as $program) {
            CommunityProgram::query()->updateOrCreate(
                ['slug' => $program['slug']],
                [
                    'category' => $program['category'],
                    'title' => $program['title'],
                    'tagline' => $program['tagline'],
                    'summary' => $program['summary'],
                    'description' => $program['description'],
                    'benefits' => $program['benefits'] ?? [],
                    'activities' => $program['activities'] ?? [],
                    'membership' => $program['membership'] ?? null,
                    'schedule' => $program['schedule'] ?? null,
                    'badge' => $program['badge'] ?? null,
                    'icon' => $program['icon'] ?? 'spark',
                    'sort_order' => $sort++,
                    'is_active' => true,
                    'is_featured' => $program['is_featured'] ?? true,
                ],
            );
        }
    }
}
