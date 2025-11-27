<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\DashboardServiceInterface;
use App\Interfaces\ServiceInterface\EventServiceInterface;
use Livewire\Component;

class Dashboard extends Component
{
    protected DashboardServiceInterface $dashboardService;
    protected EventServiceInterface $eventService;

    public function mount()
    {
        $this->dashboardService = app(DashboardServiceInterface::class);
        $this->eventService = app(EventServiceInterface::class);
    }
    public function render()
    {
        $data = [
            'mappings' => $this->mapping(),
            'upcoming' => $this->getUpcomingEvent(),
            'now' => $this->getNowEvent(),
        ];
        return view('livewire.dashboard', $data);
    }

    public function countEmployee()
    {
        $service = $this->dashboardService ?? app(DashboardServiceInterface::class);
        return $service->countEmployee();
    }
    public function countTraining()
    {
        $service = $this->dashboardService ?? app(DashboardServiceInterface::class);
        return $service->countTraining();
    }
    public function countCompleted()
    {
        $service = $this->dashboardService ?? app(DashboardServiceInterface::class);
        return $service->countParticipantComplete();
    }
    public function countNotCompleted()
    {
        $service = $this->dashboardService ?? app(DashboardServiceInterface::class);
        return $service->countParticipantNotCompleted();
    }

    public function mapping()
    {
        return [
            [
                'title'     => 'Total Training',
                'value'     => $this->countTraining(),
                'icon'      => 'fa-chalkboard-user',
                'gradient'  => 'linear-gradient(130deg,#FF003D,#FF6B81)',
            ],
            [
                'title'     => 'Total Employee',
                'value'     => $this->countEmployee(),
                'icon'      => 'fa-users',
                'gradient'  => 'linear-gradient(130deg,#00F5A0,#00D9F5)',
            ],
            [
                'title'     => 'Participant Completed',
                'value'     => $this->countCompleted(),
                'icon'      => 'fa-circle-check',
                'gradient'  => 'linear-gradient(130deg,#8E2DE2,#4A00E0)',
            ],
            [
                'title'     => 'Training Aktif',
                'value'     => $this->countNotCompleted(),
                'icon'      => 'fa-calendar-check',
                'gradient'  => 'linear-gradient(130deg,#FF9A00,#FFD56A)',
            ],
        ];
    }

    public function getUpcomingEvent()
    {
        $service = $this->eventService ?? app(EventServiceInterface::class);
        return $service->getLatestUpcomingEvent();
    }
    public function getNowEvent()
    {
        $service = $this->eventService ?? app(EventServiceInterface::class);
        return $service->getLatestNowEvent();
    }
}
