<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface\NotificationRepositoryInterface;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Builder;

class NotificationRepository implements NotificationRepositoryInterface
{
    protected function baseQuery(): Builder
    {
        return Notification::with(['events', 'transactions']);
    }



    public function getAll(): Builder
    {
        return $this->baseQuery();
    }

    public function find(int $id)
    {
        return $this->baseQuery()->findOrFail($id);
    }

    public function getNotificationsFor(string $dept, array $types = []): Builder
    {
        return $this->baseQuery()
            ->when(!empty($types), function ($q) use ($types) {
                $q->whereIn('type', $types);
            })
            ->whereHas('transactions', function ($q) use ($dept) {
                $q->whereIn('target', [$dept, 'ALL']);
            });
    }
}
