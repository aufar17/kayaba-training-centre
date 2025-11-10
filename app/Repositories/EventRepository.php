<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface\EventRepositoryInterface;
use App\Models\Event;
use App\Models\EventTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EventRepository implements EventRepositoryInterface
{
    public function getModel(): Builder
    {
        return Event::query();
    }

    public function find(int $id)
    {
        return Event::with(['trainings', 'locations', 'organizers', 'trainers'])->findOrFail($id);
    }

    public function participantModel(): Builder
    {
        return EventTransaction::query();
    }
}
