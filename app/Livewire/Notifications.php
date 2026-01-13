<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\NotificationService;
use Illuminate\Pagination\LengthAwarePaginator;

class Notifications extends Component
{
    use WithPagination;

    public $user;
    public $role;
    public $perPage = 20;
    public $page;

    protected $paginationTheme = 'bootstrap';
    protected NotificationService $notificationService;

    public function mount($user)
    {
        $this->user = $user;
        $this->role = $user->getUserRole();
        $this->notificationService = app(NotificationService::class);
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function render()
    {
        $notifications = $this->notificationService->getAllForUser($this->user, $this->role);

        $mapped = $this->notificationService->mapNotifications($notifications);

        $grouped = $this->notificationService->groupByDateLabel($mapped);

        $paginated = $this->notificationService->paginateGroupedNotifications(
            $grouped,
            $this->page ?? 1,
            $this->perPage
        );

        $data = [
            'notifications' => $paginated,
        ];
        return view('livewire.notifications', $data);
    }
}
