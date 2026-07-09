<?php

namespace App\Services;

class BusinessIntelligenceService extends BaseService
{
    public function __construct(
        private readonly DashboardService $dashboardService,
        private readonly AnalyticsService $analyticsService,
        private readonly CrmService $crmService,
        private readonly ProjectManagementService $projectManagementService,
        private readonly TestingOptimizationService $testingOptimizationService,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getDashboard(): array
    {
        $configured = config('cyra.business_intelligence', []);
        $rangeDays = (int) config('cyra.command_center.analytics_range_days', 30);

        $commandCenter = $this->dashboardService->getCommandCenter();
        $analytics = $this->analyticsService->getDashboard($rangeDays);
        $crm = $this->crmService->getPipeline();
        $projects = $this->projectManagementService->getPortfolio();
        $tasks = $this->projectManagementService->getTaskBoard();
        $optimization = $this->testingOptimizationService->getDashboard();

        $overview = $analytics['overview'] ?? [];
        $crmSummary = $crm['summary'] ?? [];
        $projectSummary = $projects['summary'] ?? [];
        $pulseScore = $commandCenter['company_pulse']['overall_score'] ?? 0;

        return [
            'summary' => [
                'range_label' => $configured['range_label'] ?? "Last {$rangeDays} Days",
                'pipeline_value' => '₦'.number_format((float) ($crmSummary['pipeline_value'] ?? 0)),
                'active_leads' => (int) ($crmSummary['total'] ?? 0),
                'conversion_rate' => ($overview['conversion_rate'] ?? 0).'%',
                'page_views' => number_format((int) ($overview['page_views'] ?? 0)),
                'company_pulse' => $pulseScore,
                'avg_project_progress' => (int) ($projectSummary['average_progress'] ?? 0),
                'health_score' => (int) ($optimization['summary']['health_score'] ?? 0),
            ],
            'kpis' => $commandCenter['kpis'] ?? [],
            'company_pulse' => $commandCenter['company_pulse'] ?? [],
            'crm' => [
                'summary' => $crmSummary,
                'stages' => collect($crm['stages'] ?? [])
                    ->map(fn (array $stage) => [
                        'label' => $stage['label'],
                        'count' => $stage['count'] ?? 0,
                        'value' => '₦'.number_format((float) ($stage['value'] ?? 0)),
                        'raw_value' => (float) ($stage['value'] ?? 0),
                    ])
                    ->values()
                    ->all(),
            ],
            'digital' => [
                'overview' => $overview,
                'traffic_trend' => $analytics['traffic_trend'] ?? [],
                'top_pages' => array_slice($analytics['top_pages'] ?? [], 0, 5),
                'top_modules' => array_slice($analytics['top_modules'] ?? [], 0, 5),
                'lead_sources' => array_slice($analytics['lead_sources'] ?? [], 0, 5),
            ],
            'delivery' => [
                'summary' => $projectSummary,
                'tasks' => $tasks['summary'] ?? [],
                'projects' => array_slice($projects['projects'] ?? [], 0, 6),
            ],
            'platform' => [
                'health_score' => $optimization['summary']['health_score'] ?? 0,
                'modules_completed' => $optimization['summary']['modules_completed'] ?? 0,
                'modules_total' => $optimization['summary']['modules_total'] ?? 0,
                'feature_tests' => $optimization['summary']['feature_tests'] ?? 0,
                'seo_score' => $optimization['summary']['seo_score'] ?? 0,
            ],
            'data_domains' => $this->buildDataDomains($configured['data_domains'] ?? [], $crmSummary, $overview, $projectSummary, $pulseScore, $optimization),
            'reports' => $this->buildReports($configured['reports'] ?? []),
            'insights' => $this->buildInsights($overview, $crmSummary, $projectSummary, $tasks['summary'] ?? [], $pulseScore),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $domains
     * @param  array<string, mixed>  $crmSummary
     * @param  array<string, mixed>  $overview
     * @param  array<string, mixed>  $projectSummary
     * @param  array<string, mixed>  $optimization
     * @return list<array<string, mixed>>
     */
    private function buildDataDomains(
        array $domains,
        array $crmSummary,
        array $overview,
        array $projectSummary,
        int $pulseScore,
        array $optimization,
    ): array {
        $metricsBySlug = [
            'revenue' => [
                'primary' => '₦'.number_format((float) ($crmSummary['pipeline_value'] ?? 0)),
                'secondary' => ($crmSummary['total'] ?? 0).' active leads',
            ],
            'digital' => [
                'primary' => number_format((int) ($overview['page_views'] ?? 0)).' views',
                'secondary' => ($overview['conversion_rate'] ?? 0).'% conversion',
            ],
            'delivery' => [
                'primary' => ($projectSummary['in_progress'] ?? 0).' active programs',
                'secondary' => ($projectSummary['average_progress'] ?? 0).'% avg progress',
            ],
            'operations' => [
                'primary' => $pulseScore.'% pulse',
                'secondary' => 'Organizational health',
            ],
            'platform' => [
                'primary' => ($optimization['summary']['health_score'] ?? 0).'% health',
                'secondary' => ($optimization['summary']['modules_completed'] ?? 0).'/'.($optimization['summary']['modules_total'] ?? 0).' modules',
            ],
        ];

        return collect($domains)->map(function (array $domain) use ($metricsBySlug) {
            $slug = $domain['slug'] ?? '';
            $metrics = $metricsBySlug[$slug] ?? ['primary' => '—', 'secondary' => 'No data'];

            return [
                ...$domain,
                'primary_metric' => $metrics['primary'],
                'secondary_metric' => $metrics['secondary'],
            ];
        })->values()->all();
    }

    /**
     * @param  list<array<string, mixed>>  $reports
     * @return list<array<string, mixed>>
     */
    private function buildReports(array $reports): array
    {
        return collect($reports)->map(function (array $report) {
            $routeName = $report['route'] ?? null;

            return [
                ...$report,
                'url' => $routeName !== null && \Illuminate\Support\Facades\Route::has($routeName)
                    ? route($routeName)
                    : '#',
            ];
        })->values()->all();
    }

    /**
     * @param  array<string, mixed>  $overview
     * @param  array<string, mixed>  $crmSummary
     * @param  array<string, mixed>  $projectSummary
     * @param  array<string, mixed>  $taskSummary
     * @return list<string>
     */
    private function buildInsights(
        array $overview,
        array $crmSummary,
        array $projectSummary,
        array $taskSummary,
        int $pulseScore,
    ): array {
        $insights = collect();

        $insights->push(sprintf(
            'CRM pipeline holds %d active leads worth %s with %d high-priority opportunities.',
            (int) ($crmSummary['total'] ?? 0),
            '₦'.number_format((float) ($crmSummary['pipeline_value'] ?? 0)),
            (int) ($crmSummary['high_priority'] ?? 0),
        ));

        $insights->push(sprintf(
            'Digital estate recorded %s page views and %s form submissions (%s conversion) in the reporting period.',
            number_format((int) ($overview['page_views'] ?? 0)),
            number_format((int) ($overview['form_submissions'] ?? 0)),
            ($overview['conversion_rate'] ?? 0).'%',
        ));

        $insights->push(sprintf(
            'Delivery portfolio: %d programs in progress at %d%% average completion with %d open tasks (%d overdue).',
            (int) ($projectSummary['in_progress'] ?? 0),
            (int) ($projectSummary['average_progress'] ?? 0),
            (int) ($projectSummary['open_tasks'] ?? 0),
            (int) ($taskSummary['overdue'] ?? 0),
        ));

        $insights->push(sprintf(
            'Company pulse score is %d%% — %s for executive steering this period.',
            $pulseScore,
            $pulseScore >= 85 ? 'strong performance across operational dimensions' : 'review lower-scoring pulse metrics on the Command Center',
        ));

        if (($crmSummary['inbound_inquiries'] ?? 0) > 0) {
            $insights->push(sprintf(
                '%d inbound inquiries are available for CRM conversion — prioritize qualification this week.',
                (int) $crmSummary['inbound_inquiries'],
            ));
        }

        return $insights->values()->all();
    }
}
