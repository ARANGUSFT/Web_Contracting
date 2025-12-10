<?php
namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Http\Controllers\Controller;

class SellerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:team');
    }

    public function index()
    {
        $user = Auth::guard('team')->user();
    
        $leads = Lead::where('team_id', $user->id)->paginate(10);
    
        $statusCounts = [
            'leads'     => Lead::where('team_id', $user->id)->where('estado', 1)->count(),
            'prospect'  => Lead::where('team_id', $user->id)->where('estado', 2)->count(),
            'approved'  => Lead::where('team_id', $user->id)->where('estado', 3)->count(),
            'completed' => Lead::where('team_id', $user->id)->where('estado', 4)->count(),
            'invoiced'  => Lead::where('team_id', $user->id)->where('estado', 5)->count(),
            'finish'  => Lead::where('team_id', $user->id)->where('estado', 6)->count(),
            'cancelled'  => Lead::where('team_id', $user->id)->where('estado', 7)->count(),

        ];
    
        $statusMap = [
            1 => ['name' => 'Lead', 'color' => 'bg-warning'],
            2 => ['name' => 'Prospect', 'color' => 'bg-orange'],
            3 => ['name' => 'Approved', 'color' => 'bg-success'],
            4 => ['name' => 'Completed', 'color' => 'bg-primary'],
            5 => ['name' => 'Invoiced', 'color' => 'bg-danger'],
            6 => ['name' => 'Finish', 'color' => 'bg-secondary'], // ✅ NUEVO ESTADO 6
            7 => ['name' => 'Cancelled', 'color' => 'bg-secondary'] // ✅ NUEVO ESTADO 7

        ];
    
        return view('manageTeam.seller.dashboard', compact('leads', 'statusMap', 'statusCounts'));
    }

    public function create()
    {
        return view('manageTeam.seller.createLeads');
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'date_loss' => 'nullable|date',
            'location_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para la imagen
        ]);

        // Preparar datos excluyendo el archivo
        $data = $request->except(['location_photo']);

        // Manejo de archivos
        if ($request->hasFile('location_photo')) {
            $data['location_photo'] = $request->file('location_photo')->store('uploads/location_photos', 'public');
        }

        // Obtener el usuario del equipo
        $team = Auth::guard('team')->user();
        
        // Asignar relaciones ANTES de crear el lead
        $data['team_id'] = $team->id;
        if ($team->user_id) {
            $data['user_id'] = $team->user_id;
        }

        // Crear el lead con todos los datos
        $lead = Lead::create($data);

        return redirect()->route('seller.dashboard')->with('success', 'Lead creado con éxito y asignado correctamente.');
    }

    public function show($id) 
    {
        $user = Auth::guard('team')->user();
        $lead = Lead::where('id', $id)->where('team_id', $user->id)->firstOrFail();
    
        $messages = $lead->messages()->with(['user', 'team'])->orderBy('created_at', 'asc')->get();
        $images = $lead->images()->orderBy('created_at', 'desc')->get();
    
        $statusMap = [
            1 => ['name' => 'Lead', 'color' => 'bg-warning'],
            2 => ['name' => 'Prospect', 'color' => 'bg-orange'],
            3 => ['name' => 'Approved', 'color' => 'bg-success'],
            4 => ['name' => 'Completed', 'color' => 'bg-primary'],
            5 => ['name' => 'Invoiced', 'color' => 'bg-danger'],
            6 => ['name' => 'Finish', 'color' => 'bg-secondary'], // ✅ NUEVO ESTADO 6
            7 => ['name' => 'Cancelled', 'color' => 'bg-secondary'] // ✅ NUEVO ESTADO 7

        ];
    
        return view('manageTeam.seller.lead_details', compact('lead', 'messages', 'images', 'statusMap'));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::guard('team')->user();
        $lead = Lead::where('id', $id)->where('team_id', $user->id)->firstOrFail();

        $request->validate([
            'estado' => 'required|integer|between:1,5'
        ]);

        // ✅ CORREGIDO: Actualizar el estado
        $lead->estado = $request->estado;
        $lead->save();

        return redirect()->back()->with('success', 'Estado del lead actualizado correctamente.');
    }

    public function edit($id)
    {
        $user = Auth::guard('team')->user();
        $lead = Lead::where('id', $id)->where('team_id', $user->id)->firstOrFail();

        return view('manageTeam.seller.lead_edit', compact('lead'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::guard('team')->user();
        $lead = Lead::where('id', $id)->where('team_id', $user->id)->firstOrFail();

    // 1. Validación
        $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone'        => 'required|string|max:20',
            'email'        => 'required|email|max:255',

            'location_photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'remove_location_photo' => 'nullable|in:0,1',
        ]);

        // 2. Actualizar SOLO los campos que no deben tocar el estado
        $lead->update($request->only([
            'first_name',
            'last_name',
            'company_name',
            'phone',
            'email',
            // aquí puedes ir agregando más campos de texto si quieres
        ]));

        // Siempre trabajamos con un array
        $photos = $lead->location_photo ?? [];
        if (!is_array($photos)) {
            $photos = [$photos];
        }

        // 3. Si marcó "Remove", borrar TODAS las fotos de esa sección
        if ($request->boolean('remove_location_photo')) {
            foreach ($photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
            $photos = []; // vaciar el array
        }

        // 4. Si subió una nueva foto, agregarla al array
        if ($request->hasFile('location_photo')) {
            $path = $request->file('location_photo')->store('leads/location_photos', 'public');
            $photos[] = $path; // agregamos al array
        }

        // 5. Guardar el array actualizado
        $lead->location_photo = $photos;
        $lead->save();

        // ✅ CORREGIDO: Redirección correcta
        return redirect()->route('seller.leads.show', $lead->id)->with('success', 'Lead actualizado correctamente.');
    }
}