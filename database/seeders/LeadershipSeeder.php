<?php

namespace Database\Seeders;

use App\Models\LeadershipProfile;
use Illuminate\Database\Seeder;

class LeadershipSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = config('cyra.leadership.profiles', []);
        $configuredSlugs = collect($profiles)->pluck('slug')->filter()->all();
        $sort = 1;

        foreach ($profiles as $profile) {
            LeadershipProfile::query()->updateOrCreate(
                ['slug' => $profile['slug']],
                [
                    'name' => $profile['name'],
                    'title' => $profile['title'],
                    'tier' => $profile['tier'] ?? 'executive',
                    'bio' => $profile['bio'],
                    'focus_areas' => $profile['focus_areas'] ?? [],
                    'linkedin_url' => $profile['linkedin_url'] ?? null,
                    'email' => $profile['email'] ?? null,
                    'sort_order' => $sort++,
                    'is_active' => true,
                    'is_featured' => $profile['is_featured'] ?? true,
                ],
            );
        }

        LeadershipProfile::query()
            ->when(
                $configuredSlugs !== [],
                fn ($query) => $query->whereNotIn('slug', $configuredSlugs),
                fn ($query) => $query,
            )
            ->update(['is_active' => false]);
    }
}
