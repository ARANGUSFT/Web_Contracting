<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use App\Models\Emergencies;
use App\Models\Lead;
use App\Models\Lead_approvals;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class ProjectDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:team');
        $this->middleware(function ($request, $next) {
            if (Auth::guard('team')->user()->role !== 'project_manager') {
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

    return view('manageTeam.project_manager.dashboard', compact(
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
        
            return view('manageTeam.project_manager.details', compact('lead', 'messages', 'images', 'statusMap'));
    }


    /**
     * Calendar view - MÉTODO PRINCIPAL QUE FALTABA
     */
    public function calendar()
    {
        $user = Auth::guard('team')->user();
        
        $events = $this->getAllCalendarEvents($user);

        return view('manageTeam.calendar', compact('events', 'user'));
    }

    /**
     * Get all calendar events
     */
    private function getAllCalendarEvents($user)
    {
        return array_merge(
            $this->getJobRequestEvents($user),
            $this->getEmergencyEvents($user),
            $this->getLeadApprovalEvents()
        );
    }

    /**
     * Get Job Request events for calendar
     */
    private function getJobRequestEvents($user)
    {
        return $user->jobRequests()
            ->with('teamMembers')
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => 'Job: ' . $job->job_number_name,
                    'start' => $job->install_date_requested,
                    'url' => route('jobs.show', $job->id),
                    'type' => 'Job Request',
                    'company' => $job->company_name,
                    'rep' => $job->company_rep,
                    'rep_phone' => $job->company_rep_phone,
                    'rep_email' => $job->company_rep_email,
                    'customer' => $job->customer_first_name . ' ' . $job->customer_last_name,
                    'customer_phone' => $job->customer_phone_number,
                    'address' => $this->formatAddress([
                        'street' => $job->job_address_street_address,
                        'street2' => $job->job_address_street_address_line_2,
                        'city' => $job->job_address_city,
                        'state' => $job->job_address_state,
                        'zip' => $job->job_address_zip_code
                    ]),
                    'materials' => [
                        'starter' => $job->starter_bundles_ordered ?? 0,
                        'hip' => $job->hip_and_ridge_ordered ?? 0,
                        'field' => $job->field_shingle_bundles_ordered ?? 0,
                        'modified' => $job->modified_bitumen_cap_rolls_ordered ?? 0,
                    ],
                    'delivery_date' => $job->delivery_date,
                    'inspections' => [
                        'mid_roof' => $job->mid_roof_inspection,
                        'siding' => $job->siding_being_replaced,
                        'layers' => $job->asphalt_shingle_layers_to_remove,
                        're_deck' => $job->re_deck,
                    ],
                    'special_instructions' => $job->special_instructions,
                    'team' => $this->formatTeamMembers($job->teamMembers),
                    'files' => $this->processJobFiles($job),
                    'color' => '#0da146ff', // Verde original de tu código
                    'textColor' => '#ffffff'
                ];
            })
            ->toArray();
    }

    /**
     * Get Emergency events for calendar
     */
    private function getEmergencyEvents($user)
    {
        return $user->emergencies()
            ->with('teamMembers')
            ->get()
            ->map(function ($emergency) {
                return [
                    'id' => $emergency->id,
                    'title' => 'Emergency: ' . $emergency->job_number_name,
                    'start' => $emergency->date_submitted,
                    'url' => route('emergency.show', $emergency->id),
                    'type' => 'Emergency',
                    'company' => $emergency->company_name,
                    'email' => $emergency->company_contact_email,
                    'address' => $this->formatAddress([
                        'street' => $emergency->job_address,
                        'street2' => $emergency->job_address_line2,
                        'city' => $emergency->job_city,
                        'state' => $emergency->job_state,
                        'zip' => $emergency->job_zip_code
                    ]),
                    'supplement' => $emergency->type_of_supplement,
                    'terms' => $emergency->terms_conditions ? 'Accepted' : 'Not Accepted',
                    'requirements' => $emergency->requirements ? 'Accepted' : 'Not Accepted',
                    'team' => $this->formatTeamMembers($emergency->teamMembers),
                    'files' => $this->processEmergencyFiles($emergency),
                    'color' => '#dc3545', // Rojo original de tu código
                    'textColor' => '#ffffff'
                ];
            })
            ->toArray();
    }

    /**
     * Get Lead Approval events for calendar
     */
    private function getLeadApprovalEvents()
    {
        // Verificar si el modelo existe
        if (!class_exists('App\Models\Lead_approvals') && !class_exists('App\Lead_approvals')) {
            return [];
        }

        $modelClass = class_exists('App\Models\Lead_approvals') 
            ? 'App\Models\Lead_approvals' 
            : 'App\Lead_approvals';

        return $modelClass::whereNotNull('installation_date')
            ->where('installation_date', '>=', now()->subMonths(3))
            ->limit(100)
            ->get()
            ->map(function ($approval) {
                return [
                    'title' => 'Approved Lead - ' . ($approval->lead_name ?: 'Unnamed Lead'),
                    'start' => $approval->installation_date,
                    'url' => route('manager.manage', $approval->lead_id),
                    'type' => 'Approved',
                    'color' => '#670ebb',
                    'textColor' => '#ffffff'
                ];
            })
            ->toArray();
    }

    /**
     * Process job request files
     */
    private function processJobFiles($job)
    {
        $fileGroups = [
            ['label' => 'Aerial Measurements', 'data' => $job->aerial_measurement ?? []],
            ['label' => 'Material Orders', 'data' => $job->material_order ?? []],
            ['label' => 'Pictures', 'data' => $job->file_upload ?? []],
            ['label' => 'Contracts', 'data' => $job->contract_upload_path ?? []]
        ];

        return $this->processFileGroups($fileGroups);
    }

    /**
     * Process emergency files
     */
    private function processEmergencyFiles($emergency)
    {
        $fileGroups = [
            ['label' => 'Aerial Measurements', 'data' => $emergency->aerial_measurement_path ?? []],
            ['label' => 'Contracts', 'data' => $emergency->contract_upload_path ?? []],
            ['label' => 'Pictures', 'data' => $emergency->file_picture_upload_path ?? []]
        ];

        return $this->processFileGroups($fileGroups);
    }

    /**
     * Process file groups from different sources
     */
    private function processFileGroups($fileGroups)
    {
        $processedFiles = [];

        foreach ($fileGroups as $group) {
            $data = $group['data'];
            
            // Si está vacío, saltar
            if (empty($data)) {
                continue;
            }

            // Si es string, intentar decodificar JSON
            if (is_string($data)) {
                $decoded = json_decode($data, true);
                $items = $decoded ?: (trim($data) ? [$data] : []);
            } else {
                $items = is_array($data) ? $data : [$data];
            }

            // Procesar cada archivo
            foreach ($items as $item) {
                if (empty($item)) continue;

                if (is_array($item)) {
                    $path = $item['path'] ?? $item['url'] ?? $item['file_path'] ?? null;
                    $name = $item['name'] ?? $item['original_name'] ?? ($path ? basename($path) : 'file');
                } else {
                    $path = $item;
                    $name = basename($path);
                }

                if ($path) {
                    $processedFiles[] = [
                        'path' => $path,
                        'name' => $name,
                        'label' => $group['label'],
                        'type' => $this->getFileType($path)
                    ];
                }
            }
        }

        return $processedFiles;
    }

    /**
     * Get file type based on extension
     */
    private function getFileType($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        $documentTypes = ['pdf', 'doc', 'docx', 'txt', 'rtf'];
        $spreadsheetTypes = ['xls', 'xlsx', 'csv'];
        
        if (in_array($extension, $imageTypes)) {
            return 'image';
        } elseif (in_array($extension, $documentTypes)) {
            return 'document';
        } elseif (in_array($extension, $spreadsheetTypes)) {
            return 'spreadsheet';
        } else {
            return 'file';
        }
    }

    /**
     * Format address from components
     */
    private function formatAddress($addressComponents)
    {
        $street = trim($addressComponents['street'] ?? '');
        $street2 = trim($addressComponents['street2'] ?? '');
        $city = trim($addressComponents['city'] ?? '');
        $state = trim($addressComponents['state'] ?? '');
        $zip = trim($addressComponents['zip'] ?? '');

        $addressParts = array_filter([$street, $street2, $city, $state, $zip]);
        
        return !empty($addressParts) ? implode(', ', $addressParts) : 'Address not available';
    }

    /**
     * Format team members with their roles
     */
    private function formatTeamMembers($teamMembers)
    {
        if (!$teamMembers || $teamMembers->isEmpty()) {
            return [];
        }

        return $teamMembers->map(function ($member) {
            $role = $member->role ? ucfirst(str_replace('_', ' ', $member->role)) : 'Team Member';
            return $member->name . ' (' . $role . ')';
        })->toArray();
    }
}