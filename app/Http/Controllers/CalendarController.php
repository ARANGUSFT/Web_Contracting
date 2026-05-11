<?php

namespace App\Http\Controllers;

use App\Models\Emergencies;
use App\Models\JobRequest;
use App\Models\RepairTicket;
use Carbon\Carbon;
use App\Models\Lead_approvals;

class CalendarController extends Controller
{
    private function statusColor(string $status): string
    {
        return match(strtolower(trim($status))) {
            'pending'                    => '#d97706',
            'scheduled'                  => '#2563eb',
            'in_progress', 'in progress' => '#7c3aed',
            'completed'                  => '#059669',
            'cancelled'                  => '#dc2626',
            default                      => '#475569',
        };
    }

    // ─────────────────────────────────────────────────────────────
    // ADMIN
    // ─────────────────────────────────────────────────────────────
    public function calendarData()
    {
        $userId = auth()->id();

        // ── Jobs ─────────────────────────────────────────────────
        $jobEvents = JobRequest::where('user_id', $userId)->get()->map(function ($job) {
            $status  = $job->status ?? 'pending';
            $address = collect([
                $job->job_address_street_address,
                $job->job_address_city,
                $job->job_address_state,
                $job->job_address_zip_code,
            ])->filter()->implode(', ');

            return [
                'title'  => $job->job_number_name,
                'start'  => Carbon::parse($job->install_date_requested)->toDateString(),
                'url'    => route('jobs.show', $job->id),
                'type'   => 'Job Request',
                'color'  => $this->statusColor($status),
                'extendedProps' => [
                    'type'    => 'Job Request',
                    'status'  => $status,
                    'address' => $address,
                    'company' => $job->company_name,
                ],
            ];
        });

        // ── Emergencies ──────────────────────────────────────────
        $emergencyEvents = Emergencies::where('user_id', $userId)->get()->map(function ($e) {
            $address = collect([
                $e->job_address,
                $e->job_city,
                $e->job_state,
                $e->job_zip_code,
            ])->filter()->implode(', ');

            return [
                'title'  => $e->job_number_name,
                'start'  => Carbon::parse($e->date_submitted)->toDateString(),
                'url'    => route('emergency.show', $e->id),
                'type'   => 'Emergency',
                'color'  => '#e11d48',
                'extendedProps' => [
                    'type'    => 'Emergency',
                    'status'  => $e->status ?? 'emergency',
                    'address' => $address,
                    'company' => $e->company_name,
                ],
            ];
        });

        // ── Lead Approvals ───────────────────────────────────────
        $approvalEvents = Lead_approvals::where('user_id', $userId)->get()->map(function ($approval) {
            return [
                'title'  => 'Approved Lead - ' . $approval->lead_name,
                'start'  => Carbon::parse($approval->installation_date)->toDateString(),
                'url'    => route('leads.show', $approval->lead_id),
                'type'   => 'Lead Approval',
                'color'  => '#6d28d9',
                'extendedProps' => [
                    'type'   => 'Lead Approval',
                    'status' => 'approval',
                ],
            ];
        });

        // ── Repair Tickets ───────────────────────────────────────
        $repairEvents = RepairTicket::where('user_id', $userId)
            ->with(['jobRequest', 'emergency'])
            ->get()
            ->map(function ($rt) {
                $ref = $rt->reference_type === 'job'
                    ? optional($rt->jobRequest)->job_number_name
                    : optional($rt->emergency)->job_number_name;

                $url = $rt->reference_type === 'job'
                    ? route('jobs.show', $rt->reference_id)
                    : route('emergency.show', $rt->reference_id);

                return [
                    'title'  => 'Repair: ' . ($ref ?? '#' . $rt->id),
                    'start'  => Carbon::parse($rt->repair_date)->toDateString(),
                    'url'    => $url,
                    'type'   => 'Repair Ticket',
                    'color'  => '#0891b2',
                    'extendedProps' => [
                        'type'    => 'Repair Ticket',
                        'status'  => $rt->status,
                        'company' => $ref,
                        'address' => null,
                    ],
                ];
            });

        $merged = collect()
            ->merge($jobEvents)
            ->merge($emergencyEvents)
            ->merge($approvalEvents)
            ->merge($repairEvents);

        return response()->json([
            'events'              => $merged->values(),
            'job_count'           => $jobEvents->count(),
            'emergency_count'     => $emergencyEvents->count(),
            'lead_approval_count' => $approvalEvents->count(),
            'repair_count'        => $repairEvents->count(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // CONTRACTOR
    // ─────────────────────────────────────────────────────────────
    public function contractorCalendarData()
    {
        $teamId = auth('team')->id();

        $jobEvents = JobRequest::whereHas('teams', function ($q) use ($teamId) {
            $q->where('team_id', $teamId);
        })->get()->map(function ($job) {
            $status  = $job->status ?? 'pending';
            $address = collect([
                $job->job_address_street_address,
                $job->job_address_city,
                $job->job_address_state,
                $job->job_address_zip_code,
            ])->filter()->implode(', ');

            return [
                'title'  => $job->job_number_name,
                'start'  => Carbon::parse($job->install_date_requested)->toDateString(),
                'url'    => route('jobs.show', $job->id),
                'color'  => $this->statusColor($status),
                'extendedProps' => [
                    'type'    => 'job',
                    'status'  => $status,
                    'address' => $address,
                    'company' => $job->company_name,
                    'notes'   => $job->special_instructions,
                ],
            ];
        });

        $emergencyEvents = Emergencies::whereHas('teams', function ($q) use ($teamId) {
            $q->where('team_id', $teamId);
        })->get()->map(function ($e) {
            $address = collect([
                $e->job_address,
                $e->job_city,
                $e->job_state,
                $e->job_zip_code,
            ])->filter()->implode(', ');

            return [
                'title'  => $e->job_number_name ?? $e->type_of_supplement ?? 'Emergency',
                'start'  => Carbon::parse($e->date_submitted)->toDateString(),
                'url'    => route('emergency.show', $e->id),
                'color'  => '#e11d48',
                'extendedProps' => [
                    'type'    => 'emergency',
                    'status'  => $e->status ?? 'emergency',
                    'address' => $address,
                    'company' => $e->company_name,
                    'notes'   => $e->requirements,
                ],
            ];
        });

        $merged = collect()
            ->merge($jobEvents)
            ->merge($emergencyEvents);

        return response()->json([
            'events'          => $merged->values(),
            'job_count'       => $jobEvents->count(),
            'emergency_count' => $emergencyEvents->count(),
        ]);
    }
}