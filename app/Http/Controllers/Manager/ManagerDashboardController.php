<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\JobRequest;
use App\Models\Emergencies;
use App\Models\Lead_approvals;


class ManagerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:team']);
    }

    public function index(Request $request)
    {
        $user = Auth::guard('team')->user();
        $sellerId = $request->input('seller_id');

        // Leads del admin asociado a este manager
        $query = Lead::with('team')->where('user_id', $user->user_id);

        if ($sellerId && $sellerId !== 'all') {
            $query->where('team_id', $sellerId);
        }

        $leads = $query->paginate(10)->appends(['seller_id' => $sellerId]);

        $sellers = Team::where('user_id', $user->user_id)->where('role', 'sales')->get();

        $statusCounts = [
            'leads' => Lead::where('estado', 1)->where('user_id', $user->user_id)->count(),
            'prospect' => Lead::where('estado', 2)->where('user_id', $user->user_id)->count(),
            'approved' => Lead::where('estado', 3)->where('user_id', $user->user_id)->count(),
            'completed' => Lead::where('estado', 4)->where('user_id', $user->user_id)->count(),
            'invoiced' => Lead::where('estado', 5)->where('user_id', $user->user_id)->count(),
        ];

        $statusSumsRaw = Lead::select('estado', DB::raw('SUM(contract_value) as total'))
            ->where('user_id', $user->user_id)
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $statusSums = [
            'leads' => $statusSumsRaw[1] ?? 0,
            'prospect' => $statusSumsRaw[2] ?? 0,
            'approved' => $statusSumsRaw[3] ?? 0,
            'completed' => $statusSumsRaw[4] ?? 0,
            'invoiced' => $statusSumsRaw[5] ?? 0,
        ];

        return view('manageTeam.manager.dashboard', compact('leads', 'sellers', 'sellerId', 'statusCounts', 'statusSums'));
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
    
        return view('manageTeam.manager.view', compact('lead', 'messages', 'images', 'statusMap'));
    }


    public function assignStatusManage(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|between:1,6'
        ]);
    
        $lead = Lead::findOrFail($id);
        $lead->estado = $request->status;
        $lead->last_touched_at = now(); // Actualiza fecha última modificación
        $lead->save();
    
        return back()->with('success', 'Lead status updated successfully.');
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
                'color' => '#24c122',
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

        // 🔹 Eventos de Leads Aprobados
            $approvalEvents = Lead_approvals::all()->map(function ($approval) {
                return [
                    'title' => 'Approved Lead - ' . $approval->lead_name,
                    'start' => \Carbon\Carbon::parse($approval->installation_date)->toDateString(),
                    'url' => route('manager.manage', $approval->lead_id),
                    'type'  => 'Lead Approval',
                    'color' => '#670ebb',
                ];
            });

            // 🔹 Combinar
            $events = array_merge($events, $approvalEvents->toArray());


    
        return view('manageTeam.calendar', compact('events', 'user'));
    }


    public function assignStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|integer|between:1,6']);
    
        $lead = Lead::findOrFail($id);
        $lead->estado = $request->status;
        $lead->last_touched_at = now(); // 👈 actualiza la última modificación
        $lead->save();
    
        return back()->with('success', 'Lead status updated successfully.');
    }


    public function submitApprovedData(Request $request, $id)
    {
        $request->validate([
            'lead_name' => 'required|string|max:255',
            'lead_address' => 'required|string|max:255',
            'lead_phone' => 'required|string|max:20',
            'installation_date' => 'required|date',
            'extra_info' => 'nullable|string|max:1000',
        ]);
    
        // Obtener el lead y su usuario relacionado
        $lead = Lead::with('user')->findOrFail($id);
        $user = $lead->user;
    
        // Validar que el usuario exista
        if (!$user) {
            return back()->withErrors('El lead no tiene un usuario asignado.');
        }
    
        Lead_approvals::create([
            'lead_id' => $id,
            'company_name' => $user->company_name,
            'company_representative' => $user->name . ' ' . $user->last_name,
            'company_phone' => $user->phone,
            'lead_name' => $request->lead_name,
            'lead_address' => $request->lead_address,
            'lead_phone' => $request->lead_phone,
            'installation_date' => $request->installation_date,
            'extra_info' => $request->extra_info,
        ]);
    
        $lead->approved_data_submitted = true;
        $lead->estado = 4; // Cambiar estado a Completed
        $lead->save();
    
        return back()->with('success', 'Lead approval data submitted successfully and status updated to Completed.');
    }
    


   
}

