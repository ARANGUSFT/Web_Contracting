<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class GuestDashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:team');

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('team')->user();
            if ($user->role !== 'guest') {
                abort(403, 'Acceso denegado.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $user = Auth::guard('team')->user();

        // Validación de rol directamente aquí
        if ($user->role !== 'guest') {
            abort(403, 'Acceso no autorizado.');
        }

        return view('manageTeam.guest.dashboard', compact('user'));
    }
    
}
