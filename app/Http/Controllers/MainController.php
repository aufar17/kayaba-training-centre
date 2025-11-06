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
    public function event()
    {
        $data = [
            'user' => $this->session(),
        ];
        return view('event', $data);
    }
}
