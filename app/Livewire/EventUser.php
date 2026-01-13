<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\EventServiceInterface;
use Livewire\Component;
use Livewire\WithPagination;

class EventUser extends Component
{
    use WithPagination;
    public $eventId;
    public $user;

    protected $paginationTheme = 'bootstrap';
    protected EventServiceInterface $service;
    public function mount()
    {
        $this->service = app(EventServiceInterface::class);
    }
    public function render()
    {
        $data = [
            'nowEvent' => $this->getNowEvent(),
            'upcomingEvent' => $this->getUpcomingEvent(),
            'pastEvent' => $this->getPastEvent(),
            'role' => $this->checkRole(),
        ];
        return view('livewire.event-user', $data);
    }

    public function checkRole()
    {
        $role = $this->user->getUserRole();
        return $role;
    }

    public function getNowEvent()
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $dept = $this->user->dept;
        $now = $service->getNowEvent($dept);
        return $now;
    }

    public function getUpcomingEvent()
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $dept = $this->user->dept;
        $upcoming = $service->getUpcomingEvent($dept);
        return $upcoming;
    }

    public function getPastEvent()
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $dept = $this->user->dept;
        $past = $service->getPastEvent($dept);
        return $past;
    }
}
