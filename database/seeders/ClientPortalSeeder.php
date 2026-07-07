<?php

namespace Database\Seeders;

use App\Models\ClientAccount;
use App\Models\ClientEngagement;
use Illuminate\Database\Seeder;

class ClientPortalSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('cyra.client_portal.accounts', []) as $accountData) {
            $engagements = $accountData['engagements'] ?? [];
            unset($accountData['engagements']);

            $account = ClientAccount::query()->updateOrCreate(
                ['slug' => $accountData['slug']],
                [
                    'name' => $accountData['name'],
                    'industry' => $accountData['industry'],
                    'region' => $accountData['region'],
                    'account_manager' => $accountData['account_manager'],
                    'support_email' => $accountData['support_email'],
                    'is_active' => true,
                ],
            );

            $sort = 1;

            foreach ($engagements as $engagement) {
                ClientEngagement::query()->updateOrCreate(
                    [
                        'client_account_id' => $account->id,
                        'slug' => $engagement['slug'],
                    ],
                    [
                        'portfolio_slug' => $engagement['portfolio_slug'] ?? null,
                        'title' => $engagement['title'],
                        'status' => $engagement['status'],
                        'phase' => $engagement['phase'],
                        'progress' => $engagement['progress'] ?? 0,
                        'tagline' => $engagement['tagline'],
                        'summary' => $engagement['summary'],
                        'description' => $engagement['description'],
                        'milestones' => $engagement['milestones'] ?? [],
                        'deliverables' => $engagement['deliverables'] ?? [],
                        'team' => $engagement['team'] ?? [],
                        'sort_order' => $sort++,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
