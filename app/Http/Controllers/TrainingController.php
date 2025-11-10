<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{


    public function session()
    {
        $user = Auth::user();
        return $user;
    }

    public function trainingContent($id)
    {
        $data = [
            'user' => $this->session(),
            'id' => $id
        ];

        return view('training-content', $data);
    }
    public function eventParticipant($id)
    {
        $data = [
            'user' => $this->session(),
            'id' => $id
        ];

        return view('event-participant', $data);
    }
}
