<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\EventServiceInterface;
use Livewire\Component;

class EventUser extends Component
{
    public $eventId;
    public $user;
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
        ];
        return view('livewire.event-user', $data);
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
