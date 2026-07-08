<?php

namespace App\Services;

use App\Models\AnalyticsEvent;
use App\Models\CmsPage;
use App\Models\ContactInquiry;
use App\Models\MediaAsset;
use App\Models\User;
use App\Repositories\AnalyticsEventRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AnalyticsService extends BaseService
{
    public function __construct(
        private readonly AnalyticsEventRepository $analyticsEventRepository,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function trackEvent(array $data, ?User $user = null, ?string $sessionHash = null): array
    {
        $event = $this->analyticsEventRepository->recordEvent([
            'event_type' => $data['event_type'],
            'source' => $data['source'] ?? 'web',
            'subject' => $data['subject'],
            'subject_label' => $data['subject_label'] ?? null,
            'metadata' => $data['metadata'] ?? [],
            'value' => $data['value'] ?? 1,
            'session_hash' => $sessionHash,
            'user_id' => $user?->id,
            'occurred_at' => now(),
        ]);

        return $this->formatEvent($event);
    }

    /**
     * @return array<string, mixed>
     */
    public function getDashboard(int $rangeDays = 30): array
    {
        $rangeDays = max(1, min($rangeDays, 90));
        $end = now()->endOfDay();
        $start = now()->subDays($rangeDays - 1)->startOfDay();

        $pageViews = $this->analyticsEventRepository->countEventsBetween($start, $end, 'page_view');
        $moduleViews = $this->analyticsEventRepository->countEventsBetween($start, $end, 'module_view');
        $formSubmits = $this->analyticsEventRepository->countEventsBetween($start, $end, 'form_submit');
        $portalLogins = $this->analyticsEventRepository->countEventsBetween($start, $end, 'portal_login');
        $uniqueSessions = $this->analyticsEventRepository->countUniqueSessionsBetween($start, $end);
        $contactInquiries = ContactInquiry::query()->whereBetween('created_at', [$start, $end])->count();

        $conversionRate = $pageViews > 0
            ? round(($formSubmits / $pageViews) * 100, 1)
            : 0.0;

        return [
            'range_days' => $rangeDays,
            'period' => [
                'start' => $start->toDateString(),
                'end' => $end->toDateString(),
            ],
            'overview' => [
                'page_views' => $pageViews,
                'module_views' => $moduleViews,
                'unique_sessions' => $uniqueSessions,
                'form_submissions' => $formSubmits,
                'contact_inquiries' => $contactInquiries,
                'portal_logins' => $portalLogins,
                'conversion_rate' => $conversionRate,
            ],
            'traffic_trend' => $this->buildTrafficTrend($start, $end, $rangeDays),
            'top_pages' => $this->analyticsEventRepository->getTopSubjects($start, $end, 'page_view'),
            'top_modules' => $this->analyticsEventRepository->getTopSubjects($start, $end, 'module_view'),
            'lead_sources' => $this->analyticsEventRepository->getTopSubjects($start, $end, 'form_submit'),
            'platform_snapshot' => [
                'cms_pages' => CmsPage::query()->where('status', 'published')->count(),
                'media_assets' => MediaAsset::query()->where('is_active', true)->count(),
                'contact_inquiries_total' => ContactInquiry::query()->count(),
                'active_users' => User::query()->where('is_active', true)->count(),
            ],
            'insights' => config('cyra.analytics.insights', []),
        ];
    }

    /**
     * @param  array<string, mixed>  $pattern
     */
    public function seedEventsFromPattern(array $pattern, Carbon $day): void
    {
        $count = random_int($pattern['daily_min'], $pattern['daily_max']);

        for ($i = 0; $i < $count; $i++) {
            $occurredAt = $day->copy()->addMinutes(random_int(0, 1439));

            $this->analyticsEventRepository->recordEvent([
                'event_type' => $pattern['event_type'],
                'source' => $pattern['source'] ?? 'web',
                'subject' => $pattern['subject'],
                'subject_label' => $pattern['subject_label'] ?? null,
                'metadata' => $pattern['metadata'] ?? [],
                'value' => 1,
                'session_hash' => hash('sha256', Str::uuid()->toString()),
                'occurred_at' => $occurredAt,
            ]);
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildTrafficTrend(Carbon $start, Carbon $end, int $rangeDays): array
    {
        $dailyTotals = collect($this->analyticsEventRepository->getDailyPageViewTotals($start, $end))
            ->keyBy('date');

        $trend = [];
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $date = $cursor->toDateString();
            $trend[] = [
                'date' => $date,
                'label' => $cursor->format('M j'),
                'page_views' => (int) ($dailyTotals[$date]['page_views'] ?? 0),
            ];
            $cursor->addDay();
        }

        if ($rangeDays > 14) {
            return collect($trend)
                ->filter(fn (array $point, int $index) => $index % 2 === 0 || $index === count($trend) - 1)
                ->values()
                ->all();
        }

        return $trend;
    }

    /**
     * @return array<string, mixed>
     */
    private function formatEvent(AnalyticsEvent $event): array
    {
        return [
            'id' => $event->id,
            'event_type' => $event->event_type,
            'source' => $event->source,
            'subject' => $event->subject,
            'subject_label' => $event->subject_label,
            'value' => $event->value,
            'occurred_at' => $event->occurred_at?->toIso8601String(),
        ];
    }
}
