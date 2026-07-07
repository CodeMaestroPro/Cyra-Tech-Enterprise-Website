<?php

namespace Database\Seeders;

use App\Models\SolutionOffering;
use Illuminate\Database\Seeder;

class SolutionSeeder extends Seeder
{
    public function run(): void
    {
        $sort = 1;

        foreach (config('cyra.solutions.offerings', []) as $offering) {
            SolutionOffering::query()->updateOrCreate(
                ['slug' => $offering['slug']],
                [
                    'category' => $offering['category'],
                    'title' => $offering['title'],
                    'summary' => $offering['summary'],
                    'description' => $offering['description'],
                    'capabilities' => $offering['capabilities'] ?? [],
                    'outcomes' => $offering['outcomes'] ?? [],
                    'icon' => $offering['icon'] ?? 'spark',
                    'sort_order' => $sort++,
                    'is_active' => true,
                    'is_featured' => $offering['is_featured'] ?? true,
                ],
            );
        }
    }
}
