<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\NotificationServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

class Notifications extends Component
{
    public $user;
    public $role;
    public $page;
    public $perPage = 20;

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected NotificationServiceInterface $service;

    public function mount()
    {
        $this->service = app(NotificationServiceInterface::class);
        $this->role = $this->user->getUserRole();
    }

    public function render()
    {
        $notifications = $this->paginateNotifications();

        return view('livewire.notifications', [
            'notifications' => $notifications,
        ]);
    }


    public function setPage($page)
    {
        $this->page = $page;
    }

    protected function paginateNotifications(): LengthAwarePaginator
    {
        $collection = $this->getAllNotifications();

        $currentPage = $this->page;
        $offset = ($currentPage - 1) * $this->perPage;

        return new LengthAwarePaginator(
            $collection->slice($offset, $this->perPage)->values(),
            $collection->count(),
            $this->perPage,
            $currentPage,
            ['path' => request()->url()]
        );
    }


    protected function getAllNotifications(): Collection
    {

        $service = $this->service ?? app(NotificationServiceInterface::class);
        $user = $this->user;
        $role = $this->role;

        return collect([
            $service->eventCreateNotification($user, $role),
            $service->registerNotificationForManager($role, $user),
            $service->registeredParticipantNotification($user),
            $service->deptApprovalNotification($user, $role),
            $service->hrdApprovalNotification($role, $user),
            $service->fixedParticipantNotification($user),
            $service->deptReportNotification($role, $user),
            $service->participantReportNotification($user),
        ])
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
            ],
            'register' => [
                'icon' => 'fa-solid fa-user-plus',
                'color' => 'warning',
            ],
            'registered_participant' => [
                'icon' => 'fa-solid fa-user-plus',
                'color' => 'warning',
            ],
            'dept_approval' => [
                'icon' => 'fa-solid fa-user-check',
                'color' => 'success',
            ],
            'hrd_approval' => [
                'icon' => 'fa-solid fa-clipboard-check',
                'color' => 'success',
            ],
            'fixed_participant' => [
                'icon' => 'fa-solid fa-clipboard-check',
                'color' => 'success',
            ],
            'report' => [
                'icon' => 'fa-solid fa-file-lines',
                'color' => 'warning',
            ],

            'participant_completed' => [
                'icon' => 'fa-solid fa-file-signature',
                'color' => 'primary',
            ],

            'participant_not_completed' => [
                'icon' => 'fa-solid fa-file-signature',
                'color' => 'primary',
            ],
            'default' => [
                'icon' => 'fa-solid fa-info-circle',
                'color' => 'secondary',
            ],
        ];

        return array_merge($notif->toArray(), [
            'time'  => $displayTime,
            'icon'  => $map[$type]['icon'] ?? $map['default']['icon'],
            'color' => $map[$type]['color'] ?? $map['default']['color'],
        ]);
    }
}
