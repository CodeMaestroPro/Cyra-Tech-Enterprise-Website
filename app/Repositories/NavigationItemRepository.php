<?php

namespace App\Repositories;

use App\Models\NavigationItem;
use Illuminate\Database\Eloquent\Collection;

class NavigationItemRepository extends BaseRepository
{
    public function __construct(NavigationItem $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, NavigationItem>
     */
    public function getActiveByLocation(string $location): Collection
    {
        return $this->model->newQuery()
            ->where('location', $location)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * @return Collection<int, NavigationItem>
     */
    public function getActiveAdminItems(): Collection
    {
        return $this->model->newQuery()
            ->where('location', 'admin_sidebar')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * @return Collection<int, NavigationItem>
     */
    public function getAllAdminItems(): Collection
    {
        return $this->model->newQuery()
            ->where('location', 'admin_sidebar')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateItem(NavigationItem $item, array $attributes): NavigationItem
    {
        $item->update($attributes);

        return $item->refresh();
    }
}
