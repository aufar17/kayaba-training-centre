<?php

namespace App\Interfaces\RepositoryInterface;

use Illuminate\Support\Collection;

interface TrainingRepositoryInterface
{
    public function getAll(): Collection;

    public function find(int $id);
}
