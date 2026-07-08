<?php

namespace App\Repositories;

use App\Models\AnalyticsEvent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsEventRepository extends BaseRepository
{
    public function __construct(AnalyticsEvent $model)
    {
        parent::__construct($model);
    }

    public function recordEvent(array $attributes): AnalyticsEvent
    {
        return $this->model->newQuery()->create($attributes);
    }

    /**
     * @return Collection<int, AnalyticsEvent>
     */
    public function getEventsBetween(Carbon $start, Carbon $end): Collection
    {
        return $this->model->newQuery()
            ->whereBetween('occurred_at', [$start, $end])
            ->orderByDesc('occurred_at')
            ->get();
    }

    public function countEventsBetween(Carbon $start, Carbon $end, ?string $eventType = null): int
    {
        return $this->model->newQuery()
            ->whereBetween('occurred_at', [$start, $end])
            ->when($eventType !== null, fn ($query) => $query->where('event_type', $eventType))
            ->sum('value');
    }

    public function countUniqueSessionsBetween(Carbon $start, Carbon $end): int
    {
        return (int) $this->model->newQuery()
            ->whereBetween('occurred_at', [$start, $end])
            ->whereNotNull('session_hash')
            ->distinct()
            ->count('session_hash');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getDailyPageViewTotals(Carbon $start, Carbon $end): array
    {
        $driver = DB::connection()->getDriverName();
        $dateExpression = $driver === 'sqlite'
            ? "strftime('%Y-%m-%d', occurred_at)"
            : 'DATE(occurred_at)';

        return $this->model->newQuery()
            ->selectRaw("{$dateExpression} as event_date, SUM(value) as total")
            ->whereBetween('occurred_at', [$start, $end])
            ->where('event_type', 'page_view')
            ->groupBy('event_date')
            ->orderBy('event_date')
            ->get()
            ->map(fn ($row) => [
                'date' => $row->event_date,
                'page_views' => (int) $row->total,
            ])
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getTopSubjects(Carbon $start, Carbon $end, string $eventType, int $limit = 5): array
    {
        return $this->model->newQuery()
            ->select('subject', 'subject_label')
            ->selectRaw('SUM(value) as total')
            ->whereBetween('occurred_at', [$start, $end])
            ->where('event_type', $eventType)
            ->groupBy('subject', 'subject_label')
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'subject' => $row->subject,
                'label' => $row->subject_label ?? ucfirst(str_replace('-', ' ', $row->subject)),
                'total' => (int) $row->total,
            ])
            ->values()
            ->all();
    }
}
