<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\TrainingServiceInterface;
use App\Models\Departement;
use App\Models\Department;
use Livewire\Component;

class Trainings extends Component
{
    public $code;
    public $name;
    public $desc;
    public $purpose;
    public $golongan;
    public $departement;
    public $day_duration;
    public $time_duration;

    public $trainingId;
    public $departmentInputs = [];
    public $departmentSearchResults = [];
    public $selectedDepartments = [];

    protected TrainingServiceInterface $service;

    public function mount()
    {
        $this->service = app(TrainingServiceInterface::class);
        $this->departmentInputs = [''];
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

    public function addDepartmentInput()
    {
        $this->departmentInputs[] = '';
    }

    public function removeDepartmentInput($index)
    {
        unset($this->departmentInputs[$index]);
        $this->departmentInputs = array_values($this->departmentInputs);
    }

    public function updatedDepartmentInputs($value, $key)
    {
        if ($value) {
            $this->departmentSearchResults[$key] = Department::query()
                ->where('code', 'like', "%{$value}%")
                ->orWhere('name', 'like', "%{$value}%")
                ->take(5)
                ->get();
        } else {
            $this->departmentSearchResults[$key] = [];
        }
    }

    public function selectDepartment($index, $code, $name)
    {
        $this->departmentInputs[$index] = "$code - $name";
        $this->selectedDepartments[$index] = $code;
        $this->departmentSearchResults[$index] = [];
    }


    public function create()
    {

        $this->service = app(TrainingServiceInterface::class);

        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'desc' => $this->desc,
            'purpose' => $this->purpose,
            'golongan' => $this->golongan,
            'day_duration' => $this->day_duration,
            'time_duration' => $this->time_duration,
            'departments' => $this->selectedDepartments,
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
        $this->golongan = $training->golongan;
        $this->day_duration = $training->day_duration;
        $this->time_duration = $training->time_duration;

        $departments = $training->matrix->pluck('dept')->toArray();
        $this->selectedDepartments = $departments;

        $this->departmentInputs = [];
        foreach ($departments as $code) {
            $dept = Department::where('code', $code)->first();
            $this->departmentInputs[] = $dept ? "$dept->code - $dept->name" : $code;
        }

        if (empty($this->departmentInputs)) {
            $this->departmentInputs = [''];
        }
    }



    public function update()
    {
        $this->service = app(TrainingServiceInterface::class);
        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'desc' => $this->desc,
            'purpose' => $this->purpose,
            'golongan' => $this->golongan,
            'day_duration' => $this->day_duration,
            'time_duration' => $this->time_duration,
            'departments' => $this->selectedDepartments,
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
