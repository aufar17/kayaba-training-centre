<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Login
Route::get('login', [MainController::class, 'login'])->name('login');
Route::get('register', [MainController::class, 'register'])->name('register');

//Main Features
Route::get('/', [MainController::class, 'index'])->name('index');
Route::get('shipping', [MainController::class, 'shipping'])->name('shipping');
