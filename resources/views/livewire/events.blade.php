<div>
    <x-card :icon="'fa-table'">
        @slot('title')
        Event Data
        @endslot

        @slot('body')
        <button class="btn btn-success border-0 mb-5" data-bs-toggle="modal" data-bs-target="#newEventModal">
            <i class="fa-solid fa-plus me-1"></i> New
        </button>

        <div class="table-responsive">
            <table id="event" class="table table-light table-hover table-bordered table-striped shadow-sm">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Training</th>
                        <th class="text-center">Organizer</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Time</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $event->code }}</td>
                        <td class="text-center">{{ $event->trainings->name }}</td>
                        <td class="text-center">{{ $event->organizers->name }}</td>
                        <td class="text-center">{{ $event->locations->name }}</td>
                        <td class="text-center">{{ $event->start_date }} - {{ $event->end_date }}</td>
                        <td class="text-center">{{ $event->start_time }} - {{ $event->end_time }}</td>
                        <td class="text-center">
                            <button class="badge bg-gradient-info border-0 shadow-xl" data-bs-toggle="modal"
                                data-bs-target="#participantEventModal">
                                <i class="fa-solid fa-user-group"></i>
                            </button>

                            <button wire:click="edit({{ $event->id }})" class="badge bg-warning border-0 shadow-xl"
                                data-bs-toggle="modal" data-bs-target="#editEventModal">
                                <i class="fa-solid fa-edit"></i>
                            </button>

                            <button wire:click="confirmDelete({{ $event->id }})"
                                class="badge bg-danger border-0 shadow-xl" data-bs-toggle="modal"
                                data-bs-target="#deleteEventModal">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>

        <div wire:ignore.self class="modal fade" id="newEventModal" tabindex="-1" aria-labelledby="newEventModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white" id="newEventModalLabel">Add New Event</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="create">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="code" class="form-label text-black text-uppercase">Event
                                            Code</label>
                                        <input type="text" class="form-control shadow-sm" id="code"
                                            wire:model.live="code" placeholder="Event code"
                                            oninput="this.value = this.value.toUpperCase()" required>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="training"
                                            class="form-label fw-semibold text-uppercase">Training</label>
                                        <select wire:model.live="training" id="training" class="form-select"
                                            wire:change="checkTraining">
                                            <option value="">-- Select Training --</option>
                                            @foreach($trainings as $training)
                                            <option value="{{ $training->id }}">{{ $training->name }}</option>
                                            @endforeach
                                            <option value="other">Other...</option>
                                        </select>

                                        @if($showTrainingInput)
                                        <div class="mt-2">
                                            <input type="text" wire:model.live="new_training_code"
                                                class="form-control mb-2" placeholder="Enter new training code"
                                                oninput="this.value = this.value.toUpperCase()">
                                            <input type="text" wire:model.live="new_training_name" class="form-control"
                                                placeholder="Enter new training name">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="location"
                                            class="form-label fw-semibold text-uppercase">Location</label>
                                        <select wire:model.live="location" id="location" class="form-select"
                                            wire:change="checkLocation">
                                            <option value="">-- Select Location --</option>
                                            @foreach($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                            <option value="other">Other...</option>
                                        </select>

                                        @if($showLocationInput)
                                        <div class="mt-2">
                                            <input type="text" wire:model.live="new_location_code"
                                                class="form-control mb-2" placeholder="Enter new location code"
                                                oninput="this.value = this.value.toUpperCase()">
                                            <input type="text" wire:model.live="new_location_name" class="form-control"
                                                placeholder="Enter new location name">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="organizer"
                                            class="form-label fw-semibold text-uppercase">Organizer</label>
                                        <select wire:model.live="organizer" id="organizer" class="form-select"
                                            wire:change="checkOrganizer">
                                            <option value="">-- Select Organizer --</option>
                                            @foreach($organizers as $organizer)
                                            <option value="{{ $organizer->id }}">{{ $organizer->name }}</option>
                                            @endforeach
                                            <option value="other">Other...</option>
                                        </select>

                                        @if($showOrganizerInput)
                                        <div class="mt-2">
                                            <input type="text" wire:model.live="new_organizer_code"
                                                class="form-control mb-2" placeholder="Enter new organizer code"
                                                oninput="this.value = this.value.toUpperCase()">
                                            <input type="text" wire:model.live="new_organizer_name" class="form-control"
                                                placeholder="Enter new organizer name">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="trainer"
                                            class="form-label fw-semibold text-uppercase">Trainer</label>
                                        <select wire:model.live="trainer" id="trainer" class="form-select"
                                            wire:change="checkTrainer">
                                            <option value="">-- Select Trainer --</option>
                                            @foreach($trainers as $trainer)
                                            <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                                            @endforeach
                                            <option value="other">Other...</option>
                                        </select>

                                        @if($showTrainerInput)
                                        <div class="mt-2">
                                            <input type="text" wire:model.live="new_trainer_code"
                                                class="form-control mb-2" placeholder="Enter new trainer code"
                                                oninput="this.value = this.value.toUpperCase()">
                                            <input type="text" wire:model.live="new_trainer_name" class="form-control"
                                                placeholder="Enter new trainer name">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label text-black text-uppercase">Start
                                            Date</label>
                                        <input type="date" class="form-control shadow-sm" id="start_date"
                                            wire:model.live="start_date" placeholder="Start Date"
                                            oninput="this.value = this.value.toUpperCase()" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label text-black text-uppercase">End
                                            Date</label>
                                        <input type="date" class="form-control shadow-sm" id="end_date"
                                            wire:model.live="end_date" placeholder="End Date"
                                            oninput="this.value = this.value.toUpperCase()" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label text-black text-uppercase">Start
                                            Time</label>
                                        <input type="time" class="form-control shadow-sm" id="start_time"
                                            wire:model.live="start_time" placeholder="Start Time"
                                            oninput="this.value = this.value.toUpperCase()" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="end_time" class="form-label text-black text-uppercase">End
                                            Time</label>
                                        <input type="time" class="form-control shadow-sm" id="end_time"
                                            wire:model.live="end_time" placeholder="End Time"
                                            oninput="this.value = this.value.toUpperCase()" required>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4 border-0 shadow-lg">
                                <div class="card-header bg-light border-bottom border border-success">
                                    <h6 class="mb-0 fw-bold text-black text-uppercase">
                                        <i class="fa-solid fa-list-check me-2"></i>Event Preview
                                    </h6>
                                </div>

                                <div class="card-body bg-white">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-success rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Event Code
                                                </div>
                                                <div class="fw-bold text-dark">{{ $code ?: '-' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-success rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Location
                                                </div>
                                                <div class="fw-bold text-dark">
                                                    {{ $locationNamePreview }} </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-success rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Organizer
                                                </div>
                                                <div class="fw-bold text-dark">
                                                    {{ $organizerNamePreview }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-success rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Trainer
                                                </div>
                                                <div class="fw-bold text-dark">
                                                    {{ $trainerNamePreview }} </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-success rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Training
                                                </div>
                                                <div class="fw-bold text-dark">
                                                    {{ $trainingNamePreview }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-success rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Dates
                                                </div>
                                                <div class="fw-bold text-dark">
                                                    {{ $start_date && $end_date ? "$start_date → $end_date" : '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-success rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Time
                                                    Duration
                                                </div>
                                                <div class="fw-bold text-dark">
                                                    {{ $start_time && $end_time ? "$start_time → $end_time" : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title text-white" id="editEventModalLabel">Edit Event</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="update">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="code" class="form-label text-black text-uppercase">Event
                                            Code</label>
                                        <input type="text" class="form-control shadow-sm" id="code"
                                            wire:model.live="code" placeholder="Event code"
                                            oninput="this.value = this.value.toUpperCase()" required>
                                    </div>
                                </div>

                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="training"
                                            class="form-label fw-semibold text-uppercase">Training</label>
                                        <select wire:model.live="training" id="training" class="form-select"
                                            wire:change="checkTraining">
                                            <option value="">-- Select Training --</option>
                                            @foreach($trainings as $training)
                                            <option value="{{ $training->id }}">{{ $training->name }}</option>
                                            @endforeach
                                            <option value="other">Other...</option>
                                        </select>

                                        @if($showTrainingInput)
                                        <div class="mt-2">
                                            <input type="text" wire:model.live="new_training_code"
                                                class="form-control mb-2" placeholder="Enter new training code"
                                                oninput="this.value = this.value.toUpperCase()">
                                            <input type="text" wire:model.live="new_training_name" class="form-control"
                                                placeholder="Enter new training name">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="location"
                                            class="form-label fw-semibold text-uppercase">Location</label>
                                        <select wire:model.live="location" id="location" class="form-select"
                                            wire:change="checkLocation">
                                            <option value="">-- Select Location --</option>
                                            @foreach($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                            <option value="other">Other...</option>
                                        </select>

                                        @if($showLocationInput)
                                        <div class="mt-2">
                                            <input type="text" wire:model.live="new_location_code"
                                                class="form-control mb-2" placeholder="Enter new location code"
                                                oninput="this.value = this.value.toUpperCase()">
                                            <input type="text" wire:model.live="new_location_name" class="form-control"
                                                placeholder="Enter new location name">
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="organizer"
                                            class="form-label fw-semibold text-uppercase">Organizer</label>
                                        <select wire:model.live="organizer" id="organizer" class="form-select"
                                            wire:change="checkOrganizer">
                                            <option value="">-- Select Organizer --</option>
                                            @foreach($organizers as $organizer)
                                            <option value="{{ $organizer->id }}">{{ $organizer->name }}</option>
                                            @endforeach
                                            <option value="other">Other...</option>
                                        </select>

                                        @if($showOrganizerInput)
                                        <div class="mt-2">
                                            <input type="text" wire:model.live="new_organizer_code"
                                                class="form-control mb-2" placeholder="Enter new organizer code"
                                                oninput="this.value = this.value.toUpperCase()">
                                            <input type="text" wire:model.live="new_organizer_name" class="form-control"
                                                placeholder="Enter new organizer name">
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="trainer"
                                            class="form-label fw-semibold text-uppercase">Trainer</label>
                                        <select wire:model.live="trainer" id="trainer" class="form-select"
                                            wire:change="checkTrainer">
                                            <option value="">-- Select Trainer --</option>
                                            @foreach($trainers as $trainer)
                                            <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                                            @endforeach
                                            <option value="other">Other...</option>
                                        </select>

                                        @if($showTrainerInput)
                                        <div class="mt-2">
                                            <input type="text" wire:model.live="new_trainer_code"
                                                class="form-control mb-2" placeholder="Enter new trainer code"
                                                oninput="this.value = this.value.toUpperCase()">
                                            <input type="text" wire:model.live="new_trainer_name" class="form-control"
                                                placeholder="Enter new trainer name">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label text-black text-uppercase">Start
                                            Date</label>
                                        <input type="date" class="form-control shadow-sm" id="start_date"
                                            wire:model.live="start_date" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label text-black text-uppercase">End
                                            Date</label>
                                        <input type="date" class="form-control shadow-sm" id="end_date"
                                            wire:model.live="end_date" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label text-black text-uppercase">Start
                                            Time</label>
                                        <input type="time" class="form-control shadow-sm" id="start_time"
                                            wire:model.live="start_time" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="end_time" class="form-label text-black text-uppercase">End
                                            Time</label>
                                        <input type="time" class="form-control shadow-sm" id="end_time"
                                            wire:model.live="end_time" required>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4 border-0 shadow-lg">
                                <div class="card-header bg-light border-bottom border border-warning">
                                    <h6 class="mb-0 fw-bold text-black text-uppercase">
                                        <i class="fa-solid fa-list-check me-2"></i>Event Preview
                                    </h6>
                                </div>

                                <div class="card-body bg-white">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-warning rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Event Code
                                                </div>
                                                <div class="fw-bold text-dark">{{ $code ?: '-' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-warning rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Location
                                                </div>
                                                <div class="fw-bold text-dark">
                                                    {{ $locationNamePreview }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-warning rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Organizer
                                                </div>
                                                <div class="fw-bold text-dark">
                                                    {{ $organizerNamePreview }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-warning rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Trainer
                                                </div>
                                                <div class="fw-bold text-dark">
                                                    {{ $trainerNamePreview }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-warning rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Training
                                                </div>
                                                <div class="fw-bold text-dark">
                                                    {{ $trainingNamePreview }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-warning rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Dates</div>
                                                <div class="fw-bold text-dark">
                                                    {{ $start_date && $end_date ? "$start_date → $end_date" : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div
                                                class="p-3 bg-white border-start border-4 border-warning rounded shadow-sm h-100">
                                                <div class="fw-semibold text-secondary text-uppercase small">Time
                                                    Duration</div>
                                                <div class="fw-bold text-dark">
                                                    {{ $start_time && $end_time ? "$start_time → $end_time" : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning text-white">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="deleteEventModal" tabindex="-1"
            aria-labelledby="deleteEventModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-md">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title text-white" id="deleteEventModalLabel">Delete Confirmation</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Event?</p>
                    </div>
                    <div class="modal-footer border-1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>
        @endslot
    </x-card>
</div>