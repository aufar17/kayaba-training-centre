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
    public $npk;
    public $role;
    public $selectedId;
    public $registerNotif;
    public $deptApprovalNotif;
    public $hrdApprovalNotif;
    public $reportNotif;

    public $participants = [];
    public $counts = [];
    public $countLabels = [];
    public $searchResults = [];
    public $selectedParticipants = [];
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
    public function searchNpk()
    {
        if (strlen($this->npk) < 2) {
            $this->searchResults = [];
            return;
        }

        $code = Event::where('id', $this->id)->pluck('code');

        $registeredNpks = EventTransaction::where('event_id', $code)
            ->pluck('npk')
            ->toArray();

        $tempSelected = collect($this->selectedParticipants)->pluck('npk')->toArray();

        $excludeNpks = array_merge($registeredNpks, $tempSelected);

        $this->searchResults = CTUser::where('npk', 'like', '%' . $this->npk . '%')
            ->whereNotIn('npk', $excludeNpks)
            ->limit(10)
            ->get(['npk', 'full_name']);
    }


    public function updatedNpk()
    {
        $this->searchNpk();
    }

    public function addParticipantByHrd($npk)
    {
        $participant = CTUser::where('npk', $npk)->first();

        if (!$participant) {
            return;
        }

        if (!collect($this->selectedParticipants)->contains('npk', $participant->npk)) {
            $this->selectedParticipants[] = [
                'npk' => $participant->npk,
                'full_name' => $participant->full_name
            ];
        }

        $this->npk = '';
        $this->searchResults = [];
    }

    public function removeSelected($npk)
    {
        $this->selectedParticipants = array_filter(
            $this->selectedParticipants,
            fn($item) => $item['npk'] !== $npk
        );

        $this->selectedParticipants = array_values($this->selectedParticipants);
    }

    public function register()
    {
        if ($this->checkRole() != 'spv') {
            session()->flash('error', 'Only Supervisor can register participant.');
            return;
        }

        $service = $this->service ?? app(EventServiceInterface::class);
        $data = [
            'event_id' => $this->id,
            'npk' => $this->npk
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
        if (empty($this->selectedParticipants)) {
            session()->flash('error', 'Belum ada peserta yang dipilih.');
            return;
        }

        $code = Event::where('id', $this->id)->value('code');

        $service = $this->service ?? app(EventServiceInterface::class);

        $result = $service->registerParticipantbyHrd($code, $this->selectedParticipants);

        if (!$result) {
            session()->flash('error', 'Failed to add participants. Please try again.');
            return;
        }

        $this->npk = '';
        $this->searchResults = [];
        $this->selectedParticipants = [];

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
            'completed' => -1
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
