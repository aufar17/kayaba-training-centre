<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface\EventRepositoryInterface;
use App\Models\Event;
use Illuminate\Support\Collection;

class EventRepository implements EventRepositoryInterface
{
    public function getAll(): Collection
    {
        return Event::with(['trainings', 'organizers', 'trainers', 'locations'])->latest()->get();
    }

    public function find(int $id)
    {
        return Event::findOrFail($id);
    }
}
