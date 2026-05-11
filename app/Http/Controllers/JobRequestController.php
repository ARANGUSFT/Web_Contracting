<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Offers;
use Illuminate\Http\Request;
use App\Models\JobRequest;
use App\Models\Team;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class JobRequestController extends Controller
{

    // ─────────────────────────────────────────────────────────────
    // HELPER — genera el siguiente número JR- de forma segura
    // ─────────────────────────────────────────────────────────────
    private function nextJrNumber(): string
    {
        $companyPrefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', auth()->user()->company_name ?? 'XXX'), 0, 3));

        $last = JobRequest::where('job_number_name', 'LIKE', "JB-{$companyPrefix}-%")
            ->get()
            ->map(function ($j) {
                if (preg_match('/^JB-[A-Z]{3}-(\d{4})$/', $j->job_number_name, $m)) {
                    return (int) $m[1];
                }
                return 0;
            })
            ->max() ?? 0;

        return 'JB-' . $companyPrefix . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    // ─────────────────────────────────────────────────────────────
    // CREATE — muestra el formulario
    // ─────────────────────────────────────────────────────────────
    public function create()
    {
        $user         = auth()->user();
        $teamMembers  = Team::whereIn('role', ['manager', 'project_manager', 'crew'])->get();
        $nextJrNumber = $this->nextJrNumber();

        return view('leads.pg.form.newjob', compact('user', 'teamMembers', 'nextJrNumber'));
    }


    // ─────────────────────────────────────────────────────────────
    // STORE — guarda el job
    // ─────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        // ── Sobreescribir job_number_name con el calculado en servidor ──
        $request->merge(['job_number_name' => $this->nextJrNumber()]);

        $validated = $request->validate([
            // Datos generales
            'install_date_requested'             => 'required|date',
            'company_name'                       => 'required|string',
            'company_rep'                        => 'required|string',
            'company_rep_phone'                  => 'required|string',
            'company_rep_email'                  => 'nullable|email',

            // Cliente
            'customer_first_name'                => 'required|string',
            'customer_last_name'                 => 'nullable|string',
            'customer_phone_number'              => 'required|string',

            // Dirección
            'job_number_name'                    => 'required|string|unique:job_requests,job_number_name',
            'job_address_street_address'         => 'required|string',
            'job_address_street_address_line_2'  => 'nullable|string',
            'job_address_city'                   => 'required|string',
            'job_address_state'                  => 'required|string',
            'job_address_zip_code'               => 'required|string',

            // Materiales
            'material_roof_loaded'               => 'required|in:Yes,No',
            'starter_bundles_ordered'            => 'nullable|integer',
            'hip_and_ridge_ordered'              => 'nullable|integer',
            'field_shingle_bundles_ordered'      => 'nullable|integer',
            'modified_bitumen_cap_rolls_ordered' => 'nullable|integer',
            'delivery_date'                      => 'nullable|date',

            // Inspecciones
            'mid_roof_inspection'                => 'nullable|in:Yes,No',
            'siding_being_replaced'              => 'nullable|in:Yes,No',
            'asphalt_shingle_layers_to_remove'   => 'nullable|integer',
            're_deck'                            => 'nullable|in:Yes,No',
            'skylights_replace'                  => 'nullable|in:Yes,No',
            'gutter_remove'                      => 'nullable|in:Yes,No',
            'gutter_detached_and_reset'          => 'nullable|in:Yes,No',
            'satellite_remove'                   => 'nullable|in:Yes,No',
            'satellite_goes_in_the_trash'        => 'nullable|in:Yes,No',
            'open_soffit_ceiling'                => 'nullable|in:Yes,No',
            'detached_garage_roof'               => 'nullable|in:Yes,No',
            'detached_shed_roof'                 => 'nullable|in:Yes,No',

            // Otros
            'special_instructions'               => 'nullable|string',
            'material_verification'              => 'nullable|boolean',
            'stop_work_request'                  => 'nullable|boolean',
            'documentationattachment'            => 'nullable|boolean',

            // Team
            'assigned_team_members'              => 'nullable|array',
            'assigned_team_members.*'            => 'exists:team,id',

            // Archivos
            'aerial_measurement.*'               => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'material_order.*'                   => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'file_upload.*'                      => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        // Archivos
        $validated['aerial_measurement'] = $this->handleMultipleFiles($request, 'aerial_measurement', 'job_requests/aerials');
        $validated['material_order']     = $this->handleMultipleFiles($request, 'material_order', 'job_requests/materials');
        $validated['file_upload']        = $this->handleMultipleFiles($request, 'file_upload', 'job_requests/files');

        // Checkboxes
        $validated['material_verification']   = $request->has('material_verification');
        $validated['stop_work_request']       = $request->has('stop_work_request');
        $validated['documentationattachment'] = $request->has('documentationattachment');

        $validated['user_id'] = auth()->id();

        try {
            $job = JobRequest::create($validated);

            if ($request->has('assigned_team_members')) {
                $job->teamMembers()->sync($request->input('assigned_team_members'));
            }

            Calendar::create([
                'title'        => 'Job: ' . $validated['job_number_name'],
                'start'        => $validated['install_date_requested'],
                'type'         => 'job',
                'reference_id' => $job->id,
                'color'        => '#0d6efd',
            ]);

            return redirect()->back()->with('success', 'Job Request submitted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al guardar el registro: ' . $e->getMessage()]);
        }
    }


    // ─────────────────────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────────────────────
    public function show($id)
    {
        $job = JobRequest::with('teamMembers')->findOrFail($id);

        return view('leads.pg.showR', compact('job'));
    }


    // ─────────────────────────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────────────────────────
    public function edit(JobRequest $job)
    {
        if ($job->install_date_requested) {
            $job->install_date_requested = \Carbon\Carbon::parse($job->install_date_requested)->format('Y-m-d');
        }
        if ($job->delivery_date) {
            $job->delivery_date = \Carbon\Carbon::parse($job->delivery_date)->format('Y-m-d');
        }

        $teamMembers = Team::whereIn('role', ['manager', 'project_manager', 'crew'])->get();

        return view('leads.pg.update.editJob', compact('job', 'teamMembers'));
    }


    // ─────────────────────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────────────────────
    public function update(Request $request, JobRequest $job)
    {
        $validated = $request->validate([
            'install_date_requested'             => 'required|date',
            'company_name'                       => 'required|string',
            'company_rep'                        => 'nullable|string',
            'company_rep_phone'                  => 'nullable|string',
            'company_rep_email'                  => 'nullable|email',

            'customer_first_name'                => 'nullable|string',
            'customer_last_name'                 => 'nullable|string',
            'customer_phone_number'              => 'nullable|string',

            // job_number_name NO se valida en update — nunca cambia
            'job_address_street_address'         => 'nullable|string',
            'job_address_street_address_line_2'  => 'nullable|string',
            'job_address_city'                   => 'nullable|string',
            'job_address_state'                  => 'nullable|string',
            'job_address_zip_code'               => 'nullable|string',

            'material_roof_loaded'               => 'nullable|string',
            'starter_bundles_ordered'            => 'nullable|string',
            'hip_and_ridge_ordered'              => 'nullable|string',
            'field_shingle_bundles_ordered'      => 'nullable|string',
            'modified_bitumen_cap_rolls_ordered' => 'nullable|string',
            'delivery_date'                      => 'nullable|date',

            'mid_roof_inspection'                => 'nullable|string',
            'siding_being_replaced'              => 'nullable|string',
            'asphalt_shingle_layers_to_remove'   => 'nullable|string',
            're_deck'                            => 'nullable|string',
            'skylights_replace'                  => 'nullable|string',
            'gutter_remove'                      => 'nullable|string',
            'gutter_detached_and_reset'          => 'nullable|string',
            'satellite_remove'                   => 'nullable|string',
            'satellite_goes_in_the_trash'        => 'nullable|string',
            'open_soffit_ceiling'                => 'nullable|string',
            'detached_garage_roof'               => 'nullable|string',
            'detached_shed_roof'                 => 'nullable|string',

            'special_instructions'               => 'nullable|string',

            'assigned_team_members'              => 'nullable|array',
            'assigned_team_members.*'            => 'exists:team,id',

            'aerial_measurement.*'               => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'material_order.*'                   => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'file_upload.*'                      => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        // Preservar job_number_name original — nunca se edita
        unset($validated['job_number_name']);

        foreach (['aerial_measurement', 'material_order', 'file_upload'] as $field) {
            $existingFiles = is_string($job->$field)
                ? json_decode($job->$field, true)
                : ($job->$field ?? []);

            $newFiles = [];

            if ($request->hasFile($field)) {
                foreach ($request->file($field) as $file) {
                    $originalName = $file->getClientOriginalName();
                    $path = $file->storeAs('job_requests/' . str_replace('_', '', $field), $originalName, 'public');
                    $newFiles[] = ['path' => $path, 'original_name' => $originalName];
                }
                $validated[$field] = json_encode(array_merge($existingFiles ?? [], $newFiles));
            } else {
                $validated[$field] = json_encode($existingFiles ?? []);
            }
        }

        $validated['material_verification']   = $request->boolean('material_verification');
        $validated['stop_work_request']       = $request->boolean('stop_work_request');
        $validated['documentationattachment'] = $request->boolean('documentationattachment');

        $job->update($validated);
        $job->teamMembers()->sync($request->input('assigned_team_members', []));

        return redirect()->route('jobs.show', $job->id)->with('success', 'Job updated successfully.');
    }


    // ─────────────────────────────────────────────────────────────
    // DELETE FILE (AJAX)
    // ─────────────────────────────────────────────────────────────
    public function deleteFile($jobId, $field, $index)
    {
        try {
            $job = JobRequest::findOrFail($jobId);

            $validFields = ['aerial_measurement', 'material_order', 'file_upload'];

            if (!in_array($field, $validFields)) {
                return response()->json(['error' => 'Invalid field'], 400);
            }

            $files = [];
            if (!empty($job->$field)) {
                $files = is_string($job->$field)
                    ? json_decode($job->$field, true)
                    : $job->$field;
                $files = is_array($files) ? $files : [];
            }

            if (!isset($files[$index])) {
                return response()->json(['error' => 'File not found'], 404);
            }

            $file = $files[$index];

            if (isset($file['path']) && Storage::disk('public')->exists($file['path'])) {
                Storage::disk('public')->delete($file['path']);
            }

            unset($files[$index]);
            $files = array_values($files);

            $job->$field = !empty($files) ? json_encode($files) : null;
            $job->save();

            return response()->json([
                'message'         => 'File deleted successfully',
                'remaining_files' => count($files),
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Job not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting file: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }


    // ─────────────────────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────────────────────
    public function destroy(JobRequest $job)
    {
        $job->delete();

        return redirect()->route('calendar.view')->with('success', 'Job deleted successfully.');
    }


    // ─────────────────────────────────────────────────────────────
    // PRIVATE — manejo de archivos múltiples
    // ─────────────────────────────────────────────────────────────
    private function handleMultipleFiles(Request $request, string $inputName, string $folder): string
    {
        $stored = [];

        if ($request->hasFile($inputName)) {
            foreach ($request->file($inputName) as $file) {
                $original = $file->getClientOriginalName();
                $path     = $file->storeAs($folder, $original, 'public');
                $stored[] = ['path' => $path, 'original_name' => $original];
            }
        }

        return json_encode($stored);
    }
}