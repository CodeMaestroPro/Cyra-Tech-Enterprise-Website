<?php

namespace App\Services;

use App\Repositories\PlatformModuleRepository;
use Illuminate\Support\Facades\DB;

class PlatformService extends BaseService
{
    public function __construct(
        private readonly PlatformModuleRepository $moduleRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getStatus(): array
    {
        $modules = $this->getModules();
        $completed = collect($modules)->where('status', 'completed')->count();
        $total = count($modules);

        return [
            'name' => config('cyra.name'),
            'tagline' => config('cyra.tagline'),
            'version' => config('cyra.version'),
            'environment' => app()->environment(),
            'database' => $this->getDatabaseStatus(),
            'modules' => [
                'total' => $total,
                'completed' => $completed,
                'pending' => $total - $completed,
                'progress' => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
            ],
            'stack' => [
                'backend' => 'Laravel 12',
                'frontend' => 'JavaScript + Blade',
                'styling' => 'Tailwind CSS 4',
                'database' => 'MySQL',
            ],
            'initialized_at' => now()->toIso8601String(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getModules(): array
    {
        $configuredModules = config('cyra.modules', []);

        try {
            $storedModules = $this->moduleRepository->all()->keyBy('slug');
        } catch (\Throwable) {
            $storedModules = collect();
        }

        return collect($configuredModules)->map(function (array $module) use ($storedModules) {
            $stored = $storedModules->get($module['slug']);

            return [
                'id' => $module['id'],
                'slug' => $module['slug'],
                'name' => $module['name'],
                'status' => $stored?->status ?? $module['status'],
                'completed_at' => $stored?->completed_at?->toIso8601String(),
            ];
        })->values()->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function getHealth(): array
    {
        return [
            'status' => 'ok',
            'service' => config('cyra.name'),
            'version' => config('cyra.version'),
            'timestamp' => now()->toIso8601String(),
            'checks' => [
                'application' => true,
                'database' => $this->isDatabaseConnected(),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getDatabaseStatus(): array
    {
        $connected = $this->isDatabaseConnected();

        return [
            'driver' => config('database.default'),
            'connected' => $connected,
            'status' => $connected ? 'connected' : 'disconnected',
        ];
    }

    private function isDatabaseConnected(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
