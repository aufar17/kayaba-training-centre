<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{

    public function session()
    {
        $user = Auth::user();
        return $user;
    }
    public function index()
    {
        $data = [
            'user' => $this->session(),
        ];
        return view('index', $data);
    }
    public function training()
    {
        $data = [
            'user' => $this->session(),
        ];
        return view('training', $data);
    }
    public function location()
    {
        $data = [
            'user' => $this->session(),
        ];
        return view('location', $data);
    }
    public function organizer()
    {
        $data = [
            'user' => $this->session(),
        ];
        return view('organizer', $data);
    }
    public function trainer()
    {
        $data = [
            'user' => $this->session(),
        ];
        return view('trainer', $data);
    }
    public function event()
    {
        $data = [
            'user' => $this->session(),
        ];
        return view('event', $data);
    }
    public function history()
    {
        $data = [
            'user' => $this->session(),
        ];
        return view('history', $data);
    }
    public function notification()
    {
        $data = [
            'user' => $this->session(),
        ];
        return view('notification', $data);
    }
}
