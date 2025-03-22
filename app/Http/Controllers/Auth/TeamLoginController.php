<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class TeamLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.team_login'); // Vista para el formulario de login de vendedores
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('team')->attempt($credentials)) {
            return redirect()->route('seller.dashboard'); // Redirigir al panel de vendedores
        }

        return back()->with('error', 'Credenciales incorrectas.');
    }

    public function logout()
    {
        Auth::guard('team')->logout();
        return redirect()->route('team.login');
    }
}
