<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\DashboardServiceInterface;
use Livewire\Component;

class Kpi extends Component
{
    protected DashboardServiceInterface $service;

    public function mount()
    {
        $this->service = app(DashboardServiceInterface::class);
    }
    public function render()
    {
        $data = [
            'mappings' => $this->mapping(),
        ];
        return view('livewire.kpi', $data);
    }

    public function countEmployee()
    {
        $service = $this->service ?? app(DashboardServiceInterface::class);
        return $service->countEmployee();
    }
    public function countTraining()
    {
        $service = $this->service ?? app(DashboardServiceInterface::class);
        return $service->countTraining();
    }
    public function countCompleted()
    {
        $service = $this->service ?? app(DashboardServiceInterface::class);
        return $service->countParticipantComplete();
    }
    public function countNotCompleted()
    {
        $service = $this->service ?? app(DashboardServiceInterface::class);
        return $service->countParticipantNotCompleted();
    }

    public function mapping()
    {
        $mapping = [
            [
                'title' => 'Total Employee',
                'value' => $this->countEmployee(),
                'icon'  => 'fa-users',
                'color' => 'text-primary',
            ],
            [
                'title' => 'Total Training',
                'value' => $this->countTraining(),
                'icon'  => 'fa-calendar-days',
                'color' => 'text-info',
            ],
            [
                'title' => 'Participants Completed',
                'value' => $this->countCompleted(),
                'icon'  => 'fa-user-check',
                'color' => 'text-success',
            ],
            [
                'title' => 'Participants Not Completed',
                'value' => $this->countNotCompleted(),
                'icon'  => 'fa-user-xmark',
                'color' => 'text-danger',
            ],
        ];

        return $mapping;
    }
}
