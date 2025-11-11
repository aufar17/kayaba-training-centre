<?php

namespace App\Services;

use App\Interfaces\RepositoryInterface\EventRepositoryInterface;
use App\Interfaces\RepositoryInterface\TrainingRepositoryInterface;
use App\Interfaces\ServiceInterface\DashboardServiceInterface;
use App\Interfaces\ServiceInterface\EventServiceInterface;
use App\Interfaces\ServiceInterface\OrganizerServiceInterface;
use App\Models\Auth\CTUser;
use App\Models\Organizer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService implements DashboardServiceInterface
{
    protected TrainingRepositoryInterface $trainingRepository;
    protected EventRepositoryInterface $eventRepository;

    public function __construct(
        TrainingRepositoryInterface $trainingRepository,
        EventRepositoryInterface $eventRepository
    ) {
        $this->trainingRepository = $trainingRepository;
        $this->eventRepository = $eventRepository;
    }

    public function countEmployee()
    {
        $employees = CTUser::get()->count();
        return $employees;
    }
    public function countTraining()
    {
        return $this->trainingRepository->getAll()->count();
    }

    public function countParticipantComplete()
    {
        $completed = $this->eventRepository->participantModel()
            ->where('completed', 1)
            ->count();

        return $completed;
    }

    public function countParticipantNotCompleted()
    {
        $notCompleted = $this->eventRepository->participantModel()
            ->where('completed', -1)
            ->count();

        return $notCompleted;
    }
}
