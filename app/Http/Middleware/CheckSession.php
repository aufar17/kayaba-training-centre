<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        if ($request->is('captcha')) {
            return $next($request);
        }

        if (in_array($routeName, ['captcha'])) {
            return $next($request);
        }


        // Cek user login
        if (!Auth::check()) {
            if (!in_array($routeName, ['login', 'login-post'])) {
                return redirect()->route('login')->withErrors([
                    'auth' => 'Anda harus login terlebih dahulu.',
                ]);
            }

            return $this->setNoCacheHeaders($next($request));
        }

        $user = Auth::user();
        if (session('otp_verified') !== true) {
            $otp = $user->otp()->latest()->first();

            if (!in_array($routeName, ['resend-otp']) && $otp && $otp->expiry_date < now()) {
                Auth::logout();
                session()->flush();

                return redirect()->route('login')->withErrors([
                    'otp' => 'OTP telah kadaluarsa. Silakan login ulang.',
                ]);
            }

            if (!in_array($routeName, ['otp-verif', 'verify-otp', 'logout', 'resend-otp'])) {
                return redirect()->route('otp-verif');
            }

            return $this->setNoCacheHeaders($next($request));
        }

        // Ini hanya dieksekusi jika sudah OTP verified
        if (in_array($routeName, ['login', 'login-post', 'otp-verif', 'verify-otp', 'resend-otp'])) {
            return redirect()->route('index');
        }

        return $this->setNoCacheHeaders($next($request));
    }

    /**
     * Tambah header untuk mencegah halaman disimpan di cache
     */
    protected function setNoCacheHeaders($response): Response
    {
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }
}
