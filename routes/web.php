<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'login'])->name('login');
Route::get('captcha', [LoginController::class, 'captcha'])->name('captcha');
Route::get('otp', [OtpController::class, 'otpVerif'])->name('otp-verif');

Route::post('verify-otp', [OtpController::class, 'verify'])->name('verify-otp');
Route::post('resend-otp', [OtpController::class, 'resendOtp'])->name('resend-otp');

//MAIN FEATURES
Route::get('/', [MainController::class, 'index'])->name('index');
Route::get('training', [MainController::class, 'training'])->name('training');
Route::get('event', [MainController::class, 'event'])->name('event');




require __DIR__ . '/auth.php';
