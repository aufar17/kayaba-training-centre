<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\TrainingServiceInterface;
use Livewire\Component;

class Trainings extends Component
{
    public $code;
    public $name;
    public $desc;
    public $purpose;
    public $day_duration;
    public $time_duration;
    public $trainingId;

    protected TrainingServiceInterface $service;

    public function mount()
    {
        $this->service = app(TrainingServiceInterface::class);
    }

    public function render()
    {
        $data = [
            'trainings' => $this->getTraining(),
        ];
        return view('livewire.trainings', $data);
    }

    public function getTraining()
    {
        $this->service = app(TrainingServiceInterface::class);
        return $this->service->getAll();
    }

    public function create()
    {
        $this->service = app(TrainingServiceInterface::class);
        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'desc' => $this->desc,
            'purpose' => $this->purpose,
            'day_duration' => $this->day_duration,
            'time_duration' => $this->time_duration,
        ];


        $create = $this->service->create($data);

        $create
            ? session()->flash('success', 'New training added successfully.')
            : session()->flash('error', 'Failed to add new training. Please try again.');
        return redirect()->route('training');
    }

    public function edit($id)
    {
        $this->service = app(TrainingServiceInterface::class);
        $training = $this->service->getById($id);
        $this->trainingId = $training->id;
        $this->code = $training->code;
        $this->name = $training->name;
        $this->desc = $training->desc;
        $this->purpose = $training->purpose;
        $this->day_duration = $training->day_duration;
        $this->time_duration = $training->time_duration;
    }

    public function update()
    {
        $this->service = app(TrainingServiceInterface::class);
        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'desc' => $this->desc,
            'purpose' => $this->purpose,
            'day_duration' => $this->day_duration,
            'time_duration' => $this->time_duration,
        ];

        $update = $this->service->update($this->trainingId, $data);

        $update
            ? session()->flash('success', 'Training updated successfully!')
            : session()->flash('error', 'Failed to update training. Please try again.');

        return redirect()->route('training');
    }

    public function confirmDelete($id)
    {
        $this->trainingId = $id;
    }

    public function delete()
    {
        $this->service = app(TrainingServiceInterface::class);
        $delete = $this->service->delete($this->trainingId);

        $delete
            ? session()->flash('success', 'Training deleted successfully!')
            : session()->flash('error', 'Failed to delete training. Please try again.');

        return redirect()->route('training');
    }
}
