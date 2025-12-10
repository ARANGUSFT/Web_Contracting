<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Team;
use App\Models\Lead_approvals;
use Illuminate\Support\Facades\Storage;
use App\Notifications\LeadAssignedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        // Solo mostrar leads del admin autenticado
        $query = Lead::with('team')->where('user_id', auth()->id());

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

        // FILTRO DE ESTADO - CORREGIDO PARA MANEJAR MÚLTIPLES ESTADOS
        if ($request->has('status')) {
            $statusMap = [
                'leads' => 1,
                'prospect' => 2, 
                'approved' => 3,
                'completed' => 4,
                'invoiced' => 5,
                'finish' => 6,
                'cancelled' => 7
            ];
            
            $statuses = $request->status;
            
            // Si es un string, convertirlo a array
            if (!is_array($statuses)) {
                $statuses = [$statuses];
            }
            
            // Filtrar solo los estados válidos y mapear a valores numéricos
            $validStatuses = [];
            foreach ($statuses as $status) {
                if ($status !== 'all' && isset($statusMap[$status])) {
                    $validStatuses[] = $statusMap[$status];
                }
            }
            
            // Aplicar filtro solo si hay estados válidos
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
                    $query->where('last_touched_at', '<', $now->copy()->subDays(30))
                          ->orWhereNull('last_touched_at');
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

        // Paginación manteniendo todos los parámetros
        $leads = $query->paginate(10)->appends($request->except('page'));

        $teams = Team::where('user_id', auth()->id())->get()->filter(function ($team) {
            return $team->role === 'sales';
        });
        
        // Contadores por estado (solo para este admin)
        $statusCounts = [
            'leads' => Lead::where('estado', 1)->where('user_id', auth()->id())->count(),
            'prospect' => Lead::where('estado', 2)->where('user_id', auth()->id())->count(),
            'approved' => Lead::where('estado', 3)->where('user_id', auth()->id())->count(),
            'completed' => Lead::where('estado', 4)->where('user_id', auth()->id())->count(),
            'invoiced' => Lead::where('estado', 5)->where('user_id', auth()->id())->count(),
            'finish' => Lead::where('estado', 6)->where('user_id', auth()->id())->count(),
            'cancelled' => Lead::where('estado', 7)->where('user_id', auth()->id())->count(),
        ];

        // CALCULAR ACTIVE JOBS (excluyendo cancelled)
        $activeJobs = collect($statusCounts)->except('cancelled')->sum();

        $statusSumsRaw = Lead::select('estado', DB::raw('SUM(contract_value) as total'))
            ->where('user_id', auth()->id())
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $statusSums = [
            'leads' => $statusSumsRaw[1] ?? 0,
            'prospect' => $statusSumsRaw[2] ?? 0,
            'approved' => $statusSumsRaw[3] ?? 0,
            'completed' => $statusSumsRaw[4] ?? 0,
            'invoiced' => $statusSumsRaw[5] ?? 0,
            'finish' => $statusSumsRaw[6] ?? 0,
            'cancelled' => $statusSumsRaw[7] ?? 0,
        ];

        return view('leads.list', compact('leads', 'statusCounts', 'statusSums', 'teams', 'activeJobs'));
    }

    public function assignStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|integer|between:1,7']); // ✅ Ya incluye el 6
    
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
            'user_id' => auth()->id(), // ✅ Aquí se asocia el usuario autenticado
        ]);
    
        $lead->approved_data_submitted = true;
        $lead->estado = 3; // Cambiar estado a Completed
        $lead->save();
    
        return back()->with('success', 'Lead approval data submitted successfully and status updated to Completed.');
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
    
        // Asociar el lead al usuario autenticado
        $data['user_id'] = auth()->id();
    
        // Crear el Lead
        Lead::create($data);
    
        return redirect()->route('leads.index')->with('success', 'Lead creado con éxito.');
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
            'finanzas'      // ✅ pagos
        ])->findOrFail($id);
            
        // Obtener mensajes del chat
        $messages = $lead->messages->sortBy('created_at');
    
        // Obtener imágenes ordenadas
        $images = $lead->images->sortByDesc('created_at');
    
        // Mapeo de estados con colores - AGREGADO ESTADO 6
        $statusMap = [
            1 => ['name' => 'Lead', 'color' => 'bg-warning'], 
            2 => ['name' => 'Prospect', 'color' => 'bg-orange'], 
            3 => ['name' => 'Approved', 'color' => 'bg-success'], 
            4 => ['name' => 'Completed', 'color' => 'bg-primary'], 
            5 => ['name' => 'Invoiced', 'color' => 'bg-danger'],
            6 => ['name' => 'Finish', 'color' => 'bg-secondary'], // ✅ NUEVO ESTADO 6
            7 => ['name' => 'Cancelled', 'color' => 'bg-secondary'] // ✅ NUEVO ESTADO 7
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

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead actualizado correctamente.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead eliminado.');
    }
}