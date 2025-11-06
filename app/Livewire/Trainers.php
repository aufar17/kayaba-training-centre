<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\TrainerServiceInterface;
use Livewire\Component;

class Trainers extends Component
{
    public $code;
    public $name;
    public $trainerId;

    protected TrainerServiceInterface $service;

    public function mount()
    {
        $this->service = app(TrainerServiceInterface::class);
    }

    public function render()
    {
        $data = [
            'trainers' => $this->getTrainer(),
        ];
        return view('livewire.trainers', $data);
    }

    public function getTrainer()
    {
        $this->service = app(TrainerServiceInterface::class);
        return $this->service->getAll();
    }

    public function create()
    {
        $this->service = app(TrainerServiceInterface::class);
        $data = [
            'code' => $this->code,
            'name' => $this->name,
        ];


        $create = $this->service->create($data);

        $create
            ? session()->flash('success', 'New trainer added successfully.')
            : session()->flash('error', 'Failed to add new trainer. Please try again.');
        return redirect()->route('trainer');
    }

    public function edit($id)
    {
        $this->service = app(TrainerServiceInterface::class);
        $trainer = $this->service->getById($id);
        $this->trainerId = $trainer->id;
        $this->code = $trainer->code;
        $this->name = $trainer->name;
    }

    public function update()
    {
        $this->service = app(TrainerServiceInterface::class);
        $data = [
            'code' => $this->code,
            'name' => $this->name,
        ];

        $update = $this->service->update($this->trainerId, $data);

        $update
            ? session()->flash('success', 'trainer updated successfully!')
            : session()->flash('error', 'Failed to update trainer. Please try again.');

        return redirect()->route('trainer');
    }

    public function confirmDelete($id)
    {
        $this->trainerId = $id;
    }

    public function delete()
    {
        $this->service = app(TrainerServiceInterface::class);
        $delete = $this->service->delete($this->trainerId);

        $delete
            ? session()->flash('success', 'trainer deleted successfully!')
            : session()->flash('error', 'Failed to delete trainer. Please try again.');

        return redirect()->route('trainer');
    }
}
