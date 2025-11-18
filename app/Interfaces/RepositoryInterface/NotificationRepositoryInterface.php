<?php

namespace App\Interfaces\RepositoryInterface;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface NotificationRepositoryInterface
{
    public function getAll(): Builder;
    public function find(int $id);
    public function getNotificationsFor(string $dept, array $types = []): Builder;
}
