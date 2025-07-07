<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Offers;
use App\Models\Emergencies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Team;


class EmergenciesController extends Controller
{
   

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->back()->withErrors(['error' => 'Debe iniciar sesión para enviar la solicitud.']);
        }

        $validated = $request->validate([
            'date_submitted' => 'required|date',
            'type_of_supplement' => 'required|string',
            'company_name' => 'required|string',
            'company_contact_email' => 'required|email',
            'job_number_name' => 'required|string',
            'job_address' => 'required|string',
            'job_address_line2' => 'nullable|string',
            'job_city' => 'required|string',
            'job_state' => 'required|string',
            'job_zip_code' => 'required|string',
            'terms_conditions' => 'accepted',
            'requirements' => 'accepted',
            'aerial_measurement.*' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'contract_upload.*' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'file_picture_upload.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'assigned_team_members' => 'nullable|array',
            'assigned_team_members.*' => 'exists:team,id',
        ]);

        $validated['aerial_measurement_path']    = $this->handleMultipleFiles($request, 'aerial_measurement', 'emergency/aerials');
        $validated['contract_upload_path']       = $this->handleMultipleFiles($request, 'contract_upload', 'emergency/contracts');
        $validated['file_picture_upload_path']   = $this->handleMultipleFiles($request, 'file_picture_upload', 'emergency/files');

        $validated['terms_conditions'] = $request->has('terms_conditions');
        $validated['requirements']     = $request->has('requirements');

        
        $validated['user_id'] = auth()->id();

        $emergency = Emergencies::create($validated);     

        $emergency->teamMembers()->sync($request->input('assigned_team_members', []));

        Calendar::create([
            'title'        => 'Supplement: ' . $validated['job_number_name'],
            'start'        => $validated['date_submitted'],
            'type'         => 'emergency',
            'reference_id' => $emergency->id,
            'color'        => '#dc3545',
        ]);


        return redirect()->back()->with('success', 'Emergency request submitted successfully.');
    }


    public function form()
    {
        $user = auth()->user();
        $teamMembers = Team::whereIn('role', ['manager', 'project_manager', 'crew'])->get();
    
        return view('leads.pg.form.emergency', compact('user', 'teamMembers'));
    }
    

    public function show($id)
    {
        $emergency = Emergencies::with('teamMembers')->findOrFail($id);
    
        return view('leads.pg.showE', compact('emergency'));
    }
    

    public function edit(Emergencies $emergency)
    {
        $teamMembers = \App\Models\Team::whereIn('role', ['manager', 'project_manager', 'crew'])->get();
        return view('leads.pg.update.editEmerg', compact('emergency', 'teamMembers'));
    }


    public function update(Request $request, Emergencies $emergency)
    {
            $validated = $request->validate([
                'date_submitted' => 'required|date',
                'type_of_supplement' => 'required|string',
                'company_name' => 'required|string',
                'company_contact_email' => 'required|email',
                'job_number_name' => 'required|string',
                'job_address' => 'required|string',
                'job_address_line2' => 'nullable|string',
                'job_city' => 'required|string',
                'job_state' => 'required|string',
                'job_zip_code' => 'required|string',
                'terms_conditions' => 'nullable|boolean',
                'requirements' => 'nullable|boolean',

                'assigned_team_members' => 'nullable|array',
                'assigned_team_members.*' => 'exists:team,id',

                'aerial_measurement.*' => 'sometimes|file|mimes:pdf,jpg,jpeg,png',
                'contract_upload.*' => 'sometimes|file|mimes:pdf,jpg,jpeg,png',
                'file_picture_upload.*' => 'sometimes|file|mimes:pdf,jpg,jpeg,png',
            ]);

            // Actualizar campos principales
        $emergency->fill($validated);
        $emergency->terms_conditions = $request->has('terms_conditions');
        $emergency->requirements = $request->has('requirements');

        // Archivos adjuntos (agregar sin reemplazar los existentes)
        $emergency->aerial_measurement_path = array_merge(
            $emergency->aerial_measurement_path ?? [],
            $this->handleMultipleFiles($request, 'aerial_measurement', 'emergency/aerials')
        );

        $emergency->contract_upload_path = array_merge(
            $emergency->contract_upload_path ?? [],
            $this->handleMultipleFiles($request, 'contract_upload', 'emergency/contracts')
        );

        $emergency->file_picture_upload_path = array_merge(
            $emergency->file_picture_upload_path ?? [],
            $this->handleMultipleFiles($request, 'file_picture_upload', 'emergency/files')
        );

        $emergency->save();

        // Actualizar relación muchos a muchos
        $emergency->teamMembers()->sync($request->input('assigned_team_members', []));

        return redirect()->route('emergency.show', $emergency->id)->with('success', 'Emergency updated successfully.');
    }

    
    private function handleMultipleFiles(Request $request, string $inputName, string $folder)
    {
        $storedFiles = [];
    
        if ($request->hasFile($inputName)) {
            foreach ($request->file($inputName) as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->storeAs($folder, $originalName, 'public');
    
                $storedFiles[] = [
                    'path' => $path,
                    'original_name' => $originalName,
                ];
            }
        }
    
        return $storedFiles; // ✅ Retorna como array, Laravel lo convierte a JSON en la base de datos
    }
    

    public function deleteFile(Request $request)
    {
        $request->validate([
            'emergency_id' => 'required|integer|exists:emergencies,id',
            'file_path' => 'required|string',
        ]);

        $emergency = Emergencies::findOrFail($request->emergency_id);
        $filePath = $request->file_path;

        $fields = ['aerial_measurement_path', 'contract_upload_path', 'file_picture_upload_path'];
        $fileDeleted = false;

        foreach ($fields as $field) {
            if (is_array($emergency->$field)) {
                $files = $emergency->$field;

                $filtered = collect($files)->filter(function ($item) use ($filePath) {
                    if (is_string($item)) {
                        return $item !== $filePath;
                    } elseif (is_array($item)) {
                        return $item['path'] !== $filePath;
                    }
                    return true;
                })->values()->all();

                if (count($files) !== count($filtered)) {
                    // Borrar físicamente
                    Storage::disk('public')->delete($filePath);
                    $emergency->$field = $filtered;
                    $fileDeleted = true;
                }
            }
        }

        if ($fileDeleted) {
            $emergency->save();
            return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'File not found in records.'], 404);
    }


    public function destroy(Emergencies $emergency)
    {
        $emergency->delete();
        return redirect()->route('calendar.view')->with('success', 'Job deleted successfully.');
    }
    
}
