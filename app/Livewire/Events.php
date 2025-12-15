<?php

namespace App\Livewire;

use App\Imports\EventImport;
use App\Interfaces\ServiceInterface\EventImportServiceInterface;
use App\Interfaces\ServiceInterface\EventServiceInterface;
use App\Interfaces\ServiceInterface\LocationServiceInterface;
use App\Interfaces\ServiceInterface\OrganizerServiceInterface;
use App\Interfaces\ServiceInterface\TrainerServiceInterface;
use App\Interfaces\ServiceInterface\TrainingServiceInterface;
use Livewire\Component;
use Livewire\WithFileUploads;

class Events extends Component
{
    use WithFileUploads;

    public $file;
    public $code, $training, $location, $organizer, $trainer;
    public $start_date, $end_date, $start_time, $end_time;
    public $eventId;
    public $isEditing = false;

    public $new_training_name, $new_training_code;
    public $new_organizer_name, $new_organizer_code;
    public $new_trainer_name, $new_trainer_code;
    public $new_location_name, $new_location_code;

    public $trainingNamePreview = '-';
    public $locationNamePreview = '-';
    public $organizerNamePreview = '-';
    public $trainerNamePreview = '-';


    public $showTrainingInput = false, $showLocationInput = false, $showOrganizerInput = false, $showTrainerInput = false;

    public $trainings = [];
    public $organizers = [];
    public $trainers = [];
    public $locations = [];

    protected EventServiceInterface $eventService;
    protected TrainingServiceInterface $trainingService;
    protected OrganizerServiceInterface $organizerService;
    protected TrainerServiceInterface $trainerService;
    protected LocationServiceInterface $locationService;
    protected EventImportServiceInterface $importService;

    public function mount()
    {
        $this->eventService = app(EventServiceInterface::class);
        $this->trainingService = app(TrainingServiceInterface::class);
        $this->organizerService = app(OrganizerServiceInterface::class);
        $this->trainerService = app(TrainerServiceInterface::class);
        $this->locationService = app(LocationServiceInterface::class);
        $this->importService = app(EventImportServiceInterface::class);

        $this->trainings = $this->trainingService->getAll();
        $this->organizers = $this->organizerService->getAll();
        $this->trainers = $this->trainerService->getAll();
        $this->locations = $this->locationService->getAll();
    }

    public function render()
    {
        $data = [
            'events' => $this->getEvents(),
        ];
        return view('livewire.events', $data);
    }

    public function getEvents()
    {
        $this->eventService = app(EventServiceInterface::class);
        return $this->eventService->getAll();
    }

    public function resetForm()
    {
        $this->reset([
            'code',
            'training',
            'location',
            'organizer',
            'trainer',
            'start_date',
            'end_date',
            'start_time',
            'end_time',
            'eventId',
            'isEditing'
        ]);
    }

    public function checkTraining()
    {
        $this->showTrainingInput = $this->training === 'other';
        $this->trainingNamePreview = $this->training === 'other'
            ? ($this->new_training_name ?: '-')
            : (optional(collect($this->trainings)->firstWhere('code', $this->training))->name ?? '-');
    }

    public function checkLocation()
    {
        $this->showLocationInput = $this->location === 'other';
        $this->locationNamePreview = $this->location === 'other'
            ? ($this->new_location_name ?: '-')
            : (optional(collect($this->locations)->firstWhere('code', $this->location))->name ?? '-');
    }

    public function checkOrganizer()
    {
        $this->showOrganizerInput = $this->organizer === 'other';
        $this->organizerNamePreview = $this->organizer === 'other'
            ? ($this->new_organizer_name ?: '-')
            : (optional(collect($this->organizers)->firstWhere('code', $this->organizer))->name ?? '-');
    }

    public function checkTrainer()
    {
        $this->showTrainerInput = $this->trainer === 'other';
        $this->trainerNamePreview = $this->trainer === 'other'
            ? ($this->new_trainer_name ?: '-')
            : (optional(collect($this->trainers)->firstWhere('code', $this->trainer))->name ?? '-');
    }

    public function updatedNewTrainingName($value)
    {
        if ($this->training === 'other') {
            $this->trainingNamePreview = $value ?: '-';
        }
    }

    public function updatedNewLocationName($value)
    {
        if ($this->location === 'other') {
            $this->locationNamePreview = $value ?: '-';
        }
    }

    public function updatedNewOrganizerName($value)
    {
        if ($this->organizer === 'other') {
            $this->organizerNamePreview = $value ?: '-';
        }
    }

    public function updatedNewTrainerName($value)
    {
        if ($this->trainer === 'other') {
            $this->trainerNamePreview = $value ?: '-';
        }
    }

    public function create()
    {
        $this->eventService = app(EventServiceInterface::class);

        $data = [
            'code' => $this->code,
            'training' => $this->training,
            'location' => $this->location,
            'organizer' => $this->organizer,
            'trainer' => $this->trainer,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'new_training' => [
                'code' => $this->new_training_code,
                'name' => $this->new_training_name,
            ],
            'new_organizer' => [
                'code' => $this->new_organizer_code,
                'name' => $this->new_organizer_name,
            ],
            'new_trainer' => [
                'code' => $this->new_trainer_code,
                'name' => $this->new_trainer_name,
            ],
            'new_location' => [
                'code' => $this->new_location_code,
                'name' => $this->new_location_name,
            ],
        ];

        $create = $this->eventService->create($data);

        if ($create) {
            session()->flash('success', 'New event added successfully.');
            $this->resetForm();
        } else {
            session()->flash('error', 'Failed to add new event.');
        }

        return redirect()->route('event');
    }

    public function edit($id)
    {
        $this->eventService = app(EventServiceInterface::class);

        $this->isEditing = true;
        $this->eventId = $id;

        $event = $this->eventService->getById($id);

        if ($event) {
            $this->code = $event->code;
            $this->training = $event->training_id;
            $this->location = $event->location_id;
            $this->organizer = $event->organizer_id;
            $this->trainer = $event->trainer_id;
            $this->start_date = $event->start_date;
            $this->end_date = $event->end_date;
            $this->start_time = $event->start_time;
            $this->end_time = $event->end_time;

            $this->checkTraining();
            $this->checkLocation();
            $this->checkOrganizer();
            $this->checkTrainer();
        } else {
            session()->flash('error', 'Event not found.');
        }
    }

    public function update()
    {
        $this->eventService = app(EventServiceInterface::class);

        if (!$this->eventId) {
            session()->flash('error', 'No event selected for update.');
            return;
        }

        $data = [
            'code' => $this->code,
            'training' => $this->training,
            'location' => $this->location,
            'organizer' => $this->organizer,
            'trainer' => $this->trainer,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'new_training' => [
                'code' => $this->new_training_code,
                'name' => $this->new_training_name,
            ],
            'new_organizer' => [
                'code' => $this->new_organizer_code,
                'name' => $this->new_organizer_name,
            ],
            'new_trainer' => [
                'code' => $this->new_trainer_code,
                'name' => $this->new_trainer_name,
            ],
            'new_location' => [
                'code' => $this->new_location_code,
                'name' => $this->new_location_name,
            ],
        ];

        $update = $this->eventService->update($this->eventId, $data);

        if ($update) {
            session()->flash('success', 'Event updated successfully.');
            $this->resetForm();
        } else {
            session()->flash('error', 'Failed to update event.');
        }

        return redirect()->route('event');
    }


    public function confirmDelete($id)
    {
        $this->eventId = $id;
    }

    public function delete()
    {
        $this->eventService = app(EventServiceInterface::class);

        if (!$this->eventId) {
            session()->flash('error', 'No event selected for deletion.');
            return;
        }

        $delete = $this->eventService->delete($this->eventId);

        if ($delete) {
            session()->flash('success', 'Event deleted successfully.');
        } else {
            session()->flash('error', 'Failed to delete event.');
        }

        $this->reset('eventId');

        return redirect()->route('event');
    }
}
