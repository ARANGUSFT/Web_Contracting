<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobRequest;
use App\Models\Emergencies;
use App\Models\Crew;
use App\Models\EventCompany;
use App\Models\Subcontractors;
use App\Models\EventNote;
use Illuminate\Support\Facades\Auth;

class EventCalendarController extends Controller
{
  

    public function index()
    {
        $crews = Crew::all();

        $jobCompanies   = JobRequest::distinct('company_name')->pluck('company_name')->toArray();
        $emergCompanies = Emergencies::distinct('company_name')->pluck('company_name')->toArray();
        $allNames       = array_unique(array_merge($jobCompanies, $emergCompanies));

        $saved = EventCompany::pluck('color','name')->toArray();

        $companiesForView = array_map(function($name) use ($saved) {
            return [
                'name'  => $name,
                'color' => $saved[$name] ?? '#3788d8',
            ];
        }, $allNames);

        // NUEVO: Estados de trabajos
        $jobStatuses   = JobRequest::select('id', 'status')->get();
        $emergStatuses = Emergencies::select('id', 'status')->get();

        return view('admin.calendar.index', [
            'crews'         => $crews,
            'companies'     => $companiesForView,
            'jobStatuses'   => $jobStatuses,
            'emergStatuses' => $emergStatuses,
        ]);
    }

    public function events(Request $request)
    {
        $start = $request->query('start');
        $end   = $request->query('end');

        // Trae todas las empresas con color
        $companyColors = EventCompany::pluck('color', 'name')->toArray();

        // Generador de color por defecto si falta
        $fallbackPalette = ['#1f77b4', '#ff7f0e', /*...*/];
        $assigned = [];

        $getColor = function($company) use (&$companyColors, &$assigned, $fallbackPalette) {
            if (isset($companyColors[$company])) {
                return $companyColors[$company];
            }
            if (!isset($assigned[$company])) {
                $assigned[$company] = $fallbackPalette[count($assigned) % count($fallbackPalette)];
            }
            return $assigned[$company];
        };
        $events = [];

        // JobRequests
        JobRequest::whereBetween('install_date_requested', [$start, $end])->get()
        ->each(function($job) use (&$events, $getColor) {
            $color = $getColor($job->company_name);
            $events[] = [
                'id'          => $job->id,
                'title'       => "Job #{$job->job_number_name}",
                'start'       => $job->install_date_requested->toDateString(),
                'color'       => $color,
                'type'        => 'job',
                'company_name'=> $job->company_name,
                'crewName'    => $job->crew ? $job->crew->name : null,  // ← añadimos esto
            ];
        });

        // Emergencies
        Emergencies::whereBetween('date_submitted', [$start, $end])->get()
        ->each(function($e) use (&$events, $getColor) {
            $color = $getColor($e->company_name);
            $events[] = [
                'id'          => $e->id,
                'title'       => "Emergency #{$e->job_number_name}",
                'start'       => $e->date_submitted->toDateString(),
                'color'       => $color,
                'type'        => 'emergency',
                'company_name'=> $e->company_name,
                'crewName'    => $e->crew ? $e->crew->name : null,  // ← y aquí
            ];
        });


        return response()->json($events);
    }


    public function show(string $type, int $id)
    {
        if ($type === 'job') {
            $item = JobRequest::with('crew', 'notes.user')->findOrFail($id);
    
            $data = [
                // General Info
                'install_date_requested'            => $item->install_date_requested->toDateString(),
                'company_name'                      => $item->company_name,
                'company_rep'                       => $item->company_rep,
                'company_rep_phone'                 => $item->company_rep_phone,
                'company_rep_email'                 => $item->company_rep_email,
    
                // Customer
                'customer_first_name'               => $item->customer_first_name,
                'customer_last_name'                => $item->customer_last_name,
                'customer_phone_number'             => $item->customer_phone_number,
    
                // Address
                'job_address_street_address'        => $item->job_address_street_address,
                'job_address_street_address_line_2' => $item->job_address_street_address_line_2,
                'job_address_city'                  => $item->job_address_city,
                'job_address_state'                 => $item->job_address_state,
                'job_address_zip_code'              => $item->job_address_zip_code,
                'job_number_name'                   => $item->job_number_name,
    
                // Materials & Delivery
                'material_roof_loaded'              => $item->material_roof_loaded,
                'starter_bundles_ordered'           => $item->starter_bundles_ordered,
                'hip_and_ridge_ordered'             => $item->hip_and_ridge_ordered,
                'field_shingle_bundles_ordered'     => $item->field_shingle_bundles_ordered,
                'modified_bitumen_cap_rolls_ordered'=> $item->modified_bitumen_cap_rolls_ordered,
                'delivery_date'                     => optional($item->delivery_date)->toDateString(),
    
                // Inspections & Replacements
                'mid_roof_inspection'               => $item->mid_roof_inspection,
                'siding_being_replaced'             => $item->siding_being_replaced,
                'asphalt_shingle_layers_to_remove'  => $item->asphalt_shingle_layers_to_remove,
                're_deck'                           => $item->re_deck,
                'skylights_replace'                 => $item->skylights_replace,
                'gutter_remove'                     => $item->gutter_remove,
                'gutter_detached_and_reset'         => $item->gutter_detached_and_reset,
                'satellite_remove'                  => $item->satellite_remove,
                'satellite_goes_in_the_trash'       => $item->satellite_goes_in_the_trash,
                'open_soffit_ceiling'               => $item->open_soffit_ceiling,
                'detached_garage_roof'              => $item->detached_garage_roof,
                'detached_shed_roof'                => $item->detached_shed_roof,
    
                // Additional
                'special_instructions'              => $item->special_instructions,
                'material_verification'             => $item->material_verification,
                'stop_work_request'                 => $item->stop_work_request,
                'documentationattachment'           => $item->documentationattachment,
    
                // Files
                'aerial_measurement'                => $item->aerial_measurement,
                'material_order'                    => $item->material_order,
                'file_upload'                       => $item->file_upload,
    
                // Common
                'crew_id'                           => $item->crew_id,
                'crew_name'                         => optional($item->crew)->name,
                'notes'                             => $item->notes->map(fn($n) => [
                    'id'        => $n->id,
                    'content'   => $n->content,
                    'user_name' => $n->user->name,
                    'created_at'=> $n->created_at->format('d/m/Y H:i'),
                ])->all(),
            ];
        } else {
            $item = Emergencies::with('crew', 'notes.user')->findOrFail($id);
    
            $data = [
                // General Info
                'date_submitted'          => $item->date_submitted->toDateString(),
                'type_of_supplement'      => $item->type_of_supplement,
                'company_name'            => $item->company_name,
                'company_contact_email'   => $item->company_contact_email,
                'job_number_name'         => $item->job_number_name,
    
                // Address
                'job_address'             => $item->job_address,
                'job_address_line2'       => $item->job_address_line2,
                'job_city'                => $item->job_city,
                'job_state'               => $item->job_state,
                'job_zip_code'            => $item->job_zip_code,
    
                // Terms & Requirements
                'terms_conditions'        => $item->terms_conditions,
                'requirements'            => $item->requirements,
    
                // Files
                'aerial_measurement_path' => $item->aerial_measurement_path,
                'contract_upload_path'    => $item->contract_upload_path,
                'file_picture_upload_path'=> $item->file_picture_upload_path,
    
                // Common
                'crew_id'                 => $item->crew_id,
                'crew_name'               => optional($item->crew)->name,
                'notes'                   => $item->notes->map(fn($n) => [
                    'id'        => $n->id,
                    'content'   => $n->content,
                    'user_name' => $n->user->name,
                    'created_at'=> $n->created_at->format('d/m/Y H:i'),
                ])->all(),
            ];
        }
    
        return response()->json([
            'type' => $type,
            'data' => $data,
        ]);
    }
    
    
    
    


    public function assignCrew(Request $request)
    {
        $data = $request->validate([
            'type'    => 'required|in:job,emergency',
            'id'      => 'required|integer',
            'crew_id' => 'required|exists:crews,id',
        ]);

        if ($data['type'] === 'job') {
            $model = JobRequest::findOrFail($data['id']);
        } else {
            $model = Emergencies::findOrFail($data['id']);
        }

        $model->crew_id = $data['crew_id'];
        $model->save();

        return response()->json(['success' => true]);
    }


    public function fetchNotes(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:job,emergency',
            'id'   => 'required|integer',
        ]);

        $model = $data['type'] === 'job'
            ? JobRequest::find($data['id'])
            : Emergencies::find($data['id']);

        if (!$model) {
            return response()->json(['error' => 'Modelo no encontrado'], 404);
        }

        $notes = $model->notes()->with(['user', 'subcontractor'])->orderBy('created_at')->get();

        return response()->json($notes->map(function ($note) {
            return [
                'id' => $note->id,
                'content' => $note->content,
                'user_id' => $note->user_id ?? $note->subcontractor_id,
                'user_name' => $note->user->name ?? $note->subcontractor->name,
                'created_at' => $note->created_at->format('d/m/Y H:i'),
            ];
        }));
    }

    public function storeNote(Request $request)
    {
        $data = $request->validate([
            'type'    => 'required|in:job,emergency',
            'id'      => 'required|integer',
            'content' => 'required|string|max:1000',
        ]);

        $model = $data['type'] === 'job'
            ? JobRequest::find($data['id'])
            : Emergencies::find($data['id']);

        if (!$model) {
            return response()->json(['error' => 'Modelo no encontrado'], 404);
        }

        $user = $request->user();

        $note = $model->notes()->create([
            'content' => $data['content'],
            'user_id' => $user instanceof \App\Models\Subcontractors ? null : $user->id,
            'subcontractor_id' => $user instanceof \App\Models\Subcontractors ? $user->id : null,
        ]);

        return response()->json([
            'id' => $note->id,
            'content' => $note->content,
            'user_id' => $note->user_id ?? $note->subcontractor_id,
            'user_name' => $note->user->name ?? $note->subcontractor->name,
            'created_at' => $note->created_at->format('d/m/Y H:i'),
        ]);
    }




     public function updateVisibility(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|string|exists:event_companies,name',
            'active' => 'required|boolean',
        ]);

        EventCompany::updateOrCreate(
            ['name' => $data['name']],
            ['is_active' => $data['active']]
        );

        return response()->json(['success' => true]);
    }

       public function updateColor(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string',
            'color' => 'required|regex:/^#([0-9A-Fa-f]{6})$/',
        ]);

        EventCompany::updateOrCreate(
            ['name' => $data['name']],
            ['color' => $data['color']]
        );

        return response()->json(['success' => true]);
    }


    

    public function trabajosAsignados($subcontractor_id)
    {
        $sub = Subcontractors::find($subcontractor_id);

        if (!$sub) {
            return response()->json(['message' => 'Subcontractor not found'], 404);
        }

        $crew = $sub->crews()->first();

        if (!$crew) {
            return response()->json(['message' => 'No crew assigned'], 404);
        }

        $jobs = JobRequest::where('crew_id', $crew->id)->get();
        $emergencies = Emergencies::where('crew_id', $crew->id)->get();

        $trabajos = collect()
            ->merge($jobs->map(function ($job) {
                return [
                    'id' => $job->id,
                    'type' => 'job',
                    'title' => $job->title ?? 'Job Request',
                    'date' => $job->created_at,
                    // General Info
                    'install_date_requested'            => $job->install_date_requested->toDateString(),
                    'company_name'                      => $job->company_name,
                    'company_rep'                       => $job->company_rep,
                    'company_rep_phone'                 => $job->company_rep_phone,
                    'company_rep_email'                 => $job->company_rep_email,
        
                    // Customer
                    'customer_first_name'               => $job->customer_first_name,
                    'customer_last_name'                => $job->customer_last_name,
                    'customer_phone_number'             => $job->customer_phone_number,
        
                    // Address
                    'job_address_street_address'        => $job->job_address_street_address,
                    'job_address_street_address_line_2' => $job->job_address_street_address_line_2,
                    'job_address_city'                  => $job->job_address_city,
                    'job_address_state'                 => $job->job_address_state,
                    'job_address_zip_code'              => $job->job_address_zip_code,
                    'job_number_name'                   => $job->job_number_name,
                ];
            }))
            ->merge($emergencies->map(function ($emergency) {
                return [
                    'id' => $emergency->id,
                    'type' => 'emergency',
                    'title' => $emergency->title ?? 'Emergency',
                    'date' => $emergency->created_at,
                     // General Info
                    'date_submitted'          => $emergency->date_submitted->toDateString(),
                    'type_of_supplement'      => $emergency->type_of_supplement,
                    'company_name'            => $emergency->company_name,
                    'company_contact_email'   => $emergency->company_contact_email,
                    'job_number_name'         => $emergency->job_number_name,
        
                    // Address
                    'job_address'             => $emergency->job_address,
                    'job_address_line2'       => $emergency->job_address_line2,
                    'job_city'                => $emergency->job_city,
                    'job_state'               => $emergency->job_state,
                    'job_zip_code'            => $emergency->job_zip_code,

                    
                ];
            }));

        return response()->json($trabajos->values());
    }


    public function showTrabajo(string $type, int $id)
    {
        if ($type === 'job') {
            $item = JobRequest::findOrFail($id);
            return response()->json([
                'id' => $item->id,
                'type' => 'job',
                'title' => $item->title ?? 'Job Request',
                'install_date_requested' => optional($item->install_date_requested)->toDateString(),
                'company_name' => $item->company_name,
                'company_rep' => $item->company_rep,
                'company_rep_phone' => $item->company_rep_phone,
                'company_rep_email' => $item->company_rep_email,
                'customer_first_name' => $item->customer_first_name,
                'customer_last_name' => $item->customer_last_name,
                'customer_phone_number' => $item->customer_phone_number,
                'job_number_name' => $item->job_number_name,
                'job_address_street_address' => $item->job_address_street_address,
                'job_address_street_address_line_2' => $item->job_address_street_address_line_2,
                'job_address_city' => $item->job_address_city,
                'job_address_state' => $item->job_address_state,
                'job_address_zip_code' => $item->job_address_zip_code,
                'material_roof_loaded' => $item->material_roof_loaded,
                'starter_bundles_ordered' => $item->starter_bundles_ordered,
                'hip_and_ridge_ordered' => $item->hip_and_ridge_ordered,
                'field_shingle_bundles_ordered' => $item->field_shingle_bundles_ordered,
                'modified_bitumen_cap_rolls_ordered' => $item->modified_bitumen_cap_rolls_ordered,
                'delivery_date' => optional($item->delivery_date)->toDateString(),
                'mid_roof_inspection' => $item->mid_roof_inspection,
                'siding_being_replaced' => $item->siding_being_replaced,
                'asphalt_shingle_layers_to_remove' => $item->asphalt_shingle_layers_to_remove,
                're_deck' => $item->re_deck,
                'skylights_replace' => $item->skylights_replace,
                'gutter_remove' => $item->gutter_remove,
                'gutter_detached_and_reset' => $item->gutter_detached_and_reset,
                'satellite_remove' => $item->satellite_remove,
                'satellite_goes_in_the_trash' => $item->satellite_goes_in_the_trash,
                'open_soffit_ceiling' => $item->open_soffit_ceiling,
                'detached_garage_roof' => $item->detached_garage_roof,
                'detached_shed_roof' => $item->detached_shed_roof,
                'special_instructions' => $item->special_instructions,
                'material_verification' => $item->material_verification,
                'stop_work_request' => $item->stop_work_request,
                'documentationattachment' => $item->documentationattachment,
                'created_at' => $item->created_at->toDateString(),
            ]);
        } else {
            $item = Emergencies::findOrFail($id);
            return response()->json([
                'id' => $item->id,
                'type' => 'emergency',
                'title' => $item->title ?? 'Emergency',
                'date_submitted' => $item->date_submitted->toDateString(),
                'company_name' => $item->company_name,
                'company_contact_email' => $item->company_contact_email,
                'type_of_supplement' => $item->type_of_supplement,
                'job_number_name' => $item->job_number_name,
                'job_address' => $item->job_address,
                'job_address_line2' => $item->job_address_line2,
                'job_city' => $item->job_city,
                'job_state' => $item->job_state,
                'job_zip_code' => $item->job_zip_code,
                'terms_conditions' => $item->terms_conditions,
                'requirements' => $item->requirements,
                'aerial_measurement_path' => $item->aerial_measurement_path,
                'contract_upload_path' => $item->contract_upload_path,
                'file_picture_upload_path' => $item->file_picture_upload_path,
                'created_at' => $item->created_at->toDateString(),
            ]);
        }
    }

 

}
