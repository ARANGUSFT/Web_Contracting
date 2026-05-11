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

    // ─────────────────────────────────────────────────────────────
    // HELPER — genera el siguiente número EM- de forma segura
    // ─────────────────────────────────────────────────────────────
    private function nextEmNumber(): string
    {
        $companyPrefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', auth()->user()->company_name ?? 'XXX'), 0, 3));

        $last = Emergencies::where('job_number_name', 'LIKE', "EM-{$companyPrefix}-%")
            ->get()
            ->map(function ($e) {
                if (preg_match('/^EM-[A-Z]{3}-(\d{4})$/', $e->job_number_name, $m)) {
                    return (int) $m[1];
                }
                return 0;
            })
            ->max() ?? 0;

        return 'EM-' . $companyPrefix . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }


    // ─────────────────────────────────────────────────────────────
    // FORM
    // ─────────────────────────────────────────────────────────────
    public function form()
    {
        $user        = auth()->user();
        $teamMembers = Team::whereIn('role', ['manager', 'project_manager', 'crew'])->get();
        $nextEmNumber = $this->nextEmNumber();

        return view('leads.pg.form.emergency', compact('user', 'teamMembers', 'nextEmNumber'));
    }


    // ─────────────────────────────────────────────────────────────
    // STORE
    // ─────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->back()->withErrors(['error' => 'Debe iniciar sesión para enviar la solicitud.']);
        }

        // ── Sobreescribir job_number_name con el calculado en servidor ──
        $request->merge(['job_number_name' => $this->nextEmNumber()]);

        $validated = $request->validate([
            'date_submitted'          => 'required|date',
            'type_of_supplement'      => 'required|string|max:500',
            'company_name'            => 'required|string',
            'company_contact_email'   => 'required|email',
            'job_number_name'         => 'required|string|unique:emergencies,job_number_name',
            'job_address'             => 'required|string',
            'job_address_line2'       => 'nullable|string',
            'job_city'                => 'required|string',
            'job_state'               => 'required|string',
            'job_zip_code'            => 'required|string',
            'terms_conditions'        => 'accepted',
            'requirements'            => 'accepted',
            'aerial_measurement.*'    => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'contract_upload.*'       => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'file_picture_upload.*'   => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'assigned_team_members'   => 'nullable|array',
            'assigned_team_members.*' => 'exists:team,id',
        ]);

        $validated['aerial_measurement_path']  = $this->handleMultipleFiles($request, 'aerial_measurement', 'emergency/aerials');
        $validated['contract_upload_path']     = $this->handleMultipleFiles($request, 'contract_upload', 'emergency/contracts');
        $validated['file_picture_upload_path'] = $this->handleMultipleFiles($request, 'file_picture_upload', 'emergency/files');

        $validated['terms_conditions'] = $request->has('terms_conditions');
        $validated['requirements']     = $request->has('requirements');
        $validated['user_id']          = auth()->id();
        $validated['status']           = $validated['status'] ?? 'pending';   // ← Status por defecto

        $emergency = Emergencies::create($validated);

        $emergency->teamMembers()->sync($request->input('assigned_team_members', []));

        Calendar::create([
            'title'        => 'Supplement: ' . $validated['job_number_name'],
            'start'        => $validated['date_submitted'],
            'type'         => 'emergency',
            'reference_id' => $emergency->id,
            'color'        => '#dc2626',   // ← Color consistente con la app
        ]);

        return redirect()->back()->with('success', 'Emergency request submitted successfully.');
    }


    // ─────────────────────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────────────────────
    public function show($id)
    {
        $emergency = Emergencies::with('teamMembers')->findOrFail($id);

        return view('leads.pg.showE', compact('emergency'));
    }


    // ─────────────────────────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────────────────────────
    public function edit(Emergencies $emergency)
    {
        $teamMembers = Team::whereIn('role', ['manager', 'project_manager', 'crew'])->get();

        return view('leads.pg.update.editEmerg', compact('emergency', 'teamMembers'));
    }


    // ─────────────────────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────────────────────
    public function update(Request $request, Emergencies $emergency)
    {
        $validated = $request->validate([
            'date_submitted'          => 'required|date',
            'type_of_supplement'      => 'required|string|max:500',
            'company_name'            => 'required|string',
            'company_contact_email'   => 'required|email',
            // job_number_name NO se valida — nunca cambia
            'job_address'             => 'required|string',
            'job_address_line2'       => 'nullable|string',
            'job_city'                => 'required|string',
            'job_state'               => 'required|string',
            'job_zip_code'            => 'required|string',
            'terms_conditions'        => 'nullable|boolean',
            'requirements'            => 'nullable|boolean',
            'assigned_team_members'   => 'nullable|array',
            'assigned_team_members.*' => 'exists:team,id',
            'aerial_measurement.*'    => 'sometimes|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'contract_upload.*'       => 'sometimes|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'file_picture_upload.*'   => 'sometimes|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);

        // Preservar el job_number_name original
        unset($validated['job_number_name']);

        $emergency->fill($validated);
        $emergency->terms_conditions = $request->has('terms_conditions');
        $emergency->requirements     = $request->has('requirements');

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

        $emergency->teamMembers()->sync($request->input('assigned_team_members', []));

        return redirect()->route('emergency.show', $emergency->id)
            ->with('success', 'Emergency updated successfully.');
    }


    // ─────────────────────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────────────────────
    public function destroy(Emergencies $emergency)
    {
        $emergency->delete();

        return redirect()->route('calendar.view')->with('success', 'Emergency deleted successfully.');
    }


    // ─────────────────────────────────────────────────────────────
    // DELETE FILE (AJAX)
    // ─────────────────────────────────────────────────────────────
    public function deleteFile(Request $request)
    {
        $request->validate([
            'emergency_id' => 'required|integer|exists:emergencies,id',
            'file_path'    => 'required|string',
        ]);

        $emergency = Emergencies::findOrFail($request->emergency_id);
        $filePath  = $request->file_path;

        $fields     = ['aerial_measurement_path', 'contract_upload_path', 'file_picture_upload_path'];
        $fileDeleted = false;

        foreach ($fields as $field) {
            if (!is_array($emergency->$field)) continue;

            $filtered = collect($emergency->$field)->filter(function ($item) use ($filePath) {
                return is_array($item) ? $item['path'] !== $filePath : $item !== $filePath;
            })->values()->all();

            if (count($emergency->$field) !== count($filtered)) {
                Storage::disk('public')->delete($filePath);
                $emergency->$field = $filtered;
                $fileDeleted = true;
            }
        }

        if ($fileDeleted) {
            $emergency->save();
            return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'File not found.'], 404);
    }


    // ─────────────────────────────────────────────────────────────
    // PRIVATE — manejo de archivos múltiples
    // ─────────────────────────────────────────────────────────────
    private function handleMultipleFiles(Request $request, string $inputName, string $folder): array
    {
        $stored = [];

        if ($request->hasFile($inputName)) {
            foreach ($request->file($inputName) as $file) {
                $original = $file->getClientOriginalName();
                $path     = $file->storeAs($folder, $original, 'public');
                $stored[] = ['path' => $path, 'original_name' => $original];
            }
        }

        return $stored;
    }
}