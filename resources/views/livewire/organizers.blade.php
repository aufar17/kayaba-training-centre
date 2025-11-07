<div>
    <x-card :icon="'fa-table'">
        @slot('title')
        Organizer Data
        @endslot

        @slot('body')
        <button class="btn btn-success border-0 mb-5" data-bs-toggle="modal" data-bs-target="#newOrganizerModal">
            <i class="fa-solid fa-plus me-1"></i> New
        </button>

        <div class="table-responsive">
            <table id="organizer" class="table table-light table-hover table-bordered table-striped shadow-sm">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Organizer</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($organizers as $organizer)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $organizer->code }}</td>
                        <td class="text-center">{{ $organizer->name }}</td>
                        <td class="text-center">
                            <button wire:click="edit({{ $organizer->id }})" class="badge bg-warning border-0 shadow-xl"
                                data-bs-toggle="modal" data-bs-target="#editOrganizerModal">
                                <i class="fa-solid fa-edit"></i>
                            </button>

                            <button wire:click="confirmDelete({{ $organizer->id }})"
                                class="badge bg-danger border-0 shadow-xl" data-bs-toggle="modal"
                                data-bs-target="#deleteOrganizerModal">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div wire:ignore.self class="modal fade" id="newOrganizerModal" tabindex="-1"
            aria-labelledby="newOrganizerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content border-0 shadow-md">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white" id="newOrganizerModalLabel">Add New Organizer</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="create">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="code" class="form-label text-uppercase">Organizer Code</label>
                                <input type="text" class="form-control" id="code" wire:model="code"
                                    placeholder="Organizer code" oninput="this.value = this.value.toUpperCase()"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label text-uppercase">Organizer</label>
                                <input type="text" class="form-control" id="name" wire:model="name"
                                    placeholder="Organizer name" required>
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

        <div wire:ignore.self class="modal fade" id="editOrganizerModal" tabindex="-1"
            aria-labelledby="editOrganizerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content border-0 shadow-md">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title text-white" id="editOrganizerModalLabel">Edit Organizer</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="update">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_code" class="form-label text-uppercase">Organizer Code</label>
                                <input type="text" class="form-control" id="edit_code" wire:model="code"
                                    placeholder="Organizer code" oninput="this.value = this.value.toUpperCase()"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_name" class="form-label text-uppercase">Organizer</label>
                                <input type="text" class="form-control" id="edit_name" wire:model="name"
                                    placeholder="Organizer name" required>
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

        <div wire:ignore.self class="modal fade" id="deleteOrganizerModal" tabindex="-1"
            aria-labelledby="deleteOrganizerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-md">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title text-white" id="deleteOrganizerModalLabel">Delete Confirmation</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Organizer?</p>
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