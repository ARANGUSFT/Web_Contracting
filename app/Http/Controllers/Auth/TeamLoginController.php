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
            $user = Auth::guard('team')->user();

            // ✅ Bloquear si está inactivo
            if (!$user->is_active) {
                Auth::guard('team')->logout();
                return redirect()->route('team.login')->withErrors([
                    'email' => 'Your account is inactive.',
                ]);
            }
        
            return match ($user->role) {
                'sales' => redirect()->route('seller.dashboard'),
                'guest' => redirect()->route('guest.dashboard'),
                'manager' => redirect()->route('manager.dashboard'), // si existe
                'company_admin' => redirect()->route('admin.dashboard'), // si existe
                'project_manager' => redirect()->route('project.dashboard'), // si existe
                'crew' => redirect()->route('crew.dashboard'), // si existe
                default => abort(403, 'Role not authorized.'),
            };
        }
        

        return back()->with('error', 'Wrong credentials.');
    }

    public function logout()
    {
        Auth::guard('team')->logout();
        return redirect()->route('team.login');
    }
}
