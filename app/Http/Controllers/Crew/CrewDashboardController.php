<?php

namespace App\Http\Controllers\Crew;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CrewDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:team');
        $this->middleware(function ($request, $next) {
            if (Auth::guard('team')->user()->role !== 'crew') {
                abort(403, 'Acceso denegado.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $user = Auth::guard('team')->user();
        return view('manageTeam.crew.dashboard', compact('user'));
    }
}
