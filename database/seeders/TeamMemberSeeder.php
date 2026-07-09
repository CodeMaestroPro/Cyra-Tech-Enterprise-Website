<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $sort = 1;
        $configuredSlugs = [];

        foreach (config('cyra.team_members.profiles', []) as $member) {
            $configuredSlugs[] = $member['slug'];

            TeamMember::query()->updateOrCreate(
                ['slug' => $member['slug']],
                [
                    'name' => $member['name'],
                    'title' => $member['title'],
                    'department' => $member['department'],
                    'location' => $member['location'] ?? null,
                    'work_type' => $member['work_type'] ?? null,
                    'bio' => $member['bio'],
                    'skills' => $member['skills'] ?? [],
                    'linkedin_url' => $member['linkedin_url'] ?? null,
                    'email' => $member['email'] ?? null,
                    'sort_order' => $sort++,
                    'is_active' => true,
                    'is_featured' => $member['is_featured'] ?? false,
                ],
            );
        }

        TeamMember::query()
            ->whereNotIn('slug', $configuredSlugs)
            ->update(['is_active' => false]);
    }
}
