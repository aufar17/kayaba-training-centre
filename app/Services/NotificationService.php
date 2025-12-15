<?php

namespace App\Services;

use App\Interfaces\RepositoryInterface\EventRepositoryInterface;
use App\Interfaces\RepositoryInterface\NotificationRepositoryInterface;
use App\Interfaces\ServiceInterface\NotificationServiceInterface;
use App\Models\Event;
use App\Models\EventTransaction;
use App\Models\Notification;

class NotificationService implements NotificationServiceInterface
{
    protected EventRepositoryInterface $eventRepository;
    protected NotificationRepositoryInterface $notifRepository;
    public function __construct(
        EventRepositoryInterface $eventRepository,
        NotificationRepositoryInterface $notifRepository,
    ) {
        $this->eventRepository = $eventRepository;
        $this->notifRepository = $notifRepository;
    }

    public function getAll()
    {
        return $this->notifRepository->getAll();
    }
    public function getById($id)
    {
        return $this->notifRepository->find($id);
    }

    public function eventCreateNotification($user, $role)
    {
        if (!in_array($role, ['spv', 'manager'])) {
            return collect();
        }

        $dept = $user->dept;

        return $this->notifRepository
            ->getNotificationsFor($dept, ['event'])
            ->orderBy('created_at')
            ->get();
    }


    protected function getRegisteredEventId($user)
    {
        return $this->eventRepository->participantModel()
            ->where('npk', $user->npk)
            ->pluck('event_id')
            ->toArray();
    }

    public function registerNotificationForManager($role, $user)
    {
        if (!in_array($role, ['manager'])) {
            return collect();
        }

        $dept = $user->dept;

        return $this->notifRepository
            ->getNotificationsFor($dept, ['register'])
            ->orderBy('created_at')
            ->get();
    }

    public function registeredParticipantNotification($user)
    {
        $registeredEventIds = $this->getRegisteredEventId($user);

        if (empty($registeredEventIds)) {
            return collect();
        }

        $dept = $user->dept;

        return $this->notifRepository
            ->getNotificationsFor($dept, ['registered_participant'])
            ->orderBy('created_at')
            ->get();
    }

    public function deptApprovalNotification($user, $role)
    {
        if ($user->dept === 'HRD') {
            return $this->getAll()
                ->where('type', 'dept_approval')
                ->orderByDesc('created_at')
                ->get();
        }
        return collect();
    }

    public function hrdApprovalNotification($role, $user)
    {
        if (!in_array($role, ['manager'])) {
            return collect();
        }

        $dept = $user->dept;

        return $this->notifRepository
            ->getNotificationsFor($dept, ['hrd_approval'])
            ->orderBy('created_at')
            ->get();
    }

    public function fixedParticipantNotification($user)
    {
        $registeredEventIds = $this->getRegisteredEventId($user);

        if (empty($registeredEventIds)) {
            return collect();
        }

        $approvedEventIds = EventTransaction::whereIn('event_id', $registeredEventIds)
            ->where('npk', $user->npk)
            ->where('approval', 2)
            ->pluck('event_id')
            ->unique()
            ->values()
            ->toArray();


        if (empty($approvedEventIds)) {
            return collect();
        }

        return $this->getAll()
            ->where('type', 'fixed_participant')
            ->whereIn('event_id', $approvedEventIds)
            ->orderByDesc('created_at')
            ->get();
    }


    public function deptReportNotification($role, $user)
    {
        if (!in_array($role, ['manager'])) {
            return collect();
        }

        $dept = $user->dept;

        return $this->notifRepository
            ->getNotificationsFor($dept, ['report'])
            ->orderBy('created_at')
            ->get();
    }

    public function participantReportNotification($user)
    {
        $records = $this->eventRepository->participantModel()
            ->where('npk', $user->npk)
            ->get(['event_id', 'completed']);

        $completedCode = $records->where('completed', 1)->pluck('event_id');
        $notCompletedCode = $records->where('completed', -1)->pluck('event_id');

        $notifCompleted = collect();
        $notifNotCompleted = collect();

        if (!empty($completedCode)) {
            $notifCompleted = $this->getAll()
                ->where('type', 'participant_completed')
                ->whereIn('event_id', $completedCode)
                ->orderByDesc('created_at')
                ->get();
        }

        if (!empty($notCompletedCode)) {
            $notifNotCompleted = $this->getAll()
                ->where('type', 'participant_notcompleted')
                ->whereIn('event_id', $notCompletedCode)
                ->orderByDesc('created_at')
                ->get();
        }

        $merged = $notifCompleted->merge($notifNotCompleted);

        return $merged
            ->sortByDesc('created_at')
            ->unique('event_id')
            ->values();
    }
}
