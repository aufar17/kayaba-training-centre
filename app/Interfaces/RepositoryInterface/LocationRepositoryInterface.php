<?php

namespace App\Interfaces\RepositoryInterface;

use Illuminate\Support\Collection;

interface LocationRepositoryInterface
{
    public function getAll(): Collection;

    public function find(int $id);

    public function getLastCode(): ?string;
}
