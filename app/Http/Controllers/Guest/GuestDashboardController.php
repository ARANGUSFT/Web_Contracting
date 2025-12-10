<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class GuestDashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:team']);
    }

    public function index(Request $request)
    {
        $user   = Auth::guard('team')->user();   // Manager logueado
        $userId = $user->user_id;                // Admin dueño de los leads

        // === QUERY BASE: leads del admin asociado al manager ===
        $query = Lead::with('team')->where('user_id', $userId);

        /*
        |--------------------------------------------------------------------------
        | FILTRO DE BÚSQUEDA (search)
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('street', 'like', '%' . $search . '%')
                ->orWhere('city', 'like', '%' . $search . '%')
                ->orWhere('state', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO DE ESTADO (status) - MULTISELECT
        |--------------------------------------------------------------------------
        */
        if ($request->has('status')) {
            $statusMap = [
                'leads'     => 1,
                'prospect'  => 2,
                'approved'  => 3,
                'completed' => 4,
                'invoiced'  => 5,
                'finish'    => 6,
                'cancelled' => 7,
            ];

            $statuses = $request->status;

            // Normalizar a array
            if (!is_array($statuses)) {
                $statuses = [$statuses];
            }

            // Filtrar estados válidos y mapear a número
            $validStatuses = [];
            foreach ($statuses as $status) {
                if ($status !== 'all' && isset($statusMap[$status])) {
                    $validStatuses[] = $statusMap[$status];
                }
            }

            if (!empty($validStatuses)) {
                $query->whereIn('estado', $validStatuses);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO DE VENDEDOR (seller_id)
        |--------------------------------------------------------------------------
        */
        $sellerId = $request->input('seller_id');

        if ($sellerId && $sellerId !== 'all') {
            $query->where('team_id', $sellerId);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO DE ASIGNACIÓN (assignment)
        |--------------------------------------------------------------------------
        | assignment = 'assigned' / 'unassigned' / 'all'
        */
        if ($request->has('assignment') && $request->assignment !== 'all') {
            if ($request->assignment === 'assigned') {
                $query->whereNotNull('team_id');
            } else {
                $query->whereNull('team_id');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO DE ÚLTIMO CONTACTO (lastContact)
        |--------------------------------------------------------------------------
        | lastContact = today / week / month / older / all
        */
        if ($request->has('lastContact') && $request->lastContact !== 'all') {
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
                    // IMPORTANTE: agrupar el OR para no romper otros filtros
                    $query->where(function ($q) use ($now) {
                        $q->where('last_touched_at', '<', $now->copy()->subDays(30))
                        ->orWhereNull('last_touched_at');
                    });
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO DE MONTO DE CONTRATO (amount)
        |--------------------------------------------------------------------------
        | amount = 0-1000 / 1000-5000 / 5000+ / all
        */
        if ($request->has('amount') && $request->amount !== 'all') {
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

        /*
        |--------------------------------------------------------------------------
        | PAGINACIÓN (manteniendo filtros en la URL)
        |--------------------------------------------------------------------------
        */
        $leads = $query->paginate(10)->appends($request->except('page'));

        /*
        |--------------------------------------------------------------------------
        | LISTA DE VENDEDORES (sellers)
        |--------------------------------------------------------------------------
        */
        $sellers = Team::where('user_id', $userId)
            ->where('role', 'sales')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | CONTADORES POR ESTADO (statusCounts)
        |--------------------------------------------------------------------------
        */
        $statusCounts = [
            'leads'     => Lead::where('estado', 1)->where('user_id', $userId)->count(),
            'prospect'  => Lead::where('estado', 2)->where('user_id', $userId)->count(),
            'approved'  => Lead::where('estado', 3)->where('user_id', $userId)->count(),
            'completed' => Lead::where('estado', 4)->where('user_id', $userId)->count(),
            'invoiced'  => Lead::where('estado', 5)->where('user_id', $userId)->count(),
            'finish'    => Lead::where('estado', 6)->where('user_id', $userId)->count(),
            'cancelled' => Lead::where('estado', 7)->where('user_id', $userId)->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | SUMA DE CONTRACT_VALUE POR ESTADO (statusSums)
        |--------------------------------------------------------------------------
        */
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

        return view('manageTeam.guest.dashboard', compact(
            'leads',
            'sellers',
            'sellerId',
            'statusCounts',
            'statusSums'
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
    
        return view('manageTeam.guest.view', compact('lead', 'messages', 'images', 'statusMap'));
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
    

    
}
