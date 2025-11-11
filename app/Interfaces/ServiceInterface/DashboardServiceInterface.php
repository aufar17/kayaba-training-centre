<?php

namespace App\Interfaces\ServiceInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface DashboardServiceInterface
{
    public function countEmployee();
    public function countTraining();
    public function countParticipantComplete();
    public function countParticipantNotCompleted();
}
