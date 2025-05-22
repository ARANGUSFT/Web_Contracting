<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Emergencies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class EmergenciesController extends Controller
{
    public function store(Request $request)
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
            'terms_conditions' => 'accepted',
            'requirements' => 'accepted',
            'aerial_measurement.*' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'contract_upload.*' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'file_picture_upload.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $aerialPaths = [];
        foreach ($request->file('aerial_measurement', []) as $file) {
            $aerialPaths[] = $file->store('emergency/aerials', 'public');
        }

        $contractPaths = [];
        foreach ($request->file('contract_upload', []) as $file) {
            $contractPaths[] = $file->store('emergency/contracts', 'public');
        }

        $filePicturePaths = [];
        if ($request->hasFile('file_picture_upload')) {
            foreach ($request->file('file_picture_upload') as $file) {
                $filePicturePaths[] = $file->store('emergency/files', 'public');
            }
        }

        $emergency = Emergencies::create([ ...$validated, 'terms_conditions' => $request->has('terms_conditions'), 'requirements' => $request->has('requirements'), 'aerial_measurement_path' => $aerialPaths, 'contract_upload_path' => $contractPaths, 'file_picture_upload_path' => $filePicturePaths, ]); 

        // Crear evento en el calendario
        Calendar::create([
            'title' => 'Supplement: ' . $validated['job_number_name'],
            'start' => $validated['date_submitted'],
            'type' => 'emergency',
            'reference_id' => $emergency->id,
            'color' => '#dc3545', // rojo para emergencias
        ]);

        return redirect()->back()->with('success', 'Emergency request submitted successfully.');
        
    }

    public function form()
    {
        $user = auth()->user();
        return view('leads.pg.form.emergency', compact('user'));
    }

    public function show($id)
    {
        $emergency = Emergencies::findOrFail($id);
    
        return view('leads.pg.showE', compact('emergency'));
    }

    public function edit(Emergencies $emergency)
    {
        return view('leads.pg.update.editEmerg', compact('emergency'));
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
            'aerial_measurement.*' => 'sometimes|file|mimes:pdf,jpg,jpeg,png',
            'contract_upload.*' => 'sometimes|file|mimes:pdf,jpg,jpeg,png',
            'file_picture_upload.*' => 'sometimes|file|mimes:pdf,jpg,jpeg,png',
        ]);
    
        $emergency->fill($validated);
        $emergency->terms_conditions = $request->has('terms_conditions');
        $emergency->requirements = $request->has('requirements');
    
        // Añadir nuevos archivos sin borrar los anteriores
        $emergency->aerial_measurement_path = array_merge(
            $emergency->aerial_measurement_path ?? [],
            $this->handleMultipleFiles($request, 'aerial_measurement', 'emergencies/aerial')
        );
    
        $emergency->contract_upload_path = array_merge(
            $emergency->contract_upload_path ?? [],
            $this->handleMultipleFiles($request, 'contract_upload', 'emergencies/contracts')
        );
    
        $emergency->file_picture_upload_path = array_merge(
            $emergency->file_picture_upload_path ?? [],
            $this->handleMultipleFiles($request, 'file_picture_upload', 'emergencies/files')
        );
    
        $emergency->save();
    
        return redirect()->route('emergency.show', $emergency->id)->with('success', 'Emergency updated successfully.');
    }
    
    private function handleMultipleFiles(Request $request, string $inputName, string $folder)
    {
        $storedPaths = [];
    
        if ($request->hasFile($inputName)) {
            foreach ($request->file($inputName) as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs($folder, $filename, 'public');
                    $storedPaths[] = $path;
                }
            }
        }
    
        return $storedPaths;
    }
    

    public function deleteFile(Request $request)
    {
        $request->validate([
            'emergency_id' => 'required|integer|exists:emergencies,id',
            'file_path' => 'required|string',
        ]);

        $emergency = Emergencies::findOrFail($request->emergency_id);
        $filePath = $request->file_path;

        // Tipos de campos donde puede estar el archivo
        $fields = [
            'aerial_measurement_path',
            'contract_upload_path',
            'file_picture_upload_path',
        ];

        $fileDeleted = false;

        foreach ($fields as $field) {
            if (is_array($emergency->$field)) {
                $files = $emergency->$field;

                // Buscar el archivo y eliminarlo
                if (($key = array_search($filePath, $files)) !== false) {
                    // Borrar físicamente
                    Storage::disk('public')->delete($filePath);

                    // Quitar del array y guardar
                    unset($files[$key]);
                    $emergency->$field = array_values($files); // reindexar array
                    $fileDeleted = true;
                }
            }
        }

        if ($fileDeleted) {
            $emergency->save();
            return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'File not found in records.'], 404);
        }
    }
    


    public function destroy(Emergencies $emergency)
    {
        $emergency->delete();
        return redirect()->route('calendar.view')->with('success', 'Job deleted successfully.');
    }
    
}
