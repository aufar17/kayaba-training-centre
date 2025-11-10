<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckHRD
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $hrdOnlyRoutes = [
            'training',
            'location',
            'organizer',
            'trainer',
        ];

        if ($request->routeIs($hrdOnlyRoutes) && $user->dept !== 'HRD') {
            abort(403, 'Access denied: Only HR Department can access this page.');
        }

        return $next($request);
    }
}
