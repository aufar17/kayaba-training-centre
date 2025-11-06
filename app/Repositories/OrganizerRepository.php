<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface\OrganizerRepositoryInterface;
use App\Models\Organizer;
use Illuminate\Support\Collection;

class OrganizerRepository implements OrganizerRepositoryInterface
{
    public function getAll(): Collection
    {
        return Organizer::get();
    }

    public function find(int $id)
    {
        return Organizer::findOrFail($id);
    }
}
