<?php

namespace App\Services;

use App\Models\ClientEngagement;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Repositories\NewsletterSubscriberRepository;

class DashboardService extends BaseService
{
    public function __construct(
        private readonly AnalyticsService $analyticsService,
        private readonly PlatformService $platformService,
        private readonly NewsletterSubscriberRepository $newsletterSubscriberRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getCommandCenter(?User $user = null): array
    {
        $user ??= auth()->user();
        $analytics = $this->analyticsService->getDashboard(
            (int) config('cyra.command_center.analytics_range_days', 30),
        );

        $kpis = $this->buildKpis($analytics);
        $companyPulse = $this->buildCompanyPulse();

        return [
            'greeting' => $this->buildGreeting($user),
            'datetime' => now()->format('l, F j, Y | h:i A T'),
            'kpis' => $kpis,
            'executive_brief' => $this->buildExecutiveBrief($analytics),
            'company_pulse' => $companyPulse,
            'website_analytics' => $this->buildWebsiteAnalytics($analytics),
            'projects' => $this->buildProjects(),
            'quick_actions' => $this->buildQuickActions(),
            'upcoming_events' => config('cyra.command_center.upcoming_events', []),
            'tasks' => config('cyra.command_center.tasks', []),
            'system_status' => $this->buildSystemStatus(),
            'recent_activities' => config('cyra.command_center.recent_activities', []),
            'newsletter_subscriptions' => $this->buildNewsletterSubscriptions(),
            'platform' => [
                'modules_completed' => $this->platformService->getStatus()['modules']['completed'] ?? 0,
                'modules_total' => $this->platformService->getStatus()['modules']['total'] ?? 25,
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $analytics
     * @return list<array<string, mixed>>
     */
    private function buildKpis(array $analytics): array
    {
        $configured = config('cyra.command_center.kpis', []);
        $overview = $analytics['overview'] ?? [];
        $activeEngagements = ClientEngagement::query()->whereIn('status', ['active', 'on-track'])->count();
        $recentInquiries = (int) ($overview['contact_inquiries'] ?? 0);

        $overrides = [
            'projects-running' => [
                'value' => (string) $activeEngagements,
            ],
            'new-leads' => [
                'value' => (string) max($recentInquiries, (int) ($overview['form_submissions'] ?? 0)),
            ],
        ];

        return collect($configured)->map(function (array $kpi) use ($overrides) {
            $slug = $kpi['slug'] ?? null;

            if ($slug !== null && isset($overrides[$slug])) {
                $kpi = array_merge($kpi, $overrides[$slug]);
            }

            return $kpi;
        })->values()->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function buildGreeting(?User $user): array
    {
        $hour = (int) now()->format('G');
        $period = match (true) {
            $hour < 12 => 'Morning',
            $hour < 17 => 'Afternoon',
            default => 'Evening',
        };

        return [
            'period' => $period,
            'message' => "Good {$period},",
            'name' => $user?->name ?? 'Team Member',
            'role' => $user?->getPrimaryRoleName() ?? 'Team Member',
            'subtitle' => 'Welcome back to '.config('cyra.name').' Command Center.',
        ];
    }

    /**
     * @param  array<string, mixed>  $analytics
     * @return array<string, mixed>
     */
    private function buildExecutiveBrief(array $analytics): array
    {
        $brief = config('cyra.command_center.executive_brief', []);
        $overview = $analytics['overview'] ?? [];

        $summary = collect($brief['summary'] ?? [])->map(function (string $line) use ($overview) {
            return str_replace(
                [':page_views', ':form_submissions', ':conversion_rate'],
                [
                    number_format((int) ($overview['page_views'] ?? 0)),
                    number_format((int) ($overview['form_submissions'] ?? 0)),
                    ($overview['conversion_rate'] ?? 0).'%',
                ],
                $line,
            );
        })->values()->all();

        return [
            ...$brief,
            'summary' => $summary,
            'report_route' => route('admin.analytics.index'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildCompanyPulse(): array
    {
        $metrics = config('cyra.command_center.company_pulse.metrics', []);
        $overall = count($metrics) > 0
            ? (int) round(collect($metrics)->avg('score'))
            : 0;

        return [
            'overall_score' => $overall,
            'metrics' => $metrics,
        ];
    }

    /**
     * @param  array<string, mixed>  $analytics
     * @return array<string, mixed>
     */
    private function buildWebsiteAnalytics(array $analytics): array
    {
        $configured = config('cyra.command_center.website_analytics', []);
        $overview = $analytics['overview'] ?? [];
        $trend = $analytics['traffic_trend'] ?? [];

        return [
            'title' => $configured['title'] ?? 'Website Analytics Overview',
            'range_label' => $configured['range_label'] ?? 'Last 30 Days',
            'metrics' => [
                [
                    'label' => 'Visitors',
                    'value' => number_format((int) ($overview['unique_sessions'] ?? 0)),
                    'change' => $configured['metrics'][0]['change'] ?? '+0%',
                    'positive' => true,
                ],
                [
                    'label' => 'Page Views',
                    'value' => number_format((int) ($overview['page_views'] ?? 0)),
                    'change' => $configured['metrics'][1]['change'] ?? '+0%',
                    'positive' => true,
                ],
                [
                    'label' => 'Bounce Rate',
                    'value' => $configured['metrics'][2]['value'] ?? '32.6%',
                    'change' => $configured['metrics'][2]['change'] ?? '-5.3%',
                    'positive' => true,
                ],
                [
                    'label' => 'Avg. Session',
                    'value' => $configured['metrics'][3]['value'] ?? '04:35',
                    'change' => $configured['metrics'][3]['change'] ?? '+12.6%',
                    'positive' => true,
                ],
            ],
            'traffic_trend' => $trend,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildProjects(): array
    {
        $configured = collect(config('cyra.command_center.projects', []));

        $liveEngagements = ClientEngagement::query()
            ->with('account')
            ->orderByDesc('progress')
            ->limit(5)
            ->get()
            ->map(fn (ClientEngagement $engagement) => [
                'name' => $engagement->title,
                'client' => $engagement->account?->name,
                'status' => ucfirst(str_replace('-', ' ', $engagement->status)),
                'status_variant' => in_array($engagement->status, ['active', 'on-track'], true) ? 'primary' : 'success',
                'progress' => $engagement->progress,
            ])
            ->values();

        if ($liveEngagements->isNotEmpty()) {
            return $liveEngagements->take(5)->all();
        }

        return $configured->values()->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildQuickActions(): array
    {
        return collect(config('cyra.command_center.quick_actions', []))
            ->map(function (array $action) {
                if (($action['route'] ?? null) !== null && \Illuminate\Support\Facades\Route::has($action['route'])) {
                    $action['url'] = route($action['route'], $action['params'] ?? []);
                } else {
                    $action['url'] = $action['url'] ?? '#';
                }

                return $action;
            })
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function buildNewsletterSubscriptions(): array
    {
        $subscribers = $this->newsletterSubscriberRepository
            ->getRecentActive(8)
            ->map(fn (NewsletterSubscriber $subscriber) => [
                'email' => $subscriber->email,
                'source' => ucfirst(str_replace('-', ' ', $subscriber->source)),
                'subscribed_at' => ($subscriber->subscribed_at ?? $subscriber->created_at)?->format('M j, Y'),
                'subscribed_relative' => ($subscriber->subscribed_at ?? $subscriber->created_at)?->diffForHumans(),
            ])
            ->values()
            ->all();

        return [
            'title' => 'Newsletter Subscriptions',
            'description' => 'Recent footer sign-ups and active subscribers.',
            'stats' => [
                [
                    'label' => 'Total Active',
                    'value' => (string) $this->newsletterSubscriberRepository->countActive(),
                ],
                [
                    'label' => 'This Week',
                    'value' => (string) $this->newsletterSubscriberRepository->countActiveSince(now()->startOfWeek()),
                ],
                [
                    'label' => 'This Month',
                    'value' => (string) $this->newsletterSubscriberRepository->countActiveSince(now()->startOfMonth()),
                ],
            ],
            'subscribers' => $subscribers,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSystemStatus(): array
    {
        $health = $this->platformService->getHealth();
        $configured = config('cyra.command_center.system_status', []);

        $services = collect($configured['services'] ?? [])->map(function (array $service) use ($health) {
            if (($service['slug'] ?? null) === 'database') {
                $service['status'] = ($health['checks']['database'] ?? false) ? 'operational' : 'degraded';
            }

            return $service;
        })->values()->all();

        return [
            'title' => $configured['title'] ?? 'System Status',
            'overall' => ($health['status'] ?? 'ok') === 'ok' ? 'operational' : 'degraded',
            'services' => $services,
        ];
    }
}
