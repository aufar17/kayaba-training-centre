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
                        <td class="text-center">{{ $training->duration ?? '-' }} </td>
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
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0 shadow-md">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white" id="newTrainingModalLabel">Add New Training</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="create">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="code" class="form-label text-uppercase">Training Code</label>
                                        <input type="text" class="form-control" id="code" wire:model="code"
                                            placeholder="Training code" oninput="this.value = this.value.toUpperCase()"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-uppercase">Training</label>
                                        <input type="text" class="form-control" id="name" wire:model="name"
                                            placeholder="Training name" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="golongan" class="form-label text-uppercase">Golongan</label>
                                        <input class="form-control" id="golongan" wire:model="golongan"
                                            placeholder="Ex: I - VI " oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="desc" class="form-label text-uppercase">Description</label>
                                        <textarea class="form-control" id="desc" wire:model="desc" rows="3"
                                            placeholder="Description about training "></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="purpose" class="form-label text-uppercase">Purpose</label>
                                        <textarea class="form-control" id="purpose" wire:model="purpose" rows="3"
                                            placeholder="Training purpose"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="day_duration" class="form-label text-uppercase">Day Duration</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="day_duration"
                                                wire:model="day_duration" placeholder="Enter number of days" min="0">
                                            <span class="input-group-text bg-light text-muted fw-semibold">Days</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="time_duration" class="form-label text-uppercase">Time
                                            Duration</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="time_duration"
                                                wire:model="time_duration" placeholder="Enter number of hours" min="0">
                                            <span class="input-group-text bg-light text-muted fw-semibold">Hours</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-uppercase">Department</label>

                                @foreach($departmentInputs as $index => $value)
                                <div class="position-relative mb-2">
                                    <input type="text" class="form-control" placeholder="Type department..."
                                        wire:model.live="departmentInputs.{{ $index }}">
                                    <button type="button" class="btn btn-danger position-absolute rounded-1 top-0 end-0"
                                        wire:click="removeDepartmentInput({{ $index }})">
                                        <i class="fa-solid fa-trash fs-6"></i>
                                    </button>

                                    @if(!empty($departmentSearchResults[$index] ?? []))
                                    <ul class="list-group position-absolute w-100 mt-1 cursor-pointer"
                                        style="z-index: 10; max-height: 200px; overflow-y: auto;">
                                        @foreach($departmentSearchResults[$index] as $dept)
                                        <li class="list-group-item list-group-item-action"
                                            wire:click.prevent="selectDepartment({{ $index }}, '{{ $dept->code }}', '{{ $dept->name }}')">
                                            {{ $dept->code }} - {{ $dept->name }}
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </div>
                                @endforeach

                                <button type="button" class="btn btn-sm btn-success mt-2"
                                    wire:click="addDepartmentInput">
                                    + Add Department
                                </button>
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
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0 shadow-md">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="editTrainingModalLabel">Edit Training</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form wire:submit.prevent="update">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label text-uppercase">Training Code</label>
                                        <input type="text" class="form-control" wire:model="code"
                                            oninput="this.value = this.value.toUpperCase()" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label text-uppercase">Training</label>
                                        <input type="text" class="form-control" wire:model="name" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label text-uppercase">Golongan</label>
                                        <input class="form-control" wire:model="golongan"
                                            oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label text-uppercase">Description</label>
                                        <textarea class="form-control" wire:model="desc" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label text-uppercase">Purpose</label>
                                        <textarea class="form-control" wire:model="purpose" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label text-uppercase">Day Duration</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" wire:model="day_duration" min="0">
                                            <span class="input-group-text bg-light text-muted fw-semibold">Days</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label text-uppercase">Time Duration</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" wire:model="time_duration"
                                                min="0">
                                            <span class="input-group-text bg-light text-muted fw-semibold">Hours</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-uppercase">Department</label>

                                @foreach($departmentInputs as $index => $value)
                                <div class="position-relative mb-2">
                                    <input type="text" class="form-control" placeholder="Type department..."
                                        wire:model.live="departmentInputs.{{ $index }}">
                                    <button type="button" class="btn btn-danger position-absolute top-0 end-0 rounded-1"
                                        wire:click="removeDepartmentInput({{ $index }})">
                                        <i class="fa-solid fa-trash fs-6"></i>
                                    </button>

                                    @if(!empty($departmentSearchResults[$index] ?? []))
                                    <ul class="list-group position-absolute w-100 mt-1 cursor-pointer"
                                        style="z-index:10; max-height:200px; overflow-y:auto;">
                                        @foreach($departmentSearchResults[$index] as $deptIndex => $dept)
                                        <li class="list-group-item list-group-item-action"
                                            wire:click.prevent="selectDepartment({{ $index }}, '{{ $dept->code }}', '{{ $dept->name }}')">
                                            {{ $dept->code }} - {{ $dept->name }}
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </div>
                                @endforeach

                                <button type="button" class="btn btn-sm btn-success mt-2"
                                    wire:click="addDepartmentInput">
                                    + Add Department
                                </button>
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