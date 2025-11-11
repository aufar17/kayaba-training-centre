<?php

namespace App\Livewire;

use App\Models\Auth\CTUser;
use App\Models\Event;
use App\Models\Training;
use Livewire\Component;

class HistoryTraining extends Component
{
    public $searchEmployee = '';
    public $searchTraining = '';

    public $employees = [];
    public $events = [];
    public function render()
    {
        return view('livewire.history-training');
    }

    public function updatedSearchEmployee($value)
    {
        $this->performSearch();
    }

    public function updatedSearchTraining($value)
    {
        $this->performSearch();
    }

    protected function performSearch()
    {
        $this->employees = CTUser::query()
            ->when($this->searchEmployee, fn($q) => $q->where('npk', 'like', "%{$this->searchEmployee}%")
                ->orWhere('full_name', 'like', "%{$this->searchEmployee}%"))
            ->get();

        $this->events = Event::with('trainings')
            ->when($this->searchTraining, function ($q) {
                $search = $this->searchTraining;
                $q->whereHas('trainings', function ($trainingQuery) use ($search) {
                    $trainingQuery->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->get();
    }
}
