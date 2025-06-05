<?php

namespace App\Http\Controllers\Crew;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\JobRequest;
use App\Models\Emergencies;
use App\Models\Lead;


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
    
        // Obtener los leads que pertenecen al vendedor actual
        $leads = Lead::where('team_id', $user->id)->paginate(10);
    
        // Contar leads por estado SOLO del vendedor autenticado
        $statusCounts = [
            'leads'     => Lead::where('team_id', $user->id)->where('estado', 1)->count(),
            'prospect'  => Lead::where('team_id', $user->id)->where('estado', 2)->count(),
            'approved'  => Lead::where('team_id', $user->id)->where('estado', 3)->count(),
            'completed' => Lead::where('team_id', $user->id)->where('estado', 4)->count(),
            'invoiced'  => Lead::where('team_id', $user->id)->where('estado', 5)->count(),
        ];
    
        // Status mapping with names and Bootstrap colors
        $statusMap = [
            1 => ['name' => 'Lead', 'color' => 'bg-warning'], // Yellow
            2 => ['name' => 'Prospect', 'color' => 'bg-orange'], // Orange
            3 => ['name' => 'Approved', 'color' => 'bg-success'], // Green
            4 => ['name' => 'Completed', 'color' => 'bg-primary'], // Blue
            5 => ['name' => 'Invoiced', 'color' => 'bg-danger'] // Red
        ];

        return view('manageTeam.crew.dashboard', compact('leads', 'statusMap', 'statusCounts'));
    }
    

    
    public function calendar()
    {
        $user = Auth::guard('team')->user();
    
        $events = [];
    
        // JOB REQUESTS
        $jobs = $user->jobRequests()->with('teamMembers')->get();
    
        foreach ($jobs as $job) {
            $events[] = [
                'id' => $job->id,
                'title' => 'Job: ' . $job->job_number_name,
                'start' => $job->install_date_requested,
                'url' => route('jobs.show', $job->id),
                'type' => 'Job',
                'company' => $job->company_name,
                'rep' => $job->company_rep,
                'rep_phone' => $job->company_rep_phone,
                'rep_email' => $job->company_rep_email,
                'customer' => $job->customer_first_name . ' ' . $job->customer_last_name,
                'customer_phone' => $job->customer_phone_number,
                'address' => $job->job_address_street_address . ' ' . $job->job_address_street_address_line_2 . ', ' . $job->job_address_city . ', ' . $job->job_address_state . ' ' . $job->job_address_zip_code,
                'materials' => [
                    'starter' => $job->starter_bundles_ordered,
                    'hip' => $job->hip_and_ridge_ordered,
                    'field' => $job->field_shingle_bundles_ordered,
                    'modified' => $job->modified_bitumen_cap_rolls_ordered,
                ],
                'delivery_date' => $job->delivery_date,
                'inspections' => [
                    'mid_roof' => $job->mid_roof_inspection,
                    'siding' => $job->siding_being_replaced,
                    'layers' => $job->asphalt_shingle_layers_to_remove,
                    're_deck' => $job->re_deck,
                ],
                'special_instructions' => $job->special_instructions,
                'team' => $job->teamMembers->map(fn($t) => $t->name . ' (' . ucfirst(str_replace('_', ' ', $t->role)) . ')')->toArray(),
                'color' => '#0d6efd',
            ];
        }
    
        // EMERGENCIES
        $emergencies = $user->emergencies()->with('teamMembers')->get();
    
        foreach ($emergencies as $emergency) {
            $events[] = [
                'id' => $emergency->id,
                'title' => 'Emergency: ' . $emergency->job_number_name,
                'start' => $emergency->date_submitted,
                'url' => route('emergency.show', $emergency->id),
                'type' => 'Emergency',
                'company' => $emergency->company_name,
                'email' => $emergency->company_contact_email,
                'address' => "{$emergency->job_address} {$emergency->job_address_line2}, {$emergency->job_city}, {$emergency->job_state} {$emergency->job_zip_code}",
                'supplement' => $emergency->type_of_supplement,
                'terms' => $emergency->terms_conditions ? 'Accepted' : 'Not Accepted',
                'requirements' => $emergency->requirements ? 'Accepted' : 'Not Accepted',
                'team' => $emergency->teamMembers->map(fn($t) => $t->name . ' (' . ucfirst(str_replace('_', ' ', $t->role)) . ')')->toArray(),
                'color' => '#dc3545',
            ];
        }
    
        return view('manageTeam.calendar', compact('events', 'user'));
    }


}
