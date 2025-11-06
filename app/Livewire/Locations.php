<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\LocationServiceInterface;
use Livewire\Component;

class Locations extends Component
{
    public $code;
    public $name;
    public $locationId;

    protected LocationServiceInterface $service;

    public function mount()
    {
        $this->service = app(LocationServiceInterface::class);
    }

    public function render()
    {
        $data = [
            'locations' => $this->getlocation(),
        ];
        return view('livewire.locations', $data);
    }

    public function getLocation()
    {
        $this->service = app(LocationServiceInterface::class);
        return $this->service->getAll();
    }

    public function create()
    {
        $this->service = app(LocationServiceInterface::class);
        $data = [
            'code' => $this->code,
            'name' => $this->name,
        ];


        $create = $this->service->create($data);

        $create
            ? session()->flash('success', 'New location added successfully.')
            : session()->flash('error', 'Failed to add new location. Please try again.');
        return redirect()->route('location');
    }

    public function edit($id)
    {
        $this->service = app(LocationServiceInterface::class);
        $location = $this->service->getById($id);
        $this->locationId = $location->id;
        $this->code = $location->code;
        $this->name = $location->name;
    }

    public function update()
    {
        $this->service = app(LocationServiceInterface::class);
        $data = [
            'code' => $this->code,
            'name' => $this->name,
        ];

        $update = $this->service->update($this->locationId, $data);

        $update
            ? session()->flash('success', 'Location updated successfully!')
            : session()->flash('error', 'Failed to update location. Please try again.');

        return redirect()->route('location');
    }

    public function confirmDelete($id)
    {
        $this->locationId = $id;
    }

    public function delete()
    {
        $this->service = app(LocationServiceInterface::class);
        $delete = $this->service->delete($this->locationId);

        $delete
            ? session()->flash('success', 'Location deleted successfully!')
            : session()->flash('error', 'Failed to delete location. Please try again.');

        return redirect()->route('location');
    }
}
