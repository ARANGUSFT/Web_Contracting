<?php

namespace App\Http\Controllers;

use App\Models\Subcontractors;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InsuranceController extends Controller
{
    public function index()
    {
        $subcontractors = Subcontractors::where('is_active', true)->get();
        return view('admin.insurances.index', compact('subcontractors'));
    }

    public function create(Subcontractors $sub)
    {
        return view('admin.insurances.create', compact('sub'));
    }

    public function store(Request $r, Subcontractors $sub)
    {
        // Validación de los campos
        $r->validate([
            'expires_at' => 'required|date',
            'file' => 'required',
            'file.*' => 'file|mimes:pdf,jpg,png|max:5120',
            'notes' => 'nullable|string',
        ]);
    
        $files = [];
    
        // Recorremos los archivos subidos y guardamos tanto el path como el nombre original
        foreach ($r->file('file') as $uploadedFile) {
            $storedPath = $uploadedFile->store('insurances', 'public');
            $files[] = [
                'path' => $storedPath,
                'original_name' => $uploadedFile->getClientOriginalName(),
            ];
        }
    
        // Guardamos el registro con los archivos en formato JSON
        $sub->insurances()->create([
            'expires_at' => $r->expires_at,
            'file' => $files,
            'notes' => $r->notes,
        ]);
    
        return redirect()
            ->route('superadmin.subcontractors.insurances.index')
            ->with('success', 'Insurance(s) added successfully.');
    }
    

    public function edit(Subcontractors $sub, Insurance $ins)
    {
        return view('admin.insurances.edit', compact('sub', 'ins'));
    }

    public function update(Request $r, Subcontractors $sub, Insurance $ins)
    {
        $r->validate([
            'expires_at' => 'required|date',
            'file.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'notes' => 'nullable|string',
            'delete_files' => 'nullable|array',
        ]);

        // Obtener los archivos actuales
        $currentFiles = $ins->file ?? [];

        // Eliminar archivos seleccionados
        if ($r->filled('delete_files')) {
            foreach ($r->delete_files as $deletePath) {
                foreach ($currentFiles as $key => $file) {
                    // Solo intenta acceder a 'path' si $file es un array y tiene 'path'
                    if (is_array($file) && isset($file['path']) && $file['path'] === $deletePath) {
                        if (Storage::exists($file['path'])) {
                            Storage::delete($file['path']);
                        }
                        unset($currentFiles[$key]); // eliminar del array
                    }
                }
            }
        
            $currentFiles = array_values($currentFiles); // reindexar
        }
        

        // Agregar nuevos archivos si los hay
        if ($r->hasFile('file')) {
            foreach ($r->file('file') as $uploadedFile) {
                $storedPath = $uploadedFile->store('insurances', 'public');
                $currentFiles[] = [
                    'path' => $storedPath,
                    'original_name' => $uploadedFile->getClientOriginalName(),
                ];
            }
        }

        // Actualizar el registro
        $ins->update([
            'expires_at' => $r->expires_at,
            'file' => $currentFiles,
            'notes' => $r->notes,
        ]);

        return redirect()
            ->route('superadmin.subcontractors.insurances.index')
            ->with('success', 'Insurance updated successfully.');
    }


    public function destroy(Subcontractors $sub, Insurance $ins)
    {
        // Verifica si es un array y elimina cada archivo
        if (is_array($ins->file)) {
            foreach ($ins->file as $file) {
                if (isset($file['path']) && Storage::exists($file['path'])) {
                    Storage::delete($file['path']);
                }
            }
        }
    
        // Elimina el registro del seguro
        $ins->delete();
    
        return back()->with('success', 'Insurance removed');
    }
    
}
