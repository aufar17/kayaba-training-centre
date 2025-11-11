<?php

namespace App\Interfaces\ServiceInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface EventServiceInterface
{
    public function getAll(): Collection;
    public function getById(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;
    public function getNowEvent(): Collection;
    public function getUpcomingEvent(): Collection;
    public function getPastEvent(): Collection;
    public function getParticipants($id);
    public function getParticipantbyDept($id);
    public function getHistoryApprovalbyDept($id);
    public function countApprovalByEvent($participants);
    public function deleteParticipant($id);
    public function registerParticipant($data);
    public function approvalParticipant($data);
    public function completedParticipant($data);
    public function historyParticipant($npk);
}
