<?php

namespace App\Services;

use App\Interfaces\RepositoryInterface\EventRepositoryInterface;
use App\Interfaces\ServiceInterface\NotificationServiceInterface;
use App\Models\Location;
use App\Models\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class NotificationService implements NotificationServiceInterface
{
    protected EventRepositoryInterface $repository;
    public function __construct(
        EventRepositoryInterface $repository,
    ) {
        $this->repository = $repository;
    }

    public function model()
    {
        return Notification::query();
    }

    protected function getRegisteredEventId($user)
    {
        return $this->repository->participantModel()
            ->where('npk', $user->npk)
            ->pluck('event_id')
            ->toArray();
    }

    public function eventCreateNotification($user, $role)
    {
        if (in_array($role, ['spv', 'manager']) || $user->dept === 'HRD') {
            return $this->model()->where('type', 'event')->get();
        }
        return collect();
    }

    public function registerNotificationForManager($role)
    {
        if ($role === 'manager') {
            return $this->model()
                ->where('type', 'register')
                ->orderByDesc('created_at')
                ->get();
        }
    }

    public function registeredParticipantNotification($user)
    {
        $registeredEventIds = $this->getRegisteredEventId($user);

        if (empty($registeredEventIds)) {
            return collect();
        }

        return $this->model()
            ->where('type', 'registered_participant')
            ->whereIn('event_id', $registeredEventIds)
            ->orderByDesc('created_at')
            ->get();
    }

    public function deptApprovalNotification($user, $role)
    {
        if ($user->dept === 'HRD') {
            return $this->model()
                ->where('type', 'dept_approval')
                ->orderByDesc('created_at')
                ->get();
        }
        return collect();
    }

    public function hrdApprovalNotification($role)
    {
        if ($role === 'manager') {
            return $this->model()
                ->where('type', 'hrd_approval')
                ->orderByDesc('created_at')
                ->get();
        }
    }

    public function fixedParticipantNotification($user)
    {
        $registeredEventIds = $this->getRegisteredEventId($user);

        if (empty($registeredEventIds)) {
            return collect();
        }

        return $this->model()
            ->where('type', 'fixed_participant')
            ->whereIn('event_id', $registeredEventIds)
            ->orderByDesc('created_at')
            ->get();
    }

    public function deptReportNotification($role)
    {
        if ($role === 'manager') {
            return $this->model()
                ->where('type', 'report')
                ->orderByDesc('created_at')
                ->get();
        }
    }

    public function participantReportNotification($user)
    {
        $registeredEventIds = $this->getRegisteredEventId($user);

        if (empty($registeredEventIds)) {
            return collect();
        }

        return $this->model()
            ->where('type', 'participant_report')
            ->whereIn('event_id', $registeredEventIds)
            ->orderByDesc('created_at')
            ->get();
    }
}
