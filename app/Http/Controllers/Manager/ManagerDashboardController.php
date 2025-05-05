<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    

   
}

