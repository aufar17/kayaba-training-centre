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
        return Event::query()->with(['trainings.matrix', 'organizers', 'trainers', 'locations']);
    }

    public function find(int $id)
    {
        return Event::with(['trainings', 'locations', 'organizers', 'trainers'])->findOrFail($id);
    }

    public function participantModel(): Builder
    {
        return EventTransaction::query();
    }

    public function filterByMatrixDepartment(string $dept): Builder
    {
        return $this->getModel()
            ->whereHas('trainings.matrix', function ($q) use ($dept) {
                $q->when($dept !== 'ALL', function ($q2) use ($dept) {
                    $q2->where('dept', $dept)
                        ->orWhere('dept', 'ALL');
                });
            });
    }
}
