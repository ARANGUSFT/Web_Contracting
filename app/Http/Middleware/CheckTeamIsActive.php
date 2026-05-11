<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTeamIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('team')->user();

        if ($user && !$user->is_active) {
            Auth::guard('team')->logout();

            return redirect()->route('team.login')->withErrors([
                'email' => 'Your account has been deactivated by an administrator.',
            ]);
        }

        return $next($request);
    }
}
