<?php

namespace Database\Seeders;

use App\Models\InnovationInitiative;
use Illuminate\Database\Seeder;

class InnovationLabSeeder extends Seeder
{
    public function run(): void
    {
        $sort = 1;

        foreach (config('cyra.innovation_lab.initiatives', []) as $initiative) {
            InnovationInitiative::query()->updateOrCreate(
                ['slug' => $initiative['slug']],
                [
                    'category' => $initiative['category'],
                    'title' => $initiative['title'],
                    'tagline' => $initiative['tagline'],
                    'summary' => $initiative['summary'],
                    'description' => $initiative['description'],
                    'focus_areas' => $initiative['focus_areas'] ?? [],
                    'deliverables' => $initiative['deliverables'] ?? [],
                    'timeline' => $initiative['timeline'] ?? null,
                    'badge' => $initiative['badge'] ?? null,
                    'icon' => $initiative['icon'] ?? 'spark',
                    'sort_order' => $sort++,
                    'is_active' => true,
                    'is_featured' => $initiative['is_featured'] ?? true,
                ],
            );
        }
    }
}
