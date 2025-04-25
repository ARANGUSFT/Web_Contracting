<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CompanyAdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:team');
        $this->middleware(function ($request, $next) {
            if (Auth::guard('team')->user()->role !== 'company_admin') {
                abort(403, 'Access denied.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $user = Auth::guard('team')->user();
        return view('manageTeam.company_admin.dashboard', compact('user'));
    }
}
