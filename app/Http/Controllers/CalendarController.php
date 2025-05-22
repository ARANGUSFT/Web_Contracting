<?php

namespace App\Http\Controllers;

use App\Models\Emergencies;
use App\Models\JobRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
                'color' => '#dc3545', // Rojo (ejemplo para emergencias)
            ];
        });

        // 🔹 Unir ambos arreglos
        $merged = $jobEvents->merge($emergencyEvents);

        // 🔹 Respuesta JSON
        return response()->json([
            'events' => $merged->values(),
            'job_count' => $jobEvents->count(),
            'emergency_count' => $emergencyEvents->count(),
        ]);
    }
}
