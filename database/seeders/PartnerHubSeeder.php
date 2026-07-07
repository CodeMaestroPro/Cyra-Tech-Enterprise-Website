<?php

namespace Database\Seeders;

use App\Models\PartnerProgram;
use Illuminate\Database\Seeder;

class PartnerHubSeeder extends Seeder
{
    public function run(): void
    {
        $sort = 1;

        foreach (config('cyra.partner_hub.programs', []) as $program) {
            PartnerProgram::query()->updateOrCreate(
                ['slug' => $program['slug']],
                [
                    'category' => $program['category'],
                    'title' => $program['title'],
                    'partner_type' => $program['partner_type'],
                    'region' => $program['region'],
                    'engagement_model' => $program['engagement_model'],
                    'tagline' => $program['tagline'],
                    'summary' => $program['summary'],
                    'description' => $program['description'],
                    'benefits' => $program['benefits'] ?? [],
                    'requirements' => $program['requirements'] ?? [],
                    'enablement' => $program['enablement'] ?? [],
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
