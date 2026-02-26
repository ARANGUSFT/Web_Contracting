<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(LoginRequest $request): RedirectResponse
{
    $user = \App\Models\User::where('email', $request->email)->first();

    // 🔒 Usuario no existe
    if (!$user) {
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    // 🔥 BLOQUEO POR APROBACIÓN
    if (is_null($user->approved_at)) {
        return back()->withErrors([
            'email' => 'Your account is pending admin approval.',
        ]);
    }

    // 🔥 BLOQUEO POR DESACTIVACIÓN
    if (!$user->is_active) {
        return back()->withErrors([
            'email' => 'Your account has been deactivated.',
        ]);
    }

    // ✅ Si pasa validaciones normales de Breeze
    $request->authenticate();

    $request->session()->regenerate();

    return redirect()->intended(RouteServiceProvider::HOME);
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
