<?php

namespace App\Services;

class StrategicRoadmapService extends BaseService
{
    public function __construct(
        private readonly PlatformService $platformService,
        private readonly ProjectManagementService $projectManagementService,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getDashboard(): array
    {
        $platform = $this->platformService->getStatus();
        $modules = $this->platformService->getModules();
        $portfolio = $this->projectManagementService->getPortfolio();
        $configured = config('cyra.strategic_roadmap', []);

        $initiatives = collect($configured['quarters'] ?? [])
            ->flatMap(fn (array $quarter) => $quarter['initiatives'] ?? []);

        $activeProjects = collect($portfolio['projects'] ?? [])
            ->filter(fn (array $project) => in_array($project['status'], ['in-progress', 'planning'], true))
            ->values()
            ->all();

        $pillarProgress = (int) round(
            collect($configured['pillars'] ?? [])->avg('progress') ?: 0
        );

        return [
            'summary' => [
                'horizon' => $configured['horizon'] ?? '2026–2028',
                'platform_progress' => $platform['modules']['progress'],
                'modules_completed' => $platform['modules']['completed'],
                'modules_total' => $platform['modules']['total'],
                'active_projects' => count($activeProjects),
                'initiatives_total' => $initiatives->count(),
                'initiatives_in_flight' => $initiatives
                    ->filter(fn (array $item) => ($item['status'] ?? '') !== 'completed')
                    ->count(),
                'pillar_progress' => $pillarProgress,
            ],
            'vision' => $configured['vision'] ?? '',
            'executive_summary' => $configured['executive_summary'] ?? '',
            'pillars' => $configured['pillars'] ?? [],
            'phases' => $configured['phases'] ?? [],
            'quarters' => $configured['quarters'] ?? [],
            'milestones' => $configured['milestones'] ?? [],
            'modules' => $modules,
            'projects' => array_slice($portfolio['projects'] ?? [], 0, 6),
        ];
    }
}
