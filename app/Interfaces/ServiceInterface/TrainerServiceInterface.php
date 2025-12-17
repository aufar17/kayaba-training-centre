<?php

namespace App\Interfaces\ServiceInterface;

use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface TrainerServiceInterface
{
    public function getAll(): Collection;
    public function getById(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;
    public function findByName(string $name): ?Trainer;

    public function generateNextCode(): string;

    public function findOrCreateByName(string $name): Trainer;
}
