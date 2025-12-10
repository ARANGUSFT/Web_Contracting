<?php

namespace App\Http\Controllers\Crew;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\JobRequest;
use App\Models\Emergencies;
use App\Models\Lead;
use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

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


   public function index(Request $request)
{
    $user   = Auth::guard('team')->user();
    $userId = $user->user_id; // Admin dueño de los leads

    // Solo mostrar leads del admin dueño
    $query = Lead::with('team')->where('user_id', $userId);

    // FILTRO DE BÚSQUEDA
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('first_name', 'like', '%' . $search . '%')
              ->orWhere('last_name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%')
              ->orWhere('phone', 'like', '%' . $search . '%')
              ->orWhere('street', 'like', '%' . $search . '%')
              ->orWhere('city', 'like', '%' . $search . '%')
              ->orWhere('state', 'like', '%' . $search . '%');
        });
    }

    // MAPA DE ESTADOS (para filtros y para el mapa de nombres/colores)
    $statusMapKeys = [
        'leads'     => 1,
        'prospect'  => 2, 
        'approved'  => 3,
        'completed' => 4,
        'invoiced'  => 5,
        'finish'    => 6,
        'cancelled' => 7,
    ];

    $statusMap = [
        1 => ['name' => 'Lead',      'color' => 'bg-warning'],
        2 => ['name' => 'Prospect',  'color' => 'bg-orange'],
        3 => ['name' => 'Approved',  'color' => 'bg-success'],
        4 => ['name' => 'Completed', 'color' => 'bg-primary'],
        5 => ['name' => 'Invoiced',  'color' => 'bg-danger'],
        6 => ['name' => 'Finish',    'color' => 'bg-info'],
        7 => ['name' => 'Cancelled', 'color' => 'bg-secondary'],
    ];

    // FILTRO DE ESTADO (múltiples estados)
    if ($request->has('status')) {
        $statuses = $request->status;

        if (!is_array($statuses)) {
            $statuses = [$statuses];
        }

        $validStatuses = [];
        foreach ($statuses as $status) {
            if ($status !== 'all' && isset($statusMapKeys[$status])) {
                $validStatuses[] = $statusMapKeys[$status];
            }
        }

        if (!empty($validStatuses)) {
            $query->whereIn('estado', $validStatuses);
        }
    }

    // FILTRO DE VENDEDOR
    if ($request->has('seller') && $request->seller != 'all') {
        $query->where('team_id', $request->seller);
    }

    // FILTRO DE ASIGNACIÓN
    if ($request->has('assignment') && $request->assignment != 'all') {
        if ($request->assignment == 'assigned') {
            $query->whereNotNull('team_id');
        } else {
            $query->whereNull('team_id');
        }
    }

    // FILTRO DE ÚLTIMO CONTACTO
    if ($request->has('lastContact') && $request->lastContact != 'all') {
        $now = now();
        switch ($request->lastContact) {
            case 'today':
                $query->whereDate('last_touched_at', $now->toDateString());
                break;
            case 'week':
                $query->where('last_touched_at', '>=', $now->copy()->subDays(7));
                break;
            case 'month':
                $query->where('last_touched_at', '>=', $now->copy()->subDays(30));
                break;
            case 'older':
                $query->where(function ($q) use ($now) {
                    $q->where('last_touched_at', '<', $now->copy()->subDays(30))
                      ->orWhereNull('last_touched_at');
                });
                break;
        }
    }

    // FILTRO DE MONTO
    if ($request->has('amount') && $request->amount != 'all') {
        switch ($request->amount) {
            case '0-1000':
                $query->where('contract_value', '>', 0)
                      ->where('contract_value', '<=', 1000);
                break;
            case '1000-5000':
                $query->where('contract_value', '>', 1000)
                      ->where('contract_value', '<=', 5000);
                break;
            case '5000+':
                $query->where('contract_value', '>', 5000);
                break;
        }
    }

    // Paginación manteniendo filtros
    $leads = $query->paginate(10)->appends($request->except('page'));

    // Vendedores del mismo admin
    $teams = Team::where('user_id', $userId)->get()->filter(function ($team) {
        return $team->role === 'sales';
    });

    // Contadores por estado solo de este admin
    $statusCounts = [
        'leads'     => Lead::where('estado', 1)->where('user_id', $userId)->count(),
        'prospect'  => Lead::where('estado', 2)->where('user_id', $userId)->count(),
        'approved'  => Lead::where('estado', 3)->where('user_id', $userId)->count(),
        'completed' => Lead::where('estado', 4)->where('user_id', $userId)->count(),
        'invoiced'  => Lead::where('estado', 5)->where('user_id', $userId)->count(),
        'finish'    => Lead::where('estado', 6)->where('user_id', $userId)->count(),
        'cancelled' => Lead::where('estado', 7)->where('user_id', $userId)->count(),
    ];

    // ACTIVE JOBS (excluyendo cancelled)
    $activeJobs = collect($statusCounts)->except('cancelled')->sum();

    // SUMAS POR ESTADO (contract_value)
    $statusSumsRaw = Lead::select('estado', DB::raw('SUM(contract_value) as total'))
        ->where('user_id', $userId)
        ->groupBy('estado')
        ->pluck('total', 'estado')
        ->toArray();

    $statusSums = [
        'leads'     => $statusSumsRaw[1] ?? 0,
        'prospect'  => $statusSumsRaw[2] ?? 0,
        'approved'  => $statusSumsRaw[3] ?? 0,
        'completed' => $statusSumsRaw[4] ?? 0,
        'invoiced'  => $statusSumsRaw[5] ?? 0,
        'finish'    => $statusSumsRaw[6] ?? 0,
        'cancelled' => $statusSumsRaw[7] ?? 0,
    ];

    return view('manageTeam.crew.dashboard', compact(
        'leads',
        'statusMap',
        'statusCounts',
        'statusSums',
        'teams',
        'activeJobs'
    ));
}


    public function show($id) 
    {
            // Obtener el Lead con sus relaciones
            $lead = Lead::with([
                'messages.user',
                'messages.team',
                'images',
                'files',
                'expenses',     // ✅ gastos
                'finanzas',      // ✅ pagos
                'team' // <-- AÑADIDO AQUÍ

            ])->findOrFail($id);
                
            // Obtener mensajes del chat
            $messages = $lead->messages->sortBy('created_at');
        
            // Obtener imágenes ordenadas
            $images = $lead->images->sortByDesc('created_at');
        
            // Mapeo de estados con colores
            $statusMap = [
                1 => ['name' => 'Lead', 'color' => 'bg-warning'], 
                2 => ['name' => 'Prospect', 'color' => 'bg-orange'], 
                3 => ['name' => 'Approved', 'color' => 'bg-success'], 
                4 => ['name' => 'Completed', 'color' => 'bg-primary'], 
                5 => ['name' => 'Invoiced', 'color' => 'bg-danger']
            ];
        
            return view('manageTeam.crew.lead_details', compact('lead', 'messages', 'images', 'statusMap'));
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
