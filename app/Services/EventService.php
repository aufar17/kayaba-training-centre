<?php

namespace App\Services;

use App\Interfaces\RepositoryInterface\EventRepositoryInterface;
use App\Interfaces\RepositoryInterface\TrainingRepositoryInterface;
use App\Interfaces\ServiceInterface\EventServiceInterface;
use App\Interfaces\ServiceInterface\LocationServiceInterface;
use App\Interfaces\ServiceInterface\OrganizerServiceInterface;
use App\Interfaces\ServiceInterface\TrainerServiceInterface;
use App\Interfaces\ServiceInterface\TrainingServiceInterface;
use App\Models\Auth\CTUser;
use App\Models\Event;
use App\Models\EventTransaction;
use App\Models\MatrixTraining;
use App\Models\Notification;
use App\Models\NotificationTransaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventService implements EventServiceInterface
{
    protected EventRepositoryInterface $repository;
    protected TrainingServiceInterface $trainingService;
    protected OrganizerServiceInterface $organizerService;
    protected TrainerServiceInterface $trainerService;
    protected LocationServiceInterface $locationService;

    public function __construct(
        EventRepositoryInterface $repository,
        TrainingServiceInterface $trainingService,
        OrganizerServiceInterface $organizerService,
        TrainerServiceInterface $trainerService,
        LocationServiceInterface $locationService
    ) {
        $this->repository = $repository;
        $this->trainingService = $trainingService;
        $this->organizerService = $organizerService;
        $this->trainerService = $trainerService;
        $this->locationService = $locationService;
    }

    public function getAll(): Collection
    {
        return $this->repository
            ->getModel()
            ->orderBy('code', 'desc')
            ->get();
    }
    public function getById(int $id)
    {
        $event = $this->repository->find($id);

        if (!$event) {
            return null;
        }

        $now = now();

        if ($event->start_date <= $now && $event->end_date >= $now) {
            $event->status = 'ongoing';
        } elseif ($event->start_date > $now) {
            $event->status = 'upcoming';
        } else {
            $event->status = 'past';
        }

        return $event;
    }


    public function create(array $data)
    {
        DB::beginTransaction();

        try {

            if (($data['training'] ?? null) === 'other' && !empty($data['new_training']['name'])) {
                $trainingData = [
                    'code' => $data['new_training']['code'] ?? 'TR-' . strtoupper(str()->random(4)),
                    'name' => $data['new_training']['name'],
                    'desc' => $data['new_training']['desc'] ?? null,
                    'purpose' => $data['new_training']['purpose'] ?? null,
                    'day_duration' => $data['new_training']['day_duration'] ?? null,
                    'time_duration' => $data['new_training']['time_duration'] ?? null,
                ];

                $training = $this->trainingService->create($trainingData);
                $data['training_id'] = $training->id;
            } else {
                $data['training_id'] = $data['training'];
            }

            if (($data['organizer'] ?? null) === 'other' && !empty($data['new_organizer']['name'])) {
                $organizer = $this->organizerService->create($data['new_organizer']);
                $data['organizer_id'] = $organizer->id;
            } else {
                $data['organizer_id'] = $data['organizer'];
            }

            if (($data['trainer'] ?? null) === 'other' && !empty($data['new_trainer']['name'])) {
                $trainer = $this->trainerService->create($data['new_trainer']);
                $data['trainer_id'] = $trainer->id;
            } else {
                $data['trainer_id'] = $data['trainer'];
            }

            if (($data['location'] ?? null) === 'other' && !empty($data['new_location']['name'])) {
                $location = $this->locationService->create($data['new_location']);
                $data['location_id'] = $location->id;
            } else {
                $data['location_id'] = $data['location'];
            }


            $create = Event::create([
                'code' => $data['code'],
                'training_id' => $data['training_id'],
                'location_id' => $data['location_id'],
                'organizer_id' => $data['organizer_id'],
                'trainer_id' => $data['trainer_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
            ]);


            $event = Event::where('id', $create->id)->with(['trainings', 'locations', 'organizers', 'trainers'])->first();
            $targets = MatrixTraining::where('training_code', $event->trainings->code)->get();

            $notification = Notification::create([
                'event_id' => $event->code,
                'type' => 'event',
                'title' => 'New Training Event Has Been Created',
                'description' => 'A new training event titled  <b>"' . $event->trainings->name . '"</b> has been created and is awaiting participant registration.'
            ]);

            foreach ($targets as $target) {

                $notif_trx = NotificationTransaction::create(
                    [
                        'notification_id' => $notification->id,
                        'target' => $target->dept,
                        'is_read' => 0,
                    ]
                );
            }


            DB::commit();
            return $event;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();

        try {
            if (($data['training'] ?? null) === 'other' && !empty($data['new_training']['name'])) {
                $trainingData = [
                    'code' => $data['new_training']['code'] ?? 'TR-' . strtoupper(str()->random(4)),
                    'name' => $data['new_training']['name'],
                    'desc' => $data['new_training']['desc'] ?? null,
                    'purpose' => $data['new_training']['purpose'] ?? null,
                    'day_duration' => $data['new_training']['day_duration'] ?? null,
                    'time_duration' => $data['new_training']['time_duration'] ?? null,
                ];

                $training = $this->trainingService->create($trainingData);
                $trainingId = $training->id;
            } else {
                $trainingId = $data['training'] ?? null;
            }

            if (($data['organizer'] ?? null) === 'other' && !empty($data['new_organizer']['name'])) {
                $organizerData = [
                    'code' => $data['new_organizer']['code'] ?? 'ORG-' . strtoupper(str()->random(4)),
                    'name' => $data['new_organizer']['name'],
                ];

                $organizer = $this->organizerService->create($organizerData);
                $organizerId = $organizer->id;
            } else {
                $organizerId = $data['organizer'] ?? null;
            }

            if (($data['trainer'] ?? null) === 'other' && !empty($data['new_trainer']['name'])) {
                $trainerData = [
                    'code' => $data['new_trainer']['code'] ?? 'TRN-' . strtoupper(str()->random(4)),
                    'name' => $data['new_trainer']['name'],
                ];

                $trainer = $this->trainerService->create($trainerData);
                $trainerId = $trainer->id;
            } else {
                $trainerId = $data['trainer'] ?? null;
            }

            if (($data['location'] ?? null) === 'other' && !empty($data['new_location']['name'])) {
                $locationData = [
                    'code' => $data['new_location']['code'] ?? 'LOC-' . strtoupper(str()->random(4)),
                    'name' => $data['new_location']['name'],
                ];

                $location = $this->locationService->create($locationData);
                $locationId = $location->id;
            } else {
                $locationId = $data['location'] ?? null;
            }

            $event = Event::findOrFail($id);
            $event->update([
                'code' => $data['code'] ?? $event->code,
                'training_id' => $trainingId,
                'organizer_id' => $organizerId,
                'trainer_id' => $trainerId,
                'location_id' => $locationId,
                'start_date' => $data['start_date'] ?? $event->start_date,
                'end_date' => $data['end_date'] ?? $event->end_date,
                'start_time' => $data['start_time'] ?? $event->start_time,
                'end_time' => $data['end_time'] ?? $event->end_time,
            ]);

            $notifications = Notification::where('event_id', $event->id)->where('type', 'event')->get();
            $departments = $event->trainings->matrix->pluck('dept')->toArray();

            foreach ($notifications as $notif) {
                $notif->update([
                    'title' => 'Training Event Updated',
                    'description' => 'The training event titled <b>"' . $event->trainings->name . '"</b> has been updated.',
                ]);

                $existingTrx = $notif->transactions()->pluck('target')->toArray();

                foreach ($departments as $dept) {
                    if (!in_array($dept, $existingTrx)) {
                        $notif->transactions()->create([
                            'target' => $dept,
                            'is_read' => 0,
                        ]);
                    } else {
                        $notif->transactions()->where('target', $dept)->update(['is_read' => 0]);
                    }
                }

                $notif->transactions()->whereNotIn('target', $departments)->delete();
            }



            DB::commit();

            return $event;
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            throw $e;
        }
    }


    public function delete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $event = Event::findOrFail($id);
            $deleted = $event->delete();

            DB::commit();
            return $deleted;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getLatestNowEvent()
    {
        $today = today();

        return $this->repository
            ->getModel()
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderByDesc('start_date')
            ->first();
    }


    public function getLatestUpcomingEvent()
    {
        $today = today();

        return $this->repository
            ->getModel()
            ->whereDate('start_date', '>', $today)
            ->orderByDesc('start_date')
            ->first();
    }

    public function getNowEvent($dept)
    {
        $today = today();

        return $this->repository
            ->filterByMatrixDepartment($dept)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderByDesc('start_date')
            ->get();
    }


    public function getUpcomingEvent($dept)
    {
        $today = today();

        return $this->repository
            ->filterByMatrixDepartment($dept)
            ->whereDate('start_date', '>', $today)
            ->orderByDesc('start_date')
            ->get();
    }


    public function getPastEvent($dept)
    {
        $today = today();

        return $this->repository
            ->filterByMatrixDepartment($dept)
            ->whereDate('end_date', '<', $today)

            ->orderByDesc('start_date')
            ->paginate(25);
    }



    public function getParticipants($id)
    {
        $event = $this->repository->find($id);
        $query = $this->repository
            ->participantModel()
            ->with(['event', 'user'])
            ->where('event_id', $event->code)
            ->where('approval', '>', 0);

        return $query->get();
    }

    public function getParticipantbyDept($id)
    {
        $dept = Auth::user()->dept;
        $event = $this->repository->find($id);

        $participants = $this->repository
            ->participantModel()
            ->with(['event', 'user'])
            ->where('event_id', $event->code)
            ->where('approval', '>=', 0)
            ->get();

        return $participants->where('user.dept', $dept);
    }
    public function getHistoryApprovalbyDept($id)
    {
        $dept = Auth::user()->dept;
        $event = $this->repository->find($id);


        $participants = $this->repository
            ->participantModel()
            ->with(['event', 'user'])
            ->where('event_id', $event->code)
            ->get();

        if ($dept == 'HRD') {
            return $participants->filter(function ($participant) {
                return in_array($participant->approval, [2, -2]);
            });
        } else {
            return $participants->where('user.dept', $dept);
        }
    }


    public function countApprovalByEvent($participants)
    {
        $approvedHrd = $participants->where('approval', 2)->count();
        $approvedManager = $participants->where('approval', 1)->count();
        $rejectedHrd = $participants->where('approval', -2)->count();
        $rejectedManager = $participants->where('approval', -1)->count();
        $total  = $participants->count();
        return [
            'approvedHrd' => $approvedHrd,
            'approvedManager' => $approvedManager,
            'rejectedHrd' => $rejectedHrd,
            'rejectedManager' => $rejectedManager,
            'total'  => $total,
        ];
    }


    public function registerParticipant($data)
    {
        DB::beginTransaction();

        try {
            $code = Event::findOrFail($data['event_id'])->code;
            $participant = EventTransaction::create([
                'event_id' => $code,
                'npk' => $data['npk'],
                'approval' => 0,
                'completed' => 0,
            ]);
            DB::commit();
            return $participant;
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            throw $e;
        }
    }

    public function registerParticipantbyHrd($eventId, array $participants)
    {

        DB::beginTransaction();

        try {
            foreach ($participants as $p) {
                EventTransaction::firstOrCreate([
                    'event_id' => $eventId,
                    'npk' => $p['npk'],
                ], [
                    'approval' => 2,
                    'completed' => 0,
                ]);
            }

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            return false;
        }
    }


    public function deleteParticipant($id)
    {
        DB::beginTransaction();

        try {
            $participant = EventTransaction::findOrFail($id);
            $participant->delete();
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function approvalParticipant($data)
    {
        DB::beginTransaction();

        try {
            $participant = $this->repository
                ->participantModel()
                ->where('id', $data['id'])
                ->first();

            $participant->update([
                'approval' => $data['approval'],
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function completedParticipant($data)
    {
        DB::beginTransaction();
        try {
            $participant = $this->repository
                ->participantModel()
                ->where('id', $data['id'])
                ->first();

            $participant->update([
                'completed' => $data['completed'],
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function historyParticipant($npk)
    {
        return $this->repository
            ->participantModel()
            ->with(['event.trainings', 'user'])
            ->where('npk', $npk)
            ->where('approval', '=', 2)
            ->where('completed', '=', 1)
            ->get();
    }

    public function registerNotification($id, $user)
    {
        DB::beginTransaction();

        try {
            $event = $this->getById($id);

            $dept = $user->dept;

            $notifications = [];

            $notifications[] = Notification::create([
                'event_id' => $event->code,
                'type' => 'register',
                'title' => 'New Participants Have Been Registered by Admin',
                'description' => 'Participants for the training <b>"' . $event->trainings->name . '"</b> have been registered by the admin. Please review and provide your approval as Department Head.',
            ]);

            NotificationTransaction::create([
                'notification_id' => $notifications[0]->id,
                'target' => $dept,
                'is_read' => 0,
            ]);

            $notifications[] = Notification::create([
                'event_id' => $event->code,
                'type' => 'registered_participant',
                'title' => 'You Have Been Registered for Training',
                'description' => 'You have been successfully registered as a participant in the training <b>"' . $event->trainings->name . '"</b>.',
            ]);

            NotificationTransaction::create([
                'notification_id' => $notifications[1]->id,
                'target' => $dept,
                'is_read' => 0,
            ]);

            DB::commit();

            return $notifications;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deptApprovalNotification($id)
    {
        DB::beginTransaction();

        try {
            $event = $this->getById($id);

            $availableApproval = $event->transactions()
                ->where('approval', 1)
                ->pluck('npk')
                ->unique()
                ->values();

            if ($availableApproval->isEmpty()) {
                session()->flash('error', 'Please approve all participants please!');
                return false;
            }


            $notification = Notification::create([
                'event_id' => $event->code,
                'type' => 'dept_approval',
                'title' => 'The Participants Have Been Approved by Department Head',
                'description' => 'Participants for the training  <b>"' . $event->trainings->name . '"</b>  have been approved by the department head. Please review and provide your approval as HRD.',
            ]);

            DB::commit();
            return $notification;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function hrdApprovalNotification($id)
    {
        DB::beginTransaction();

        try {
            $event = $this->getById($id);

            $availableApproval = $event->transactions()
                ->where('approval', 2)
                ->pluck('npk')
                ->unique()
                ->values();

            if ($availableApproval->isEmpty()) {
                session()->flash('error', 'Please approve all participants please!');
                return false;
            }

            $npks = $event->transactions()
                ->where('approval', 2)
                ->pluck('npk')
                ->unique()
                ->values();


            if ($npks->isEmpty()) {
                return false;
            }

            $participants = CTUser::whereIn('npk', $npks)->get(['npk', 'dept']);

            $departments = $participants->pluck('dept')
                ->filter()
                ->unique()
                ->values();

            $hrdApproval = Notification::create([
                'event_id' => $event->code,
                'type' => 'hrd_approval',
                'title' => 'Final Participant List Approved',
                'description' => 'The final participant list for the training <b>"'
                    . $event->trainings->name . '"</b> has been approved by HRD.',
            ]);

            $fixedParticipant = Notification::create([
                'event_id' => $event->code,
                'type' => 'fixed_participant',
                'title' => 'You Have Been Approved for Training',
                'description' => 'You have been approved to participate in the training <b>"'
                    . $event->trainings->name . '"</b>.',
            ]);

            $transactions = [];
            $participantTransactions = [];

            foreach ($departments as $dept) {
                $transactions[] = [
                    'notification_id' => $hrdApproval->id,
                    'target' => $dept,
                    'is_read' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $participantTransactions[] = [
                    'notification_id' => $fixedParticipant->id,
                    'target' => $dept,
                    'is_read' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($transactions)) {
                NotificationTransaction::insert($transactions);
            }
            if (!empty($participantTransactions)) {
                NotificationTransaction::insert($participantTransactions);
            }

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function reportNotification($id)
    {
        DB::beginTransaction();

        try {
            $event = $this->getById($id);

            $results = EventTransaction::where('event_id', $event->code)
                ->where('approval', 2)
                ->get();

            if ($results->isEmpty()) {
                return true;
            }

            $participants = CTUser::whereIn(
                'npk',
                $results->pluck('npk')->unique()
            )->get(['npk', 'dept']);

            $reportNotif = Notification::create([
                'event_id'    => $event->code,
                'type'        => 'report',
                'title'       => 'Training Result Report',
                'description' => 'HRD has submitted the result report for the training <b>"'
                    . $event->trainings->name . '"</b>.',
            ]);

            $deptTargets = $participants
                ->pluck('dept')
                ->filter()
                ->unique()
                ->values();

            $transactions = [];
            foreach ($deptTargets as $dept) {
                $transactions[] = [
                    'notification_id' => $reportNotif->id,
                    'target'          => $dept,
                    'is_read'         => 0,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }

            $grouped = $results->groupBy(function ($item) {
                return $item->completed == 1 ? 'completed' : 'not_completed';
            });

            foreach ($grouped as $status => $items) {

                $type = $status === 'completed'
                    ? 'participant_completed'
                    : 'participant_notcompleted';

                $label = $status === 'completed'
                    ? 'Completed'
                    : 'Not Completed';

                $notif = Notification::create([
                    'event_id'    => $event->code,
                    'type'        => $type,
                    'title'       => 'Your Training Result Report',
                    'description' => "Your result for the training <b>\"{$event->trainings->name}\"</b>: {$label}",
                ]);

                $deptTargets = $participants
                    ->whereIn('npk', $items->pluck('npk'))
                    ->pluck('dept')
                    ->filter()
                    ->unique()
                    ->values();

                foreach ($deptTargets as $dept) {
                    $transactions[] = [
                        'notification_id' => $notif->id,
                        'target'          => $dept,
                        'is_read'         => 0,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }
            }

            NotificationTransaction::insert($transactions);

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
