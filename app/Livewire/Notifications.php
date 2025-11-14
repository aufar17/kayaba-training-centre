<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\NotificationServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class Notifications extends Component
{
    public $user;
    public $role;
    protected NotificationServiceInterface $service;

    public function mount()
    {
        $this->service = app(NotificationServiceInterface::class);
        $this->role = $this->checkRole();
    }

    public function render()
    {
        $notifications = $this->getAllNotifications()
            ->map(fn($notif) => $this->mapping($notif));

        return view('livewire.notifications', [
            'notifications' => $notifications,
        ]);
    }

    public function checkRole()
    {
        $user = $this->user;
        $role = $this->user->getUserRole();
        return $role;
    }

    protected function getAllNotifications(): Collection
    {
        $service = $this->service;
        $user = $this->user;
        $role = $this->role;

        $notifications = collect([
            $service->eventCreateNotification($user, $role),
            $service->registerNotificationForManager($role),
            $service->registeredParticipantNotification($user),
            $service->deptApprovalNotification($user, $role),
            $service->hrdApprovalNotification($role),
            $service->fixedParticipantNotification($user),
            $service->deptReportNotification($role),
            $service->participantReportNotification($user),
        ]);

        return $notifications
            ->filter()
            ->flatten()
            ->sortByDesc('created_at')
            ->values();
    }

    protected function mapping($notif): array
    {
        $createdAt = $notif->created_at instanceof Carbon
            ? $notif->created_at
            : Carbon::parse($notif->created_at);

        $displayTime = $createdAt->diffInDays(now()) < 7
            ? $createdAt->diffForHumans()
            : $createdAt->format('d M Y, H:i');
        $type = $notif->type ?? 'default';

        $map = [
            'event' => [
                'icon' => 'fa-solid fa-bell',
                'color' => 'info',
                'url' => 'event-participant',
            ],
            'register' => [
                'icon' => 'fa-solid fa-user-plus',
                'color' => 'warning',
                'url' => 'event-participant',
            ],
            'registered_participant' => [
                'icon' => 'fa-solid fa-user-plus',
                'color' => 'warning',
                'url' => 'event-participant',
            ],
            'dept_approval' => [
                'icon' => 'fa-solid fa-user-check',
                'color' => 'success',
                'url' => 'event-participant',
            ],
            'hrd_approval' => [
                'icon' => 'fa-solid fa-clipboard-check',
                'color' => 'success',
                'url' => 'event-participant',
            ],
            'fixed_participant' => [
                'icon' => 'fa-solid fa-clipboard-check',
                'color' => 'success',
                'url' => 'event-participant',
            ],
            'report' => [
                'icon' => 'fa-solid fa-file-lines',
                'color' => 'warning',
                'url' => 'event-participant',
            ],
            'participant_report' => [
                'icon' => 'fa-solid fa-file-signature',
                'color' => 'primary',
                'url' => 'event-participant',
            ],
            'default' => [
                'icon' => 'fa-solid fa-info-circle',
                'color' => 'secondary',
                'url' => 'event-participant',
            ],
        ];

        return array_merge($notif->toArray(), [
            'time' => $notif->created_at->diffForHumans(),
            'icon' => $map[$type]['icon'] ?? $map['default']['icon'],
            'color' => $map[$type]['color'] ?? $map['default']['color'],
            'url' => $map[$type]['url'] ?? $map['default']['url'],
        ]);
    }
}
