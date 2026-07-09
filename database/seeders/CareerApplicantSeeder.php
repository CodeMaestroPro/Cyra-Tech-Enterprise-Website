<?php

namespace Database\Seeders;

use App\Models\CareerApplicant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CareerApplicantSeeder extends Seeder
{
    public function run(): void
    {
        $configuredReferences = [];

        foreach (config('cyra.career_applicants.applications', []) as $application) {
            $configuredReferences[] = $application['reference'];

            CareerApplicant::query()->updateOrCreate(
                ['reference' => $application['reference']],
                [
                    'opening_slug' => $application['opening_slug'],
                    'name' => $application['name'],
                    'email' => $application['email'],
                    'phone' => $application['phone'] ?? null,
                    'location' => $application['location'] ?? null,
                    'linkedin_url' => $application['linkedin_url'] ?? null,
                    'portfolio_url' => $application['portfolio_url'] ?? null,
                    'cover_letter' => $application['cover_letter'] ?? null,
                    'resume_filename' => $application['resume_filename'] ?? null,
                    'status' => $application['status'] ?? 'new',
                    'source' => $application['source'] ?? 'website',
                    'notes' => $application['notes'] ?? null,
                    'applied_at' => isset($application['applied_days_ago'])
                        ? Carbon::now()->subDays((int) $application['applied_days_ago'])
                        : Carbon::now(),
                ],
            );
        }

        CareerApplicant::query()
            ->whereNotIn('reference', $configuredReferences)
            ->delete();
    }
}
