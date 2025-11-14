<?php

namespace App\Interfaces\ServiceInterface;

interface NotificationServiceInterface
{
    public function model();

    public function eventCreateNotification($user, $role);

    public function registerNotificationForManager($role);
    public function registeredParticipantNotification($user);

    public function deptApprovalNotification($user, $role);

    public function hrdApprovalNotification($role);
    public function fixedParticipantNotification($user);

    public function deptReportNotification($user);
    public function participantReportNotification($user);
}
