<div>
    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-header border-0 rounded-top-4"
            style="background: linear-gradient(135deg, #d9e9ff, #b9d7ff, #9bc5ff);">
            <h5 class="text-uppercase text-black fw-bolder mb-0" style="letter-spacing: 2px;">
                > {{ $event->trainings->name }}
            </h5>
        </div>

        <div class="card-body p-4">
            <div class="row g-4 align-items-start">
                <div class="col-md-9">
                    <table class="table table-borderless mb-0 small align-middle">
                        <tr>
                            <td class="text-bold w-20">Code</td>
                            <td class="fw-semibold text-black">{{ $event->trainings->code ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="text-bold w-20">Description</td>
                            <td class="fw-semibold text-black text-wrap">{{ $event->trainings->desc ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="text-bold w-20">Purpose</td>
                            <td class="fw-semibold text-black text-wrap">{{ $event->trainings->purpose ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-bold w-20">Matrix</td>
                            <td class="fw-semibold text-black text-wrap">
                                @if ($event->trainings->matrix && $event->trainings->matrix->count())
                                {{ $event->trainings->matrix->pluck('dept')->implode(', ') }}
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-bold w-20">Golongan</td>
                            <td class="fw-semibold text-black text-wrap">{{ $event->trainings->golongan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-header border-0 rounded-top-4"
            style="background: linear-gradient(135deg, #e3f6ee, #cfeee1, #bfe6d4);">
            <h5 class="fw-bold mb-0 text-dark text-uppercase fw-bolder" style="letter-spacing: 2px">
                > Event Details
            </h5>
        </div>

        <div class="card-body px-4 py-4">
            <div class="row g-3 flex-nowrap overflow-auto justify-content-between">

                <div class="col-auto">
                    <div class="d-flex align-items-center gap-3 p-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;">
                            <i class="fa-solid fa-location-dot fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-black fw-bolder text-uppercase">Location</small>
                            <div class="fw-semibold text-dark">
                                {{ $event->locations->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="d-flex align-items-center gap-3 p-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;">
                            <i class="fa-solid fa-building fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-black fw-bolder text-uppercase">Organizer</small>
                            <div class="fw-semibold text-dark">
                                {{ $event->organizers->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="d-flex align-items-center gap-3 p-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;">
                            <i class="fa-solid fa-chalkboard-user fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-black fw-bolder text-uppercase">Trainer</small>
                            <div class="fw-semibold text-dark">
                                {{ $event->trainers->pluck('name')->filter()->implode(', ') ?: '-' }}
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="d-flex align-items-center gap-3 p-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;">
                            <i class="fa-solid fa-calendar-days fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-black fw-bolder text-uppercase">Date</small>
                            <div class="fw-semibold text-dark">
                                {{ $event->startDateFormat() }} – {{ $event->endDateFormat() }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="d-flex align-items-center gap-3 p-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;">
                            <i class="fa-regular fa-clock fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-black fw-bolder text-uppercase">Time</small>
                            <div class="fw-semibold text-dark">
                                {{ $event->start_time }} – {{ $event->end_time }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <x-card :icon="'fa-table'">
        @slot('title')
        {{ $event->code }} - {{ $event->trainings->name }}
        @endslot

        @slot('body')
        @if ($this->role == 'spv' && $event->status == 'upcoming')
        <form class="mb-4" wire:submit.prevent="register">
            <div class="mb-3 position-relative">
                <label for="searchParticipant" class="form-label fw-bolder text-uppercase">Search Participant</label>
                <input type="text" id="searchParticipant" placeholder="Enter NPK or Name" class="form-control shadow-sm"
                    autocomplete="off">

                <ul id="suggestionList" class="list-group position-absolute shadow w-100"
                    style="z-index:1000; display:none;">
                    @foreach($users as $user)
                    <li class="list-group-item list-group-item-action" style="cursor: pointer"
                        data-npk="{{ $user->npk }}" data-name="{{ $user->full_name }}">
                        {{ $user->npk }} - {{ $user->full_name }}
                    </li>
                    @endforeach
                </ul>
            </div>

            <input type="hidden" id="selectedNpk" wire:model="npk">

            <button type="submit" class="btn btn-success">
                <i class="fa-solid fa-cash-register me-1"></i> Register
            </button>
        </form>

        <hr class="mt-3 mb-5">
        @endif
        @if (in_array($this->role, ['spv', 'manager']) || $user->dept == 'HRD')
        <button type="button" class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#historyApprovalModal">
            <i class="fa-solid fa-clock-rotate-left me-2"></i>History Approval
        </button>
        @endif
        @if ($this->role === 'spv' && !$registerNotif && $event->status == 'upcoming')
        <button type="button" class="btn btn-success" wire:click="registerNotification">
            <i class="fa-solid fa-paper-plane me-2"></i>Send Notification
        </button>
        @endif
        @if ($this->role === 'manager' && !$deptApprovalNotif && $event->status == 'upcoming')
        <button type="button" class="btn btn-success" wire:click="deptApprovalNotification">
            <i class="fa-solid fa-paper-plane me-2"></i>Send Notification
        </button>
        @endif
        @if ($user->dept === 'HRD')
        <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#addParticipantModal">
            <i class="fa-solid fa-plus me-2"></i>Add Participant
        </button>
        @if (!$hrdApprovalNotif && $event->status == 'upcoming')
        <button type="button" class="btn btn-success" wire:click="hrdApprovalNotification">
            <i class="fa-solid fa-paper-plane me-2"></i>Send Notification
        </button>
        @endif
        @if ( $event->status == 'past')
        <button type="button" class="btn btn-success" wire:click="reportNotification">
            <i class="fa-solid fa-paper-plane me-2"></i>Send Report
        </button>
        @endif
        @endif

        <div class="table-responsive">
            <table id="event-participant" class="table table-light table-striped table-hover table-bordered shadow-sm">
                <thead>
                    <tr>
                        <th class="text-center fw-bold py-2">No</th>
                        <th class="text-center fw-bold py-2">NPK</th>
                        <th class="text-center fw-bold py-2">Name</th>
                        <th class="text-center fw-bold py-2">Department</th>
                        @if ($event->status == 'upcoming')
                        @if ($this->role == 'spv')
                        <th class="text-center fw-bold py-2">Action</th>
                        @endif
                        @if ($this->role == 'manager' || $user->dept == 'HRD')
                        <th class="text-center fw-bold py-2">Approval</th>
                        @endif
                        @endif
                        @if ($event->status == 'past')
                        <th class="text-center fw-bold py-1">Completed</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $participant)
                    @php
                    $action = $this->approvalAction($participant);
                    $completed = $this->completedLabel($participant->completed);
                    @endphp
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="text-center align-middle">{{ $participant->npk }}</td>
                        <td class="text-center align-middle">{{ $participant->user->full_name ?? '-' }}</td>
                        <td class="text-center align-middle">{{ $participant->user->dept ?? '-' }}</td>
                        @if ($event->status == 'upcoming')
                        @if ($this->role == 'spv')
                        <td class="text-center align-middle">
                            <button wire:click="confirmDelete({{ $participant->id }})"
                                class="badge bg-danger border-0 shadow-xl" data-bs-toggle="modal"
                                data-bs-target="#deleteParticipantModal">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                        @endif
                        @if ($this->role == 'manager' || $user->dept == 'HRD')
                        <td class="text-center align-middle">
                            @if ($action['type'] === 'button')
                            <div class="mt-2 d-flex justify-content-center gap-2">
                                <button class="badge bg-gradient-success border-0 shadow-lg"
                                    wire:click="approve('{{ $participant->id }}')">
                                    <i class="fa-solid fa-check fs-6"></i>
                                </button>
                                <button class="badge bg-gradient-danger border-0 shadow-lg"
                                    wire:click="reject('{{ $participant->id }}')">
                                    <i class="fa-solid fa-xmark fs-6"></i>
                                </button>
                            </div>
                            @else
                            <span class="badge {{ $action['data']['class'] }} px-3 py-2">
                                {{ $action['data']['text'] }}
                            </span>
                            @endif
                        </td>
                        @endif
                        @endif
                        @if ($event->status == 'past')
                        <td class="text-center align-middle">
                            @if ($participant->completed == 0)
                            @if ($user->dept == 'HRD')
                            <button class="badge bg-gradient-success border-0 shadow-lg"
                                wire:click="setCompleted('{{ $participant->id }}')">
                                <i class="fa-solid fa-check fs-6"></i>
                            </button>
                            <button class="badge bg-gradient-danger border-0 shadow-lg" data-bs-toggle="modal"
                                data-bs-target="#notCompletedModal{{ $participant->id }}">
                                <i class="fa-solid fa-xmark fs-6"></i>
                            </button>


                            @else
                            <span class="badge bg-gradient-info px-3 py-2">Waiting Report</span>
                            @endif
                            @else
                            <span class="badge {{ $completed['class'] }} px-3 py-2">
                                {{ $completed['text'] }}
                            </span>
                            @endif
                        </td>
                        @endif
                    </tr>

                    <div class="modal fade" id="notCompletedModal{{ $participant->id }}" tabindex="-1"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content shadow-lg">

                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" cols="30" rows="3" wire:model="notes"
                                        placeholder="Enter notes for this participant"></textarea>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                    <button type="button" class="btn btn-danger"
                                        wire:click="setNotCompleted({{ $participant->id }})">
                                        Yes, Continue
                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>

                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        @endslot

        <div wire:ignore.self class="modal fade" id="addParticipantModal" tabindex="-1"
            aria-labelledby="addParticipantModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">

                    <div class="modal-header bg-war text-white">
                        <h5 class="modal-title fw-bold" id="addParticipantModalLabel">Add Participant</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3 position-relative">
                            <label class="form-label fw-bold">NPK</label>

                            <input type="text" class="form-control" wire:model.live="npk" autocomplete="off"
                                placeholder="Search NPK or name employee">

                            @error('npk')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                            @if (!empty($searchResults))
                            <ul class="list-group position-absolute w-100 mt-1 shadow-sm"
                                style="z-index: 1100; max-height: 200px; overflow-y: auto;">
                                @foreach ($searchResults as $result)
                                <li class="list-group-item list-group-item-action" style="cursor: pointer;"
                                    wire:click="addParticipantByHrd('{{ $result->npk }}')">
                                    <strong>{{ $result->npk }}</strong> — {{ $result->full_name }}
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>

                        @if (!empty($selectedParticipants))
                        <div class="mt-3">
                            <label class="fw-bold mb-2">Selected Participants</label>

                            <ul class="list-group">
                                @foreach ($selectedParticipants as $participant)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $participant['npk'] }} - {{ $participant['full_name'] }}

                                    <button class="badge bg-gradient-danger border-0"
                                        wire:click="removeSelected('{{ $participant['npk'] }}')">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <button type="button" class="btn btn-sm btn-success" wire:click="registerParticipantbyHrd">
                            <i class="fa-solid fa-paper-plane me-2"></i>Submit
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="historyApprovalModal" tabindex="-1" aria-labelledby="historyApprovalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title fw-bold text-white" id="historyApprovalLabel">
                            <i class="fa-solid fa-clock-rotate-left me-2"></i>History Approval
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="history-approval" class="table table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">NPK</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Department</th>
                                        <th class="text-center">Approval</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($histories as $history)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $history->npk }}</td>
                                        <td class="text-center">{{ $history->user->full_name ?? '-' }}</td>
                                        <td class="text-center">{{ $history->user->dept ?? '-' }}</td>
                                        <td class="text-center">
                                            @php
                                            $action = $this->approvalAction($history);
                                            @endphp
                                            <span class="badge {{ $action['data']['class'] }} px-3 py-2">
                                                {{ $action['data']['text'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="deleteParticipantModal" tabindex="-1"
            aria-labelledby="deleteParticipantModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-md">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title text-white" id="deleteParticipantModalLabel">Delete Confirmation</h5>
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

        <div wire:ignore.self class="modal fade" id="notCompletedModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg">

                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to mark this participant as
                        <b>Not Completed</b>?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="button" class="btn btn-danger" wire:click="setNotCompleted"
                            data-bs-dismiss="modal">
                            Yes, Continue
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </x-card>
    <script>
        const input = document.getElementById('searchParticipant');
    const list = document.getElementById('suggestionList');
    const hiddenInput = document.getElementById('selectedNpk');

    input.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        let hasMatch = false;
        list.style.display = 'block';
        Array.from(list.children).forEach(li => {
            const name = li.dataset.name.toLowerCase();
            const npk = li.dataset.npk.toLowerCase();
            if(name.includes(query) || npk.includes(query)) {
                li.style.display = 'block';
                hasMatch = true;
            } else {
                li.style.display = 'none';
            }
        });
        if(!hasMatch) list.style.display = 'none';
    });

    Array.from(list.children).forEach(li => {
        li.addEventListener('click', function() {
            const npk = this.dataset.npk;
            const name = this.dataset.name;
            input.value = `${npk} - ${name}`; 
            hiddenInput.value = npk; 
            list.style.display = 'none';

            hiddenInput.dispatchEvent(new Event('input'));
        });
    });

    document.addEventListener('click', function(e) {
        if(!list.contains(e.target) && e.target !== input) {
            list.style.display = 'none';
        }
    });
    </script>

</div>