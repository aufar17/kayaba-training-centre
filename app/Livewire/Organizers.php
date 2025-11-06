<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\OrganizerServiceInterface;
use Livewire\Component;

class Organizers extends Component
{
    public $code;
    public $name;
    public $organizerId;

    protected OrganizerServiceInterface $service;

    public function mount()
    {
        $this->service = app(OrganizerServiceInterface::class);
    }

    public function render()
    {
        $data = [
            'organizers' => $this->getorganizer(),
        ];
        return view('livewire.organizers', $data);
    }

    public function getOrganizer()
    {
        $this->service = app(OrganizerServiceInterface::class);
        return $this->service->getAll();
    }

    public function create()
    {
        $this->service = app(OrganizerServiceInterface::class);
        $data = [
            'code' => $this->code,
            'name' => $this->name,
        ];


        $create = $this->service->create($data);

        $create
            ? session()->flash('success', 'New organizer added successfully.')
            : session()->flash('error', 'Failed to add new organizer. Please try again.');
        return redirect()->route('organizer');
    }

    public function edit($id)
    {
        $this->service = app(OrganizerServiceInterface::class);
        $organizer = $this->service->getById($id);
        $this->organizerId = $organizer->id;
        $this->code = $organizer->code;
        $this->name = $organizer->name;
    }

    public function update()
    {
        $this->service = app(OrganizerServiceInterface::class);
        $data = [
            'code' => $this->code,
            'name' => $this->name,
        ];

        $update = $this->service->update($this->organizerId, $data);

        $update
            ? session()->flash('success', 'organizer updated successfully!')
            : session()->flash('error', 'Failed to update organizer. Please try again.');

        return redirect()->route('organizer');
    }

    public function confirmDelete($id)
    {
        $this->organizerId = $id;
    }

    public function delete()
    {
        $this->service = app(OrganizerServiceInterface::class);
        $delete = $this->service->delete($this->organizerId);

        $delete
            ? session()->flash('success', 'organizer deleted successfully!')
            : session()->flash('error', 'Failed to delete organizer. Please try again.');

        return redirect()->route('organizer');
    }
}
