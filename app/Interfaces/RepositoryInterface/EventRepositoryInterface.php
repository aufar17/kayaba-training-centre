<?php

namespace App\Interfaces\RepositoryInterface;

use Illuminate\Support\Collection;

interface EventRepositoryInterface
{
    public function getAll(): Collection;

    public function find(int $id);
}
