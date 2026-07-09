<?php

namespace App\Services;

class CompanyPulseService extends BaseService
{
    public function __construct(
        private readonly DashboardService $dashboardService,
        private readonly CrmService $crmService,
        private readonly ProjectManagementService $projectManagementService,
        private readonly TestingOptimizationService $testingOptimizationService,
        private readonly AnalyticsService $analyticsService,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getDashboard(): array
    {
        $configured = config('cyra.company_pulse_workspace', []);
        $thresholds = $configured['thresholds'] ?? ['excellent' => 90, 'good' => 80, 'watch' => 70];

        $commandCenter = $this->dashboardService->getCommandCenter();
        $pulse = $commandCenter['company_pulse'] ?? [];
        $metrics = $this->buildDimensions($pulse['metrics'] ?? [], $configured);
        $overallScore = count($metrics) > 0
            ? (int) round(collect($metrics)->avg('score'))
            : (int) ($pulse['overall_score'] ?? 0);

        $liveSignals = $this->buildLiveSignals();
        $alerts = $this->buildAlerts($metrics, $thresholds);

        return [
            'summary' => [
                'range_label' => $configured['range_label'] ?? 'Current Quarter',
                'overall_score' => $overallScore,
                'excellent_count' => collect($metrics)->where('band', 'excellent')->count(),
                'good_count' => collect($metrics)->where('band', 'good')->count(),
                'watch_count' => collect($metrics)->where('band', 'watch')->count(),
                'critical_count' => collect($metrics)->where('band', 'critical')->count(),
            ],
            'description' => $configured['summary'] ?? '',
            'metrics' => $metrics,
            'live_signals' => $liveSignals,
            'alerts' => $alerts,
            'watchlist' => $configured['watchlist'] ?? [],
            'kpis' => $commandCenter['kpis'] ?? [],
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $metrics
     * @param  array<string, mixed>  $configured
     * @return list<array<string, mixed>>
     */
    private function buildDimensions(array $metrics, array $configured): array
    {
        $meta = $configured['dimension_meta'] ?? [];
        $recommendations = $configured['recommendations'] ?? [];
        $thresholds = $configured['thresholds'] ?? ['excellent' => 90, 'good' => 80, 'watch' => 70];

        return collect($metrics)->map(function (array $metric) use ($meta, $recommendations, $thresholds) {
            $label = $metric['label'];
            $score = (int) ($metric['score'] ?? 0);
            $dimensionMeta = $meta[$label] ?? ['icon' => 'pulse', 'focus' => 'Organizational health indicator.'];

            return [
                'label' => $label,
                'score' => $score,
                'band' => $this->scoreBand($score, $thresholds),
                'band_label' => ucfirst($this->scoreBand($score, $thresholds)),
                'icon' => $dimensionMeta['icon'] ?? 'pulse',
                'focus' => $dimensionMeta['focus'] ?? '',
                'recommendation' => $recommendations[$label] ?? 'Continue monitoring this dimension in executive reviews.',
            ];
        })->values()->all();
    }

    /**
     * @param  array<string, int>  $thresholds
     */
    private function scoreBand(int $score, array $thresholds): string
    {
        return match (true) {
            $score >= ($thresholds['excellent'] ?? 90) => 'excellent',
            $score >= ($thresholds['good'] ?? 80) => 'good',
            $score >= ($thresholds['watch'] ?? 70) => 'watch',
            default => 'critical',
        };
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildLiveSignals(): array
    {
        $crm = $this->crmService->getPipeline()['summary'] ?? [];
        $projects = $this->projectManagementService->getPortfolio()['summary'] ?? [];
        $tasks = $this->projectManagementService->getTaskBoard()['summary'] ?? [];
        $optimization = $this->testingOptimizationService->getDashboard()['summary'] ?? [];
        $analytics = $this->analyticsService->getDashboard(
            (int) config('cyra.command_center.analytics_range_days', 30),
        )['overview'] ?? [];

        return [
            [
                'label' => 'CRM Pipeline',
                'value' => '₦'.number_format((float) ($crm['pipeline_value'] ?? 0)),
                'detail' => ($crm['total'] ?? 0).' active leads',
                'icon' => 'crm',
            ],
            [
                'label' => 'Delivery Progress',
                'value' => ($projects['average_progress'] ?? 0).'% avg',
                'detail' => ($projects['in_progress'] ?? 0).' programs in progress',
                'icon' => 'folder',
            ],
            [
                'label' => 'Task Load',
                'value' => (string) ($tasks['in_progress'] ?? 0).' active',
                'detail' => ($tasks['overdue'] ?? 0).' overdue tasks',
                'icon' => 'checklist',
            ],
            [
                'label' => 'Platform Health',
                'value' => ($optimization['health_score'] ?? 0).'%',
                'detail' => ($optimization['modules_completed'] ?? 0).'/'.($optimization['modules_total'] ?? 0).' modules',
                'icon' => 'server',
            ],
            [
                'label' => 'Digital Engagement',
                'value' => number_format((int) ($analytics['page_views'] ?? 0)).' views',
                'detail' => ($analytics['conversion_rate'] ?? 0).'% conversion',
                'icon' => 'analytics',
            ],
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $metrics
     * @param  array<string, int>  $thresholds
     * @return list<array<string, mixed>>
     */
    private function buildAlerts(array $metrics, array $thresholds): array
    {
        return collect($metrics)
            ->filter(fn (array $metric) => $metric['score'] < ($thresholds['good'] ?? 80))
            ->map(fn (array $metric) => [
                'label' => $metric['label'],
                'score' => $metric['score'],
                'message' => "{$metric['label']} is at {$metric['score']}% — review recommended.",
                'recommendation' => $metric['recommendation'],
            ])
            ->values()
            ->all();
    }
}
