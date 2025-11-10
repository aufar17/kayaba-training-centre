<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\EventServiceInterface;
use App\Models\Auth\CTUser;
use Livewire\Component;

class EventParticipant extends Component
{
    public $id;
    public $user;
    public $npk;
    public $role;
    public $selectedId;

    public $participants = [];
    public $counts = [];
    public $countLabels = [];
    protected EventServiceInterface $service;
    public function mount()
    {
        $this->service = app(EventServiceInterface::class);
        $this->role = $this->checkRole();
        $this->participants = $this->getParticipants();
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
        $listUser = CTUser::where('dept', $this->user->dept)->get();
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

        $service->registerParticipant($data);

        $service
            ? session()->flash('success', 'New participant added successfully.')
            : session()->flash('error', 'Failed to add new participant. Please try again.');

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
        $approval = $participant->approval;
        $dept = $this->user->dept;
        $role = $this->role;

        switch (true) {
            case $dept === 'HRD' && (int) $approval === 1:
                return ['type' => 'button', 'show' => true];

            case $dept === 'HRD' && in_array((int) $approval, [2, -2], true):
                return ['type' => 'label', 'data' => $this->approvalLabel((int) $approval)];

            case $role === 'manager' && in_array((int) $approval, [1, -1], true):
                return ['type' => 'label', 'data' => $this->approvalLabel((int) $approval)];

            case $role === 'manager' && in_array((int) $approval, [0, null], true):
                return ['type' => 'button', 'show' => true];

            default:
                return ['type' => 'label', 'data' => $this->approvalLabel((int) $approval)];
        }
    }

    public function approvalLabel($approval)
    {
        return match ($approval) {
            1   => ['text' => 'Approved by DeptHead', 'class' => 'bg-gradient-info'],
            -1  => ['text' => 'Rejected by DeptHead', 'class' => 'bg-gradient-danger'],
            2   => ['text' => 'Approved by HRD', 'class' => 'bg-gradient-success'],
            -2  => ['text' => 'Rejected by HRD', 'class' => 'bg-gradient-danger'],
            default => ['text' => 'Pending Approval', 'class' => 'bg-gradient-secondary'],
        };
    }

    public function getHistoryApprovalbyDept($id)
    {
        $service = $this->service ?? app(EventServiceInterface::class);
        return $service->getHistoryApprovalbyDept($id);
    }
}
