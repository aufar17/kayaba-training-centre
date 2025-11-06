<?php

namespace App\Interfaces\ServiceInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface TrainingServiceInterface
{
    public function getAll(): Collection;
    public function getById(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;
}
