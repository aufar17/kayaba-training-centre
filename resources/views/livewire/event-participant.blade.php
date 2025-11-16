<div>
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

        <button type="button" class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#historyApprovalModal">
            <i class="fa-solid fa-clock-rotate-left me-2"></i>History Approval
        </button>
        @if ($this->role === 'spv' && !$registerNotif)
        <button type="button" class="btn btn-success" wire:click="registerNotification">
            <i class="fa-solid fa-paper-plane me-2"></i>Send Notification
        </button>
        @endif
        @if ($this->role === 'manager' && !$deptApprovalNotif)
        <button type="button" class="btn btn-success" wire:click="deptApprovalNotification">
            <i class="fa-solid fa-paper-plane me-2"></i>Send Notification
        </button>
        @endif
        @if ($user->dept === 'HRD')
        @if (!$hrdApprovalNotif)
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
                        <td class="text-center align-middle">{{ $participant->user->full_name }}</td>
                        <td class="text-center align-middle">{{ $participant->user->dept }}</td>
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
                            <button class="badge bg-gradient-danger border-0 shadow-lg"
                                wire:click="setNotCompleted('{{ $participant->id }}')">
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
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        @endslot

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
                                        <td class="text-center">{{ $history->user->npk }}</td>
                                        <td class="text-center">{{ $history->user->full_name }}</td>
                                        <td class="text-center">{{ $history->user->dept }}</td>
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