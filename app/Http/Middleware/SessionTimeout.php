<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    /**
     * Minutos de inactividad antes de cerrar sesión.
     * Puedes moverlo a config/session.php o .env si prefieres.
     */
    const TIMEOUT_MINUTES = 60;

    public function handle(Request $request, Closure $next)
    {
        // Solo aplica si hay alguien autenticado (cualquier guard)
        $isAuthenticated = Auth::guard('web')->check()
            || Auth::guard('team')->check();

        if (!$isAuthenticated) {
            return $next($request);
        }

        $lastActivity = session('last_activity_at');

        if ($lastActivity) {
            $inactiveSeconds = time() - $lastActivity;

            if ($inactiveSeconds > (self::TIMEOUT_MINUTES * 60)) {
                // Cierra todos los guards activos
                $this->logoutAll($request);

                // Responde según el tipo de request
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Session expired due to inactivity.',
                    ], 401);
                }

                // Redirige al login correcto según la URL
                return redirect($this->resolveLoginUrl($request))
                    ->with('warning', 'Your session expired due to inactivity. Please log in again.');
            }
        }

        // Actualiza el timestamp en cada request
        session(['last_activity_at' => time()]);

        return $next($request);
    }

    private function logoutAll(Request $request): void
    {
        foreach (['web', 'team'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    private function resolveLoginUrl(Request $request): string
    {
        $path = $request->path();

        if (str_starts_with($path, 'superadmin')) {
            return route('superadmin.login');
        }

        if (str_starts_with($path, 'seller')
            || str_starts_with($path, 'guest')
            || str_starts_with($path, 'manager')
            || str_starts_with($path, 'crew')
            || str_starts_with($path, 'project')
            || str_starts_with($path, 'admin')) {
            return route('team.login');
        }

        return route('login');
    }
}