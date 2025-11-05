<?php

namespace App\Http\Controllers;

use App\Models\Auth\Hp;
use App\Models\Auth\OtpVerification;
use App\Services\Auth\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function otpVerif()
    {
        $otpService = new OtpService;
        $otpData = $otpService->otpVerif();

        if ($otpData instanceof RedirectResponse) {
            return $otpData;
        }

        return view('authentication.otp-verification', $otpData);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login')->withErrors(['error' => 'Session expired. Silakan login kembali.']);
        }

        $otp = OtpVerification::where('npk', $user->npk)
            ->where('expiry_date', '>=', now())
            ->latest('created_at')
            ->first();

        if (! $otp || $otp->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.']);
        }

        session(['otp_verified' => true]);
        session()->save();

        $otp->delete();

        return redirect()->route('index')->with('success', 'Selamat Datang!');
    }

    public function resendOtp()
    {
        $user = Auth::user();

        $hp = Hp::where('npk', $user->npk)->first()->no_hp;

        $expiredOtp = OtpVerification::where('npk', $user->npk)
            ->where('expiry_date', '<', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($expiredOtp) {
            $expiredOtp->update([
                'otp' => rand(100000, 999999),
                'hp' => $hp,
                'expiry_date' => Carbon::now()->addMinutes(5),
                'send' => 'queue',
                'send_date' => null,
                'use' => false,
                'use_date' => null,
            ]);

            $otp = $expiredOtp;
        } else {
            $otp = OtpVerification::create([
                'npk' => $user->npk,
                'otp' => rand(100000, 999999),
                'hp' => $hp,
                'expiry_date' => Carbon::now()->addMinutes(5),
                'send' => 'queue',
                'send_date' => null,
                'use' => false,
                'use_date' => null,
            ]);
        }

        session([
            'otp_required' => true,
            'otp_expiry' => $otp->expiry_date,
        ]);

        return redirect()->route('otp-verif')->with('message', 'OTP berhasil dikirim ulang.');
    }
}
