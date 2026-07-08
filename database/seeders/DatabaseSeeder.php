<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PlatformModuleSeeder::class,
            RolePermissionSeeder::class,
            NavigationSeeder::class,
            HomepageSeeder::class,
            AboutSeeder::class,
            LeadershipSeeder::class,
            SolutionSeeder::class,
            ProductSeeder::class,
            IndustrySeeder::class,
            PortfolioSeeder::class,
            InnovationLabSeeder::class,
            CommunitySeeder::class,
            InsightSeeder::class,
            CareerSeeder::class,
            PartnerHubSeeder::class,
            ClientPortalSeeder::class,
            ClientUserSeeder::class,
            AdminUserSeeder::class,
            CmsSeeder::class,
            MediaLibrarySeeder::class,
            AnalyticsSeeder::class,
        ]);
    }
}
