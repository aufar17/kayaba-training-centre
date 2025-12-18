<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\EventServiceInterface;
use App\Models\Auth\CTUser;
use App\Models\Event;
use App\Models\EventTransaction;
use App\Models\Notification;
use Livewire\Component;

class EventParticipant extends Component
{
    public $id;
    public $user;
    public $npkSpv = '';
    public $npkHrd = '';
    public $role;
    public $notes;
    public $selectedId;
    public $registerNotif;
    public $deptApprovalNotif;
    public $hrdApprovalNotif;
    public $reportNotif;

    public $participants = [];
    public $counts = [];
    public $countLabels = [];

    public $searchSpvResults = [];
    public $selectedUserbyDept = [];
    public ?array $selectedParticipantsSpv = null;

    public $searchHrdResults = [];
    public array $selectedParticipantsHrd = [];

    protected EventServiceInterface $service;
    public function mount()
    {
        $this->service = app(EventServiceInterface::class);
        $this->role = $this->checkRole();
        $this->participants = $this->getParticipants();
        $this->registerNotif = $this->checkRegisterNotif();
        $this->deptApprovalNotif = $this->checkDeptApprovalNotif();
        $this->hrdApprovalNotif = $this->checkHrdApprovalNotif();
        $this->reportNotif = $this->checkReportNotif();
    }
    public function render()
    {
        $data =  [
            'event' => $this->getEvent(),
            'users' => $this->getUser(),
            'participants' => $this->participants,
            'histories' => $this->getHistoryApprovalbyDept($this->id),

        ];

        return view('livewire.event-participant', $data);
    }

    public function checkRole()
    {
        $role = $this->user->getUserRole();
        return $role;
    }

    public function getEvent()
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $id = $this->id;
        $event = $service->getById($id);
        return $event;
    }

    public function getEventStatus($event)
    {
        $now = now();

        if ($event->start_date <= $now && $event->end_date >= $now) {
            return 'ongoing';
        } elseif ($event->start_date > $now) {
            return 'upcoming';
        } else {
            return 'past';
        }
    }

    public function getUser()
    {
        $registeredNPKs = EventTransaction::where('event_id', $this->id)
            ->pluck('npk')
            ->toArray();

        $listUser = CTUser::where('dept', $this->user->dept)
            ->whereNotIn('npk', $registeredNPKs)
            ->get();

        return $listUser;
    }

    public function getParticipants()
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $id = $this->id;

        if ($this->user->dept == 'HRD') {
            $participants = $service->getParticipants($id);
        } else {
            $participants = $service->getParticipantbyDept($id);
        }
        return $participants;
    }
    public function searchNpk(string $context)
    {
        if ($context === 'spv') {
            $this->searchNpkSpv();
            return;
        }

        if ($context === 'hrd') {
            $this->searchNpkHrd();
            return;
        }
    }

    private function conflictEvent($selectedParticipants)
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $event = $service->getById($this->id);

        $eventCode = $event->code;

        $registeredNpks = EventTransaction::where('event_id', $eventCode)
            ->pluck('npk')
            ->toArray();

        $tempSelected = collect($selectedParticipants)
            ->pluck('npk')
            ->toArray();

        $conflictingEventCodes = Event::where('id', '!=', $event->id)
            ->where(function ($q) use ($event) {
                $q->where('start_date', '<=', $event->end_date)
                    ->where('end_date', '>=', $event->start_date);
            })
            ->pluck('code')
            ->toArray();

        $conflictingNpks = EventTransaction::whereIn('event_id', $conflictingEventCodes)
            ->pluck('npk')
            ->toArray();

        $excludeNpks = array_unique(array_merge(
            $registeredNpks,
            $tempSelected,
            $conflictingNpks
        ));

        return $excludeNpks;
    }

    private function searchNpkSpv()
    {
        if (strlen($this->npkSpv) < 2) {
            $this->searchSpvResults = [];
            return;
        }

        $excludeNpks = $this->conflictEvent($this->selectedParticipantsSpv);

        $this->searchSpvResults = CTUser::query()
            ->where('dept', $this->user->dept)
            ->where(function ($q) {
                $q->where('npk', 'like', '%' . $this->npkSpv . '%')
                    ->orWhere('full_name', 'like', '%' . $this->npkSpv . '%');
            })
            ->whereNotIn('npk', $excludeNpks)
            ->limit(10)
            ->get(['npk', 'full_name']);
    }

    private function searchNpkHrd()
    {
        if (strlen($this->npkHrd) < 2) {
            $this->searchHrdResults = [];
            return;
        }

        $excludeNpks = $this->conflictEvent($this->selectedParticipantsHrd);

        $this->searchHrdResults = CTUser::where(function ($q) {
            $q->where('npk', 'like', "%{$this->npkHrd}%")
                ->orWhere('full_name', 'like', "%{$this->npkHrd}%");
        })
            ->whereNotIn('npk', $excludeNpks)
            ->limit(10)
            ->get();
    }

    public function updatedNpkSpv()
    {
        if ($this->selectedParticipantsSpv) {
            return;
        }

        $this->searchNpk('spv');
    }

    public function updatedNpkHrd()
    {
        $this->searchNpk('hrd');
    }

    public function selectUserbyDept($npk, $name)
    {
        $this->selectedParticipantsSpv = [
            'npk' => $npk,
            'full_name' => $name,
        ];

        $this->npkSpv = "{$npk} — {$name}";

        $this->searchSpvResults = [];
    }

    public function addParticipantByHrd($npk)
    {
        $participant = CTUser::where('npk', $npk)->first();

        if (!$participant) {
            return;
        }

        if (!collect($this->selectedParticipantsHrd)->contains('npk', $participant->npk)) {
            $this->selectedParticipantsHrd[] = [
                'npk' => $participant->npk,
                'full_name' => $participant->full_name
            ];
        }

        $this->npkHrd = '';
        $this->searchHrdResults = [];
    }

    public function removeSelected($npk)
    {
        $this->selectedParticipantsHrd = array_filter(
            $this->selectedParticipantsHrd,
            fn($item) => $item['npk'] !== $npk
        );

        $this->selectedParticipantsHrd = array_values($this->selectedParticipantsHrd);
    }

    public function register()
    {
        if ($this->checkRole() != 'spv') {
            session()->flash('error', 'Only Supervisor can register participant.');
            return;
        }

        if (!$this->selectedParticipantsSpv) {
            $this->addError('npkSpv', 'Please select participant');
            return;
        }

        $service = $this->service ?? app(EventServiceInterface::class);
        $data = [
            'event_id' => $this->id,
            'npk' => $this->selectedParticipantsSpv['npk'],
        ];
        $result = $service->registerParticipant($data);

        if ($result) {
            session()->flash('success', 'New participant added successfully.');
        } else {
            session()->flash('error', 'Failed to add new participant.');
        }

        return redirect()->route('event-participant', ['id' => $this->id]);
    }

    public function registerParticipantbyHrd()
    {
        if (empty($this->selectedParticipantsHrd)) {
            session()->flash('error', 'Belum ada peserta yang dipilih.');
            return;
        }

        $code = Event::where('id', $this->id)->value('code');

        $service = $this->service ?? app(EventServiceInterface::class);

        $result = $service->registerParticipantbyHrd($code, $this->selectedParticipantsHrd);

        if (!$result) {
            session()->flash('error', 'Failed to add participants. Please try again.');
            return;
        }

        $this->npkHrd = '';
        $this->searchHrdResults = [];
        $this->selectedParticipantsHrd = [];

        session()->flash('success', 'Participants added successfully!');
        return redirect()->route('event-participant', ['id' => $this->id]);
    }



    public function confirmDelete($id)
    {
        $this->selectedId = $id;
    }

    public function delete()
    {
        $this->service = app(EventServiceInterface::class);
        $id = $this->selectedId;
        $delete = $this->service->deleteParticipant($id);

        $delete
            ? session()->flash('success', 'Participant deleted successfully!')
            : session()->flash('error', 'Failed to delete participant. Please try again.');

        return redirect()->route('event-participant', ['id' => $this->id]);
    }

    public function approve($participantId)
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        if ($this->user->dept == 'HRD') {
            $approval = 2;
        } else {
            $approval = 1;
        }
        $data = [
            'id' => $participantId,
            'approval' => $approval
        ];
        $service->approvalParticipant($data);

        session()->flash('success', 'Participant approved successfully.');
        return redirect()->route('event-participant', ['id' => $this->id]);
    }

    public function reject($participantId)
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        if ($this->user->dept == 'HRD') {
            $approval = -2;
        } else {
            $approval = -1;
        }
        $data = [
            'id' => $participantId,
            'approval' => $approval
        ];
        $service->approvalParticipant($data);

        session()->flash('error', 'Participant rejected.');
        return redirect()->route('event-participant', ['id' => $this->id]);
    }

    public function approvalAction($participant)
    {
        $approval = (int) $participant->approval;
        $dept = $this->user->dept;
        $role = $this->role;

        switch (true) {
            case $dept === 'HRD' && $approval === 1:
                return ['type' => 'button', 'show' => true, 'data' => null];

            case $dept === 'HRD' && in_array($approval, [2, -2], true):
                return ['type' => 'label', 'data' => $this->approvalLabel($approval)];

            case $role === 'manager' && in_array($approval, [1, -1], true):
                return ['type' => 'label', 'data' => $this->approvalLabel($approval)];

            case $role === 'manager' && in_array($approval, [0, null], true):
                return ['type' => 'button', 'show' => true, 'data' => $this->approvalLabel($approval)];

            default:
                return ['type' => 'label', 'data' => $this->approvalLabel($approval)];
        }
    }

    public function approvalLabel($approval)
    {
        return match ($approval) {
            1   => ['text' => 'Approved by DeptHead', 'class' => 'bg-gradient-info'],
            -1  => ['text' => 'Rejected by DeptHead', 'class' => 'bg-gradient-danger'],
            2   => ['text' => 'Approved by HRD', 'class' => 'bg-gradient-success'],
            -2  => ['text' => 'Rejected by HRD', 'class' => 'bg-gradient-danger'],
            0 => ['text' => 'Pending Approval', 'class' => 'bg-gradient-secondary'],
            default => ['text' => 'Waiting Approval', 'class' => 'bg-gradient-secondary'],
        };
    }

    public function getHistoryApprovalbyDept($id)
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        return $service->getHistoryApprovalbyDept($id);
    }


    public function checkRegisterNotif()
    {
        $check = Notification::where('event_id', $this->id)
            ->where('type', 'register')
            ->exists();
        return $check;
    }

    public function checkDeptApprovalNotif()
    {
        $check = Notification::where('event_id', $this->id)
            ->where('type', 'dept_approval')
            ->exists();
        return $check;
    }
    public function checkHrdApprovalNotif()
    {
        $check = Notification::where('event_id', $this->id)
            ->where('type', 'hrd_approval')
            ->exists();
        return $check;
    }
    public function checkReportNotif()
    {
        $check = Notification::where('event_id', $this->id)
            ->where('type', 'report')
            ->exists();
        return $check;
    }

    public function registerNotification()
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $id = $this->id;
        $user = $this->user;
        $notif = $service->registerNotification($id, $user);

        $notif
            ? session()->flash('success', 'Notification sent successfully!')
            : session()->flash('error', 'Failed to send notification. Please try again.');

        return redirect()->route('event-participant', ['id' => $id]);
    }
    public function deptApprovalNotification()
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $id = $this->id;
        $user = $this->user;
        $notif = $service->deptApprovalNotification($id, $user);

        $notif
            ? session()->flash('success', 'Notification sent successfully!')
            : session()->flash('error', 'Failed to send notification. Please try again.');

        return redirect()->route('event-participant', ['id' => $id]);
    }
    public function hrdApprovalNotification()
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $id = $this->id;
        $user = $this->user;

        $notif = $service->hrdApprovalNotification($id, $user);

        if (!$notif) {
            session()->flash('error', 'Mohon untuk melakukan approval terlebih dahulu.');
            return;
        }

        session()->flash('success', 'Notification sent successfully!');
        return redirect()->route('event-participant', ['id' => $id]);
    }

    public function reportNotification()
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $id = $this->id;
        $user = $this->user;
        $notif = $service->reportNotification($id, $user);

        $notif
            ? session()->flash('success', 'Notification sent successfully!')
            : session()->flash('error', 'Failed to send notification. Please try again.');

        return redirect()->route('event-participant', ['id' => $id]);
    }

    public function setCompleted($participantId)
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $data = [
            'id' => $participantId,
            'completed' => 1
        ];
        $service->completedParticipant($data);

        session()->flash('success', 'The participant has successfully completed the training.');
        return redirect()->route('event-participant', ['id' => $this->id]);
    }
    public function setNotCompleted($participantId)
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        $data = [
            'id' => $participantId,
            'completed' => -1,
            'notes' => $this->notes
        ];
        $service->completedParticipant($data);

        session()->flash('success', 'The participant did not successfully complete the training.');
        return redirect()->route('event-participant', ['id' => $this->id]);
    }

    public function completedLabel($completed)
    {
        return match ($completed) {
            -1 => ['text' => 'Not Completed', 'class' => 'bg-gradient-danger'],
            0   => ['text' => 'Not Complete', 'class' => 'bg-gradient-danger'],
            1 => ['text' => 'Completed', 'class' => 'bg-gradient-success'],
            default => ['text' => 'Waiting Report', 'class' => 'bg-gradient-secondary'],
        };
    }
}
