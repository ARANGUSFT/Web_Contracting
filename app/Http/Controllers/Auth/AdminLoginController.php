<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.superadmin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            if (auth()->user()->is_admin) {
                return redirect()->intended('/superadmin');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'No tienes permisos de administrador.']);
            }
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/superadmin/login');
    }
}
