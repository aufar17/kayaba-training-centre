<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface\LocationRepositoryInterface;
use App\Models\Location;
use Illuminate\Support\Collection;

class LocationRepository implements LocationRepositoryInterface
{
    public function getAll(): Collection
    {
        return Location::get();
    }

    public function find(int $id)
    {
        return Location::findOrFail($id);
    }

    public function getLastCode(): ?string
    {
        return Location::orderBy('id', 'desc')->value('code');
    }
}
