<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\EventImportController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'login'])->name('login');
Route::get('captcha', [LoginController::class, 'captcha'])->name('captcha');
Route::get('otp', [OtpController::class, 'otpVerif'])->name('otp-verif');

Route::post('verify-otp', [OtpController::class, 'verify'])->name('verify-otp');
Route::post('resend-otp', [OtpController::class, 'resendOtp'])->name('resend-otp');

//MAIN FEATURES
Route::get('/', [MainController::class, 'index'])->name('index');
Route::get('training', [MainController::class, 'training'])->name('training');
Route::get('location', [MainController::class, 'location'])->name('location');
Route::get('organizer', [MainController::class, 'organizer'])->name('organizer');
Route::get('trainer', [MainController::class, 'trainer'])->name('trainer');
Route::get('event', [MainController::class, 'event'])->name('event');
Route::get('history', [MainController::class, 'history'])->name('history');
Route::get('notification', [MainController::class, 'notification'])->name('notification');

//TRAINING FEATURES
Route::get('training-content/{id}', [TrainingController::class, 'trainingContent'])->name('training-content');
Route::get('event-participant/{id}', [TrainingController::class, 'eventParticipant'])->name('event-participant');
Route::get('participant-history/{npk}', [TrainingController::class, 'participantHistory'])->name('participant-history');


Route::post('event-import', [EventController::class, 'eventImport'])->name('event-import');
Route::get('presence-export/{id}', [EventController::class, 'presenceExport'])->name('presence-export');




require __DIR__ . '/auth.php';
