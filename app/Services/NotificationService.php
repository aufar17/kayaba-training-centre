<?php

namespace App\Services;

use App\Interfaces\RepositoryInterface\EventRepositoryInterface;
use App\Interfaces\RepositoryInterface\NotificationRepositoryInterface;
use App\Interfaces\ServiceInterface\NotificationServiceInterface;
use App\Models\Event;
use App\Models\EventTransaction;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function getAllForUser($user, $role)
    {
        return collect([
            $this->eventCreateNotification($user, $role),
            $this->registerNotificationForManager($role, $user),
            $this->registeredParticipantNotification($user),
            $this->deptApprovalNotification($user, $role),
            $this->hrdApprovalNotification($role, $user),
            $this->fixedParticipantNotification($user),
            $this->deptReportNotification($role, $user),
            $this->participantReportNotification($user),
        ])->filter()->flatten()->sortByDesc('created_at');;
    }

    public function mapNotifications($notifications)
    {
        return $notifications->transform(function ($notif) {
            $createdAt = $notif->created_at instanceof Carbon
                ? $notif->created_at
                : Carbon::parse($notif->created_at);

            $notif->time = $createdAt->diffInDays(now()) < 7
                ? $createdAt->diffForHumans()
                : $createdAt->format('d M Y, H:i');

            $typeMap = [
                'event' => ['icon' => 'fa-solid fa-bell', 'color' => 'info'],
                'register' => ['icon' => 'fa-solid fa-user-plus', 'color' => 'warning'],
                'registered_participant' => ['icon' => 'fa-solid fa-user-plus', 'color' => 'warning'],
                'dept_approval' => ['icon' => 'fa-solid fa-user-check', 'color' => 'success'],
                'hrd_approval' => ['icon' => 'fa-solid fa-clipboard-check', 'color' => 'success'],
                'fixed_participant' => ['icon' => 'fa-solid fa-clipboard-check', 'color' => 'success'],
                'report' => ['icon' => 'fa-solid fa-file-lines', 'color' => 'warning'],
                'participant_completed' => ['icon' => 'fa-solid fa-file-signature', 'color' => 'primary'],
                'participant_notcompleted' => ['icon' => 'fa-solid fa-file-signature', 'color' => 'primary'],
                'default' => ['icon' => 'fa-solid fa-info-circle', 'color' => 'secondary'],
            ];

            $type = $notif->type ?? 'default';
            $notif->icon = $typeMap[$type]['icon'] ?? $typeMap['default']['icon'];
            $notif->color = $typeMap[$type]['color'] ?? $typeMap['default']['color'];

            return $notif;
        });
    }

    public function groupByDateLabel($notifications)
    {
        return $notifications->groupBy(function ($notif) {
            $date = $notif->created_at->format('Y-m-d');
            $today = now()->format('Y-m-d');
            $yesterday = now()->subDay()->format('Y-m-d');

            if ($date === $today) return 'Today';
            if ($date === $yesterday) return 'Yesterday';

            return Carbon::parse($date)->isoFormat('dddd, D MMMM Y');
        });
    }

    public function paginateGroupedNotifications($grouped, $page = 1, $perPage = 20)
    {
        $flat = $grouped->map(function ($items, $label) {
            return [
                'label' => $label,
                'items' => $items,
            ];
        })->values();

        $offset = ($page - 1) * $perPage;

        return new LengthAwarePaginator(
            $flat->slice($offset, $perPage)->values(),
            $flat->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );
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
