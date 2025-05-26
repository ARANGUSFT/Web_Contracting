<?php

namespace App\Http\Controllers;

use App\Models\Emergencies;
use App\Models\JobRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Lead_approvals;


class CalendarController extends Controller
{
    public function calendarData()
    {
        // 🔹 Eventos de Job Requests
        $jobEvents = JobRequest::all()->map(function ($job) {
            return [
                'title' => $job->job_number_name,
                'start' => Carbon::parse($job->install_date_requested)->toDateString(),
                'url'   => route('jobs.show', $job->id),
                'type'  => 'Job Request',
                'color' => '#198754', // Verde (ejemplo para distinguir jobs)
            ];
        });

        // 🔹 Eventos de Emergencias
        $emergencyEvents = Emergencies::all()->map(function ($e) {
            return [
                'title' => 'Emergency - ' . $e->type_of_supplement,
                'start' => Carbon::parse($e->date_submitted)->toDateString(),
                'url'   => route('emergency.show', $e->id),
                'type'  => 'Emergency',
                'color' => '#e30a0a', // Rojo (ejemplo para emergencias)
            ];
        });


        // 🔹 Eventos de Leads Aprobados
        $approvalEvents = Lead_approvals::all()->map(function ($approval) {
            return [
                'title' => 'Approved Lead - ' . $approval->lead_name,
                'start' => Carbon::parse($approval->installation_date)->toDateString(),
                'url'   => route('leads.show', $approval->lead_id), // asegúrate que exista esta ruta
                'type'  => 'Lead Approval',
                'color' => '#670ebb', // Azul (ejemplo para leads aprobados)
            ];
        });


        // 🔹 Unir ambos arreglos
        $merged = $jobEvents->merge($emergencyEvents)->merge($approvalEvents);

        // 🔹 Respuesta JSON
        return response()->json([
            'events' => $merged->values(),
            'job_count' => $jobEvents->count(),
            'emergency_count' => $emergencyEvents->count(),
        ]);
    }
}
