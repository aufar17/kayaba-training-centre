<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\EventServiceInterface;
use Livewire\Component;

class ParticipantHistory extends Component
{
    public $npk;
    protected EventServiceInterface $eventService;

    public function mount()
    {
        $this->eventService = app(EventServiceInterface::class);
    }
    public function render()
    {
        $data = [
            'histories' => $this->getHistory()
        ];
        return view('livewire.participant-history', $data);
    }

    public function getHistory()
    {
        $service = $this->eventService ?? app(EventServiceInterface::class);
        $history = $service->historyParticipant($this->npk);
        return $history;
    }

    public function completedLabel($completed)
    {
        return match ($completed) {
            -1 => ['text' => 'Not Completed', 'class' => 'bg-gradient-danger'],
            0   => ['text' => 'Not Complete', 'class' => 'bg-gradient-danger'],
            1 => ['text' => 'Completed', 'class' => 'bg-gradient-success'],
            default => ['text' => 'Waiting Report', 'class' => 'bg-gradient-secondary'],
        };
    }
}
