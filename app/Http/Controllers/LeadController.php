<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Team; // Asegúrate de importar el modelo Team
use App\Notifications\LeadAssignedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = $request->input('seller_id');

        // Consulta inicial
        $query = Lead::with('team');

        // Aplicar filtro solo si se seleccionó un vendedor específico
        if ($sellerId && $sellerId !== 'all') {
            $query->where('team_id', $sellerId);
        }

        // Resultados paginados
        $leads = $query->paginate(10)->appends(['seller_id' => $sellerId]);

        $teams = Team::all();

        // Contadores generales por estado (sin filtro por vendedor)
        $statusCounts = [
            'leads' => Lead::where('estado', 1)->count(),
            'prospect' => Lead::where('estado', 2)->count(),
            'approved' => Lead::where('estado', 3)->count(),
            'completed' => Lead::where('estado', 4)->count(),
            'invoiced' => Lead::where('estado', 5)->count(),
        ];

        // Suma total de contract_value por estado (sin filtro por vendedor)
        $statusSumsRaw = Lead::select('estado', DB::raw('SUM(contract_value) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        // Normalizar claves por consistencia con $statusCounts
        $statusSums = [
            'leads' => $statusSumsRaw[1] ?? 0,
            'prospect' => $statusSumsRaw[2] ?? 0,
            'approved' => $statusSumsRaw[3] ?? 0,
            'completed' => $statusSumsRaw[4] ?? 0,
            'invoiced' => $statusSumsRaw[5] ?? 0,
        ];

        return view('leads.list', compact('leads', 'statusCounts', 'statusSums', 'teams', 'sellerId'));
    }

    
    

    public function updateStatus(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
    
        // Validar el estado enviado
        $request->validate([
            'estado' => 'required|integer|between:1,6'
        ]);
    
        // Guardar nuevo estado
        $lead->estado = $request->estado;
        $lead->save();
    
        return response()->json(['success' => true, 'message' => 'Estado actualizado con éxito']);
    }



    public function assignStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|integer|between:1,6']);

        $lead = Lead::findOrFail($id);
        $lead->estado = $request->status;
        $lead->save();

        return back()->with('success', 'Lead status updated successfully.');
    }


    public function assignSales(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
    
        // Si se selecciona "sin asignar", se pone en null
        $lead->team_id = ($request->team_id === 'unassign' || empty($request->team_id)) ? null : (int) $request->team_id;
        $lead->save();
    
        // Notificar solo si hay un vendedor asignado
        if ($lead->team_id) {
            $team = Team::find($lead->team_id);
            if ($team) {
                $team->notify(new LeadAssignedNotification($lead));
            }
        }
    
        return redirect()->back()->with('success', 'Vendedor asignado correctamente.');
    }
    


    

    // Carga formulario
    public function financial()
    {
         return view('paymentReport.payment');
    }


    // Carga formulario
    public function create()
    {
        return view('leads.create');
    }

    // Create leads form
    public function store(Request $request)
    {
        // Validar datos
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'date_loss' => 'nullable|date',
        ]);

        // Manejo de archivos
        $data = $request->except(['location_photo']);

  
        if ($request->hasFile('location_photo')) {
            $data['location_photo'] = $request->file('location_photo')->store('uploads/location_photos', 'public');
        }

        // Crear el Lead
        Lead::create($data);

        return redirect()->route('leads.index')->with('success', 'Lead creado con éxito.');
    }








    public function show($id) 
    {
        // Obtener el Lead con sus relaciones
        $lead = Lead::with(['messages.user', 'messages.team', 'images', 'files'])->findOrFail($id);
    
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
    
        return view('leads.view', compact('lead', 'messages', 'images', 'statusMap'));
    }

   
    









    public function edit(Lead $lead)
    {
        $teams = Team::all(); // Obtener equipos disponibles
        $images = $lead->images; // Obtener imágenes relacionadas con el lead

        return view('leads.editLead', compact('lead', 'teams', 'images'));
    }


    public function update(Request $request, Lead $lead)
    {
       
          // Validar datos
          $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            
        ]);

        // Actualizar datos
        $lead->update($request->all());

        return redirect()->route('leads.index')->with('success', 'Lead actualizado correctamente.');
    }




    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead eliminado.');
    }
}
