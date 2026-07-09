<?php

namespace App\Repositories;

use App\Models\NewsletterSubscriber;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class NewsletterSubscriberRepository extends BaseRepository
{
    public function __construct(NewsletterSubscriber $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?NewsletterSubscriber
    {
        return $this->model->newQuery()
            ->where('email', $email)
            ->first();
    }

    public function subscribe(array $attributes): NewsletterSubscriber
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function reactivate(NewsletterSubscriber $subscriber, ?string $ipAddress = null): NewsletterSubscriber
    {
        $subscriber->update([
            'status' => 'active',
            'ip_address' => $ipAddress,
            'subscribed_at' => now(),
        ]);

        return $subscriber->refresh();
    }

    public function countActive(): int
    {
        return $this->activeQuery()->count();
    }

    public function countActiveSince(DateTimeInterface $since): int
    {
        return $this->activeQuery()
            ->where('subscribed_at', '>=', $since)
            ->count();
    }

    /**
     * @return Collection<int, NewsletterSubscriber>
     */
    public function getRecentActive(int $limit = 10): Collection
    {
        return $this->activeQuery()
            ->orderByDesc('subscribed_at')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Builder<NewsletterSubscriber>
     */
    private function activeQuery(): Builder
    {
        return $this->model->newQuery()->where('status', 'active');
    }
}
