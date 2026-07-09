<?php

namespace App\Services;

use App\Repositories\UserRepository;

class SecurityService extends BaseService
{
    public function __construct(
        private readonly TestingOptimizationService $testingOptimizationService,
        private readonly UserRepository $userRepository,
        private readonly PlatformService $platformService,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getWorkspace(): array
    {
        $configured = config('cyra.security_workspace', []);
        $optimization = $this->testingOptimizationService->getDashboard();
        $users = $this->userRepository->getAllWithRoles();
        $health = $this->platformService->getHealth();
        $statusMeta = $configured['status_meta'] ?? [];

        $controls = collect(config('cyra.security.controls', []))
            ->map(fn (array $control) => $this->formatControl($control, $statusMeta))
            ->values()
            ->all();

        $healthChecks = collect($optimization['health_checks'] ?? [])
            ->map(fn (array $check) => [
                'label' => $check['label'],
                'description' => $check['description'] ?? '',
                'status' => $check['status'],
                'status_label' => $check['status_label'] ?? ucfirst($check['status']),
                'icon' => $check['slug'] === 'database' ? 'server' : 'shield',
            ])
            ->values()
            ->all();

        $passingControls = collect($controls)->where('status', 'enabled')->count();

        return [
            'summary' => [
                'posture_score' => (int) ($optimization['summary']['health_score'] ?? 0),
                'controls_enabled' => $passingControls,
                'total_controls' => count($controls),
                'active_users' => $users->where('is_active', true)->count(),
                'health_status' => $health['status'] ?? 'ok',
                'compliance_frameworks' => count(config('cyra.security.compliance', [])),
            ],
            'description' => $configured['summary'] ?? 'Monitor security posture, controls, and compliance readiness.',
            'controls' => $controls,
            'health_checks' => $healthChecks,
            'compliance' => config('cyra.security.compliance', []),
            'portal_security' => config('cyra.client_portal.security', []),
            'quick_links' => $this->buildQuickLinks($configured['quick_links'] ?? []),
            'workspace_notes' => $configured['workspace_notes'] ?? [],
        ];
    }

    /**
     * @param  array<string, mixed>  $control
     * @param  array<string, array<string, string>>  $statusMeta
     * @return array<string, mixed>
     */
    private function formatControl(array $control, array $statusMeta): array
    {
        $status = $control['status'] ?? 'enabled';
        $meta = $statusMeta[$status] ?? ['label' => ucfirst($status), 'variant' => 'success'];

        return [
            'slug' => $control['slug'] ?? '',
            'label' => $control['label'] ?? '',
            'description' => $control['description'] ?? '',
            'category' => $control['category'] ?? 'Platform',
            'icon' => $control['icon'] ?? 'shield',
            'status' => $status,
            'status_label' => $meta['label'],
            'status_variant' => $meta['variant'] ?? 'success',
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $links
     * @return list<array<string, mixed>>
     */
    private function buildQuickLinks(array $links): array
    {
        return collect($links)
            ->map(function (array $link) {
                $route = $link['route'] ?? null;

                return [
                    'label' => $link['label'] ?? '',
                    'icon' => $link['icon'] ?? 'link',
                    'description' => $link['description'] ?? '',
                    'href' => $route ? route($route) : ($link['url'] ?? '#'),
                    'external' => $link['external'] ?? false,
                ];
            })
            ->values()
            ->all();
    }
}
