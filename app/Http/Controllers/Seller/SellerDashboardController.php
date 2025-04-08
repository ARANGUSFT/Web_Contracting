<?php
namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Http\Controllers\Controller; // Asegurar que extiende el controlador base

class SellerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:team'); // Solo vendedores pueden acceder
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
    
        return view('seller.dashboard', compact('leads', 'statusMap', 'statusCounts'));
    }
    

    

    // Renderiza el form
    public function create()
    {
        return view('seller.createLeads');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'date_loss' => 'nullable|date',
        ]);
    
        $data = $request->except(['location_photo']);
    
        if ($request->hasFile('location_photo')) {
            $file = $request->file('location_photo');
            $data['location_photo'] = [
                'path' => $file->storeAs('uploads/location_photos', $file->getClientOriginalName(), 'public'),
                'original_name' => $file->getClientOriginalName()
            ];
        }
    
        // Crear el Lead inicialmente sin asignar
        $lead = Lead::create($data);
    
        // Obtener el vendedor autenticado (Team)
        $team = Auth::guard('team')->user();
    
        // Asignar team_id y user_id después de crear
        $lead->team_id = $team->id;
    
        if ($team->user_id) {
            $lead->user_id = $team->user_id;
        }
    
        $lead->save();
    
        return redirect()->route('seller.dashboard')->with('success', 'Lead creado con éxito y asignado correctamente.');
    }
    

    
    
    
    
    

    public function show($id) 
    {
        $user = Auth::guard('team')->user();
        
        // Buscar claramente el lead asignado al vendedor
        $lead = Lead::where('id', $id)->where('team_id', $user->id)->firstOrFail();
    
    
        // Obtener mensajes del chat y imágenes
        $messages = $lead->messages()->with(['user', 'team'])->orderBy('created_at', 'asc')->get();
        $images = $lead->images()->orderBy('created_at', 'desc')->get();
    
        // Estados con colores
        $statusMap = [
            1 => ['name' => 'Lead', 'color' => 'bg-warning'],
            2 => ['name' => 'Prospect', 'color' => 'bg-orange'],
            3 => ['name' => 'Approved', 'color' => 'bg-success'],
            4 => ['name' => 'Completed', 'color' => 'bg-primary'],
            5 => ['name' => 'Invoiced', 'color' => 'bg-danger']
        ];
    
        return view('seller.lead_details', compact('lead', 'messages', 'images', 'statusMap'));
    }

    

    
    
    
    




    public function updateStatus(Request $request, $id)
    {
        $user = Auth::guard('team')->user();
        $lead = Lead::where('id', $id)->where('team_id', $user->id)->firstOrFail();

        // Validación del estado
        $request->validate([
            'estado' => 'required|integer|between:1,5'
        ]);

        // Guardar el nuevo estado
        $lead->save();

        return redirect()->back()->with('success', 'Lead status updated successfully.');
    }
    // Boton edit
    public function edit($id)
    {
        $user = Auth::guard('team')->user();
        $lead = Lead::where('id', $id)->where('team_id', $user->id)->firstOrFail();

        return view('seller.lead_edit', compact('lead'));
    }
    // Funcionalidad de actualizar
    public function update(Request $request, $id)
    {
        $user = Auth::guard('team')->user();
        $lead = Lead::where('id', $id)->where('team_id', $user->id)->firstOrFail();

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

        return redirect()->route('seller.leads.show', $lead->id)->with('success', 'Lead updated successfully.');
    }


    
    
}
