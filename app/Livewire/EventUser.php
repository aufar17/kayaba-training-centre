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
        $now = $service->getNowEvent();
        return $now;
    }

    public function getUpcomingEvent()
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $upcoming = $service->getUpcomingEvent();
        return $upcoming;
    }

    public function getPastEvent()
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $past = $service->getPastEvent();
        return $past;
    }
}
