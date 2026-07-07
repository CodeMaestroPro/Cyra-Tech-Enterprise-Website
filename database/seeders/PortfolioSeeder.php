<?php

namespace Database\Seeders;

use App\Models\PortfolioProject;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $sort = 1;

        foreach (config('cyra.portfolio.projects', []) as $project) {
            PortfolioProject::query()->updateOrCreate(
                ['slug' => $project['slug']],
                [
                    'category' => $project['category'],
                    'title' => $project['title'],
                    'client_name' => $project['client_name'],
                    'tagline' => $project['tagline'],
                    'summary' => $project['summary'],
                    'description' => $project['description'],
                    'services' => $project['services'] ?? [],
                    'outcomes' => $project['outcomes'] ?? [],
                    'metrics' => $project['metrics'] ?? [],
                    'duration' => $project['duration'] ?? null,
                    'icon' => $project['icon'] ?? 'spark',
                    'sort_order' => $sort++,
                    'is_active' => true,
                    'is_featured' => $project['is_featured'] ?? true,
                ],
            );
        }
    }
}
