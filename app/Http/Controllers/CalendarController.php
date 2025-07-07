<?php

namespace App\Http\Controllers;

use App\Models\Emergencies;
use App\Models\JobRequest;
use App\Models\Crew;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Lead_approvals;

class CalendarController extends Controller
{

    // Muestra la vista con el calendario
    // En tu controlador
    public function index()
    {
        $crews = Crew::where('is_active', true)->get();
        return view('admin.calendar.index', compact('crews'));
    }

    // Devuelve un array JSON con eventos de ambos modelos
    public function events(Request $request)
    {
        $start = Carbon::parse($request->start)->startOfDay();
        $end   = Carbon::parse($request->end)->endOfDay();

        $events = [];

        // Emergencies
        Emergencies::whereBetween('date_submitted', [$start, $end])
            ->get()
            ->each(function($e) use (&$events) {
                $events[] = [
                    'id'    => 'e'.$e->id,
                    'title' => 'Emergency: '.$e->job_number_name,
                    'start' => Carbon::parse($e->date_submitted)->toDateString(),
                    'color' => '#dc3545',
                    'extendedProps' => [
                        'type'    => 'emergency',
                        'payload' => [
                            'user_id' => $e->user_id,
                            'crew_id' => $e->crew_id,
                            'date_submitted' => $e->date_submitted,
                            'type_of_supplement' => $e->type_of_supplement,
                            'company_name' => $e->company_name,
                            'company_contact_email' => $e->company_contact_email,
                            'job_number_name' => $e->job_number_name,
                            'job_address' => "{$e->job_address}, {$e->job_address_line2}, {$e->job_city}, {$e->job_state}, {$e->job_zip_code}",
                            'job_address_line2' => $e->job_address_line2,
                            'job_city' => $e->job_city,
                            'job_state' => $e->job_state,
                            'job_zip_code' => $e->job_zip_code,
                            'terms_conditions' => $e->terms_conditions,
                            'requirements' => $e->requirements,
                            'aerial_measurement_path' => $e->aerial_measurement_path,
                            'contract_upload_path' => $e->contract_upload_path,
                            'file_picture_upload_path' => $e->file_picture_upload_path,
                            'created_at' => $e->created_at,
                            'updated_at' => $e->updated_at
                        ],
                    ],
                ];
            });
            // === JobRequests ===
            JobRequest::whereBetween('install_date_requested', [$start, $end])
            ->get()
            ->each(function($j) use (&$events) {
                $events[] = [
                    'id'    => 'j'.$j->id,
                    'title' => $j->job_number_name,
                    'start' => Carbon::parse($j->install_date_requested)->toDateString(),
                    'color' => '#198754',
                    'extendedProps' => [
                        'type'    => 'job',
                        'payload' => [
                            // IDs y fechas
                            'user_id'                       => $j->user_id,
                            'crew_id'                       => $j->crew_id,

                            // Información general
                            'company_name'                  => $j->company_name,
                            'company_rep'                   => $j->company_rep,
                            'company_rep_phone'             => $j->company_rep_phone,
                            'company_rep_email'             => $j->company_rep_email,

                            // Cliente
                            'customer_first_name'           => $j->customer_first_name,
                            'customer_last_name'            => $j->customer_last_name,
                            'customer_phone_number'         => $j->customer_phone_number,

                            // Dirección
                            'job_address_street_address'       => $j->job_address_street_address,
                            'job_address_street_address_line_2'=> $j->job_address_street_address_line_2,
                            'job_address_city'                 => $j->job_address_city,
                            'job_address_state'                => $j->job_address_state,
                            'job_address_zip_code'             => $j->job_address_zip_code,

                            // Material Ordered
                            'material_roof_loaded'           => $j->material_roof_loaded,
                            'starter_bundles_ordered'        => $j->starter_bundles_ordered,
                            'hip_and_ridge_ordered'          => $j->hip_and_ridge_ordered,
                            'field_shingle_bundles_ordered'  => $j->field_shingle_bundles_ordered,
                            'modified_bitumen_cap_rolls_ordered'
                                                            => $j->modified_bitumen_cap_rolls_ordered,

                            // Inspecciones y reemplazos
                            'mid_roof_inspection'            => $j->mid_roof_inspection,
                            'siding_being_replaced'          => $j->siding_being_replaced,
                            'asphalt_shingle_layers_to_remove'
                                                            => $j->asphalt_shingle_layers_to_remove,
                            're_deck'                        => $j->re_deck,
                            'skylights_replace'              => $j->skylights_replace,
                            'gutter_remove'                  => $j->gutter_remove,
                            'gutter_detached_and_reset'      => $j->gutter_detached_and_reset,
                            'satellite_remove'               => $j->satellite_remove,
                            'satellite_goes_in_the_trash'    => $j->satellite_goes_in_the_trash,
                            'open_soffit_ceiling'            => $j->open_soffit_ceiling,
                            'detached_garage_roof'           => $j->detached_garage_roof,
                            'detached_shed_roof'             => $j->detached_shed_roof,

                            // Adicional
                            'special_instructions'           => $j->special_instructions,
                            'material_verification'          => $j->material_verification,
                            'stop_work_request'              => $j->stop_work_request,
                            'documentationattachment'        => $j->documentationattachment,

                    

                            // Conteo de equipo
                            'team_members_count'             => $j->teamMembers()->count(),
                        ],
                    ],
                ];
            });


        return response()->json($events);
    }
    
    public function assignCrew(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|integer',
            'event_type' => 'required|string|in:emergency,job',
            'crew_id' => 'required|exists:crews,id'
        ]);
    
        try {
            if ($validated['event_type'] === 'emergency') {
                $emergency = Emergencies::findOrFail($validated['event_id']);
                $emergency->update(['crew_id' => $validated['crew_id']]);
            } elseif ($validated['event_type'] === 'job') {
                $job = JobRequest::findOrFail($validated['event_id']);
                $job->update(['crew_id' => $validated['crew_id']]);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Crew asignado correctamente'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar crew: ' . $e->getMessage()
            ], 500);
        }
    }
    

    public function calendarData()
    {
        $userId = auth()->id(); // ✅ Obtener ID del usuario autenticado

        // 🔹 Eventos de Job Requests del usuario
        $jobEvents = JobRequest::where('user_id', $userId)->get()->map(function ($job) {
            return [
                'title' => $job->job_number_name,
                'start' => Carbon::parse($job->install_date_requested)->toDateString(),
                'url'   => route('jobs.show', $job->id),
                'type'  => 'Job Request',
                'color' => '#198754', // Verde
            ];
        });

        // 🔹 Eventos de Emergencias del usuario
        $emergencyEvents = Emergencies::where('user_id', $userId)->get()->map(function ($e) {
            return [
                'title' => 'Emergency - ' . $e->type_of_supplement,
                'start' => Carbon::parse($e->date_submitted)->toDateString(),
                'url'   => route('emergency.show', $e->id),
                'type'  => 'Emergency',
                'color' => '#e30a0a', // Rojo
            ];
        });

        // 🔹 Eventos de Leads Aprobados (sin filtrar por usuario)
        $approvalEvents = Lead_approvals::where('user_id', $userId)->get()->map(function ($approval) {
            return [
                'title' => 'Approved Lead - ' . $approval->lead_name,
                'start' => Carbon::parse($approval->installation_date)->toDateString(),
                'url'   => route('leads.show', $approval->lead_id),
                'type'  => 'Lead Approval',
                'color' => '#670ebb', // Morado
            ];
        });

        // 🔹 Combinar todos los eventos en una colección
        $merged = collect()
            ->merge($jobEvents)
            ->merge($emergencyEvents)
            ->merge($approvalEvents);

        // 🔹 Devolver como JSON
        return response()->json([
            'events' => $merged->values(),
            'job_count' => $jobEvents->count(),
            'emergency_count' => $emergencyEvents->count(),
            'lead_approval_count' => $approvalEvents->count(),
        ]);
    }
    

}
