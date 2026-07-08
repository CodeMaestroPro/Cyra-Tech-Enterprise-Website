<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\CrmService;
use Illuminate\Database\Seeder;

class CrmSeeder extends Seeder
{
    public function run(): void
    {
        $assignee = User::query()
            ->where('email', config('cyra.admin.email'))
            ->first();

        $service = app(CrmService::class);
        $sort = 1;

        foreach (config('cyra.crm.seed_leads', []) as $lead) {
            $service->seedLead([
                ...$lead,
                'sort_order' => $sort++,
            ], $assignee);
        }
    }
}
