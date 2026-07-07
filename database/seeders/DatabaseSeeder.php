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
            AdminUserSeeder::class,
        ]);
    }
}
