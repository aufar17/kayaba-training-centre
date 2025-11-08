<div>
    <x-card :icon="'fa-table'">
        @slot('title')
        Training Data
        @endslot

        @slot('body')
        <button class="btn btn-success border-0 mb-5" data-bs-toggle="modal" data-bs-target="#newTrainingModal">
            <i class="fa-solid fa-plus me-1"></i> New
        </button>

        <div class="table-responsive">
            <table id="training" class="table table-light table-hover table-bordered table-striped shadow-sm">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Training</th>
                        <th class="text-center">Duration</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trainings as $training)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $training->code }}</td>
                        <td class="text-center">{{ $training->name }}</td>
                        <td class="text-center">{{ $training->day_duration ?? '-' }} Days / {{ $training->time_duration
                            ?? '-' }}
                            Hours
                        </td>
                        <td class="text-center">
                            <a href="{{ route('training-content',$training->id) }}"
                                class="badge bg-gradient-info border-0 shadow-xl">
                                <i class="fa-solid fa-file-pdf"></i>
                            </a>

                            <button wire:click="edit({{ $training->id }})" class="badge bg-warning border-0 shadow-xl"
                                data-bs-toggle="modal" data-bs-target="#editTrainingModal">
                                <i class="fa-solid fa-edit"></i>
                            </button>

                            <button wire:click="confirmDelete({{ $training->id }})"
                                class="badge bg-danger border-0 shadow-xl" data-bs-toggle="modal"
                                data-bs-target="#deleteTrainingModal">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>

        <div wire:ignore.self class="modal fade" id="newTrainingModal" tabindex="-1"
            aria-labelledby="newTrainingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content border-0 shadow-md">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white" id="newTrainingModalLabel">Add New Training</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="create">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="code" class="form-label text-uppercase">Training Code</label>
                                <input type="text" class="form-control" id="code" wire:model="code"
                                    placeholder="Training code" oninput="this.value = this.value.toUpperCase()"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label text-uppercase">Training</label>
                                <input type="text" class="form-control" id="name" wire:model="name"
                                    placeholder="Training name" required>
                            </div>
                            <div class="mb-3">
                                <label for="desc" class="form-label text-uppercase">Description</label>
                                <textarea class="form-control" id="desc" wire:model="desc" rows="3"
                                    placeholder="Description about training "></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="purpose" class="form-label text-uppercase">Purpose</label>
                                <textarea class="form-control" id="purpose" wire:model="purpose" rows="3"
                                    placeholder="Training purpose"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="day_duration" class="form-label text-uppercase">Day Duration</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="day_duration"
                                        wire:model="day_duration" placeholder="Enter number of days" min="0">
                                    <span class="input-group-text bg-light text-muted fw-semibold">Days</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="time_duration" class="form-label text-uppercase">Time Duration</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="time_duration"
                                        wire:model="time_duration" placeholder="Enter number of hours" min="0">
                                    <span class="input-group-text bg-light text-muted fw-semibold">Hours</span>
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

        <div wire:ignore.self class="modal fade" id="editTrainingModal" tabindex="-1"
            aria-labelledby="editTrainingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content border-0 shadow-md">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title text-white" id="editTrainingModalLabel">Edit Training</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="update">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_code" class="form-label text-uppercase">Training Code</label>
                                <input type="text" class="form-control" id="edit_code" wire:model="code"
                                    placeholder="Training code" oninput="this.value = this.value.toUpperCase()"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_name" class="form-label text-uppercase">Training</label>
                                <input type="text" class="form-control" id="edit_name" wire:model="name"
                                    placeholder="Training name" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_desc" class="form-label text-uppercase">Description</label>
                                <textarea class="form-control" id="edit_desc" wire:model="desc" rows="3"
                                    placeholder="Description about training "></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="edit_purpose" class="form-label text-uppercase">Purpose</label>
                                <textarea class="form-control" id="edit_purpose" wire:model="purpose" rows="3"
                                    placeholder="Training purpose"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="day_duration" class="form-label text-uppercase">Day Duration</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="day_duration"
                                        wire:model="day_duration" placeholder="Enter number of days" min="0">
                                    <span class="input-group-text bg-light text-muted fw-semibold">Days</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="time_duration" class="form-label text-uppercase">Time Duration</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="time_duration"
                                        wire:model="time_duration" placeholder="Enter number of hours" min="0">
                                    <span class="input-group-text bg-light text-muted fw-semibold">Hours</span>
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

        <div wire:ignore.self class="modal fade" id="deleteTrainingModal" tabindex="-1"
            aria-labelledby="deleteTrainingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-md">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title text-white" id="deleteTrainingModalLabel">Delete Confirmation</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this training?</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>
        @endslot
    </x-card>
</div>