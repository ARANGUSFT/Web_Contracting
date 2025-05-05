<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobRequest;


class JobRequestController extends Controller
{
    public function store(Request $request)
    {


        $validated = $request->validate([
            // Datos generales
            'install_date_requested' => 'required|date',
            'company_name' => 'required|string',
            'company_rep' => 'required|string',
            'company_rep_phone' => 'required|string',
            'company_rep_email' => 'nullable|email',

            // Cliente
            'customer_first_name' => 'required|string',
            'customer_last_name' => 'nullable|string',
            'customer_phone_number' => 'required|string',

            // Dirección del trabajo
            'job_number_name' => 'required|string',
            'job_address_street_address' => 'required|string',
            'job_address_street_address_line_2' => 'nullable|string',
            'job_address_city' => 'required|string',
            'job_address_state' => 'required|string',
            'job_address_zip_code' => 'required|string',

            // Materiales
            'material_roof_loaded' => 'required|in:Yes,No',
            'starter_bundles_ordered' => 'nullable|integer',
            'hip_and_ridge_ordered' => 'nullable|integer',
            'field_shingle_bundles_ordered' => 'nullable|integer',
            'modified_bitumen_cap_rolls_ordered' => 'nullable|integer',
            'delivery_date' => 'nullable|date',

            // Inspecciones
            'mid_roof_inspection' => 'nullable|in:Yes,No',
            'siding_being_replaced' => 'nullable|in:Yes,No',
            'asphalt_shingle_layers_to_remove' => 'nullable|integer',
            're_deck' => 'nullable|in:Yes,No',
            'skylights_replace' => 'nullable|in:Yes,No',
            'gutter_remove' => 'nullable|in:Yes,No',
            'gutter_detached_and_reset' => 'nullable|in:Yes,No',
            'satellite_remove' => 'nullable|in:Yes,No',
            'satellite_goes_in_the_trash' => 'nullable|in:Yes,No',
            'open_soffit_ceiling' => 'nullable|in:Yes,No',
            'detached_garage_roof' => 'nullable|in:Yes,No',
            'detached_shed_roof' => 'nullable|in:Yes,No',

            // Otros
            'special_instructions' => 'nullable|string',
            'material_verification' => 'nullable|boolean',
            'stop_work_request' => 'nullable|boolean',
            'documentationattachment' => 'nullable|boolean',

            // Archivos
            'aerial_measurement' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'material_order' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'file_upload' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        // Manejo de archivos
        if ($request->hasFile('aerial_measurement')) {
            $validated['aerial_measurement'] = $request->file('aerial_measurement')->store('job_requests/aerials', 'public');
        }

        if ($request->hasFile('material_order')) {
            $validated['material_order'] = $request->file('material_order')->store('job_requests/materials', 'public');
        }

        if ($request->hasFile('file_upload')) {
            $validated['file_upload'] = $request->file('file_upload')->store('job_requests/files', 'public');
        }

        // Booleanos de checkboxes
        $validated['material_verification'] = $request->has('material_verification');
        $validated['stop_work_request'] = $request->has('stop_work_request');
        $validated['documentationattachment'] = $request->has('documentationattachment');

        // Guardado con manejo de errores
        try {
            JobRequest::create($validated);
            return redirect()->back()->with('success', 'Job Request submitted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al guardar el registro: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        $user = auth()->user();
        return view('leads.pg.newjob', compact('user'));
    }
}
