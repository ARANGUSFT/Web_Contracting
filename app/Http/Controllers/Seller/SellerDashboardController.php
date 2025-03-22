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
        $leads = Lead::where('team_id', $user->id)->paginate(10);


        // Contar leads por estado
        $statusCounts = [
            'leads' => Lead::where('estado', 1)->count(),
            'prospect' => Lead::where('estado', 2)->count(),
            'approved' => Lead::where('estado', 3)->count(),
            'completed' => Lead::where('estado', 4)->count(),
            'invoiced' => Lead::where('estado', 5)->count(),
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

    public function show($id) 
    {
        $user = Auth::guard('team')->user();
        
        // Buscar claramente el lead asignado al vendedor
        $lead = Lead::where('id', $id)->where('team_id', $user->id)->firstOrFail();
    
        // Convertir manualmente los campos JSON a arrays si aún vienen como strings
        $lead->files = is_array($lead->files) ? $lead->files : json_decode($lead->files, true) ?? [];
        $lead->finanzas = is_array($lead->finanzas) ? $lead->finanzas : json_decode($lead->finanzas, true) ?? [];
        $lead->anexos = is_array($lead->anexos) ? $lead->anexos : json_decode($lead->anexos, true) ?? [];
        $lead->contratos = is_array($lead->contratos) ? $lead->contratos : json_decode($lead->contratos, true) ?? [];
    
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
    
    


  
    public function updateDocuments(Request $request, $leadId)
    {
        $user = Auth::guard('team')->user();
        $lead = Lead::where('id', $leadId)->where('team_id', $user->id)->firstOrFail();
    
        $request->validate([
            'files_documento.*' => 'nullable|file|max:10240',
            'files_finanzas.*' => 'nullable|file|max:10240',
            'files_anexos.*' => 'nullable|file|max:10240',
            'files_contratos.*' => 'nullable|file|max:10240',
        ]);
    
        $uploadFields = [
            'files'     => 'files_documento',
            'finanzas'  => 'files_finanzas',
            'anexos'    => 'files_anexos',
            'contratos' => 'files_contratos',
        ];
    
        foreach ($uploadFields as $column => $inputName) {
            if ($request->hasFile($inputName)) {
                $newFiles = [];
                foreach ($request->file($inputName) as $file) {
                    $newFiles[] = [
                        'path' => $file->store("lead_{$column}", 'public'),
                        'original_name' => $file->getClientOriginalName()
                    ];
                }
    
                $existingFiles = $lead->$column ?? [];
                if (!is_array($existingFiles)) {
                    $existingFiles = json_decode($existingFiles, true) ?: [];
                }
    
                $lead->$column = array_merge($existingFiles, $newFiles);
            }
        }
    
        $lead->save();
    
        return redirect()->back()->with('success', 'Documents updated successfully.');
    }
    
    public function deleteFile(Request $request, $leadId)
    {
        $user = Auth::guard('team')->user();
        $lead = Lead::where('id', $leadId)->where('team_id', $user->id)->firstOrFail();
    
        $request->validate([
            'type' => 'required|in:files,finanzas,anexos,contratos',
            'file_path' => 'required|string'
        ]);
    
        $type = $request->input('type');
        $filePathToDelete = $request->input('file_path');
    
        $files = is_array($lead->$type) ? $lead->$type : json_decode($lead->$type, true) ?? [];
    
        $files = array_filter($files, function ($file) use ($filePathToDelete) {
            return $file['path'] !== $filePathToDelete;
        });
    
        // Eliminar físicamente el archivo
        \Storage::disk('public')->delete($filePathToDelete);
    
        $lead->$type = array_values($files);
        $lead->save();
    
        return redirect()->back()->with('success', 'File successfully deleted');
    }
    
    
    
    




    public function updateStatus(Request $request, $id)
    {
        $user = Auth::guard('team')->user();
        $lead = Lead::where('id', $id)->where('team_id', $user->id)->firstOrFail();

        // Validación del estado
        $request->validate([
            'estado' => 'required|integer|between:1,5'
        ]);

        // Verificar si se avanza o retrocede
        if ($request->change_status == "next" && $lead->estado < 5) {
            $lead->estado += 1;
        } elseif ($request->change_status == "back" && $lead->estado > 1) {
            $lead->estado -= 1;
        } else {
            $lead->estado = $request->estado; // Cambio manual
        }

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
