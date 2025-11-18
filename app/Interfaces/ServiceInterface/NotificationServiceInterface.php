<?php

namespace App\Interfaces\ServiceInterface;

interface NotificationServiceInterface
{
    public function getAll();
    public function getById($id);

    public function eventCreateNotification($user, $role);

    public function registerNotificationForManager($role, $user);
    public function registeredParticipantNotification($user);

    public function deptApprovalNotification($user, $role);

    public function hrdApprovalNotification($role, $user);
    public function fixedParticipantNotification($user);

    public function deptReportNotification($user, $role);
    public function participantReportNotification($user);
}
