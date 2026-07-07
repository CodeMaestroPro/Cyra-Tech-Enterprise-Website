<?php

namespace Database\Seeders;

use App\Models\PlatformModule;
use Illuminate\Database\Seeder;

class PlatformModuleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('cyra.modules', []) as $module) {
            PlatformModule::query()->updateOrCreate(
                ['slug' => $module['slug']],
                [
                    'module_id' => $module['id'],
                    'name' => $module['name'],
                    'status' => $module['status'],
                    'completed_at' => $module['status'] === 'completed' ? now() : null,
                ],
            );
        }
    }
}
