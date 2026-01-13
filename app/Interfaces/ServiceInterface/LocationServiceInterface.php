<?php

namespace App\Interfaces\ServiceInterface;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface LocationServiceInterface
{
    public function getAll(): Collection;
    public function getById(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;

    public function generateNextCode(): string;
    public function createWithAutoCode(string $name): Location;
}
