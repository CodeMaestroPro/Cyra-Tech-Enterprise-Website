<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\ProjectManagementService;
use Illuminate\Database\Seeder;

class ProjectManagementSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::query()
            ->where('email', config('cyra.admin.email'))
            ->first();

        $service = app(ProjectManagementService::class);
        $sort = 1;

        foreach (config('cyra.project_management.seed_projects', []) as $seedProject) {
            $tasks = $seedProject['tasks'] ?? [];
            unset($seedProject['tasks']);

            $project = $service->seedProject([
                ...$seedProject,
                'sort_order' => $sort++,
            ], $manager);

            $taskSort = 1;

            foreach ($tasks as $task) {
                $service->seedTask($project, [
                    ...$task,
                    'sort_order' => $taskSort++,
                ]);
            }
        }
    }
}
