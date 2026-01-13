<?php

namespace App\Interfaces\RepositoryInterface;

use App\Models\Trainer;
use Illuminate\Support\Collection;

interface TrainerRepositoryInterface
{
    public function getAll(): Collection;

    public function find(int $id);
    public function findByName(string $name): ?Trainer;

    public function getLastTrainer(): ?Trainer;
}
