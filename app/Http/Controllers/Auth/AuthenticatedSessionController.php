<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Auth\CTUser;
use App\Models\Auth\Hp;
use App\Models\Auth\OtpVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $user = CTUser::where('npk', $request->npk)->first();

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.')->withInput($request->only('npk'));
        }
        if ($user->dept != "EHS") {
            return back()->with('error', 'Maaf departemen anda bukan EHS.')->withInput($request->only('npk'));
        }
        if ($user && Hash::check($request->password, $user->pwd)) {
            $hp = Hp::where('npk', $user->npk)->first();

            if (!$hp) {
                return back()->with('error', 'Tidak ada daftar nomor HP.')->withInput($request->only('npk'));
            }

            Auth::login($user);
            // $otp = rand(100000, 999999);
            $otp = 123456;
            session(['otp_code' => $otp, 'otp_verified' => false]);

            $existingOtp = OtpVerification::where('npk', $user->npk)
                ->where('expiry_date', '<', now())
                ->where('use', false)
                ->latest()
                ->first();

            if ($existingOtp) {
                $existingOtp->update([
                    'otp' => $otp,
                    'hp' => $hp->no_hp,
                    'expiry_date' => now()->addMinutes(5),
                    'send' => 'queue',
                    'send_date' => null,
                    'use' => false,
                    'use_date' => null,
                ]);
            } else {
                OtpVerification::create([
                    'npk'    => $user->npk,
                    'otp'        => $otp,
                    'hp'         => $hp->no_hp,
                    'expiry_date' => now()->addMinutes(5),
                    'send'       => 'queue',
                    'send_date'  => null,
                    'use'        => false,
                    'use_date'   => null,
                ]);
            }

            return redirect()->route('otp-verif');
        }

        return back()->with('error', 'NPK atau password salah.')->withInput($request->only('npk'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
