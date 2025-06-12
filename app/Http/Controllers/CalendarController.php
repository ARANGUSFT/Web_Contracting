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
