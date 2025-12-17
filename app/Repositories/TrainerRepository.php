<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface\TrainerRepositoryInterface;
use App\Models\Trainer;
use Illuminate\Support\Collection;

class TrainerRepository implements TrainerRepositoryInterface
{
    public function getAll(): Collection
    {
        return Trainer::get();
    }

    public function find(int $id)
    {
        return Trainer::findOrFail($id);
    }

    public function findByName(string $name): ?Trainer
    {
        return Trainer::whereRaw(
            'LOWER(name) = ?',
            [strtolower(trim($name))]
        )->first();
    }

    public function getLastTrainer(): ?Trainer
    {
        return Trainer::orderBy('id', 'desc')->first();
    }
}
