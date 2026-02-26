<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Solo aplicar a usuarios normales (no admins)
        if ($user && !$user->is_admin) {

            // Si no está aprobado
            if (is_null($user->approved_at)) {
                Auth::logout();

                return redirect()->route('login')
                    ->with('error', 'Your account is pending admin approval.');
            }

            // Si está desactivado
            if (!$user->is_active) {
                Auth::logout();

                return redirect()->route('login')
                    ->with('error', 'Your account has been deactivated.');
            }
        }

        return $next($request);
    }
}