<?php

namespace Database\Seeders;

use App\Services\AnalyticsService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AnalyticsSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(AnalyticsService::class);
        $days = (int) config('cyra.analytics.seed_days', 30);
        $patterns = config('cyra.analytics.seed_patterns', []);

        for ($offset = $days - 1; $offset >= 0; $offset--) {
            $day = Carbon::now()->subDays($offset)->startOfDay();

            foreach ($patterns as $pattern) {
                $service->seedEventsFromPattern($pattern, $day);
            }
        }
    }
}
