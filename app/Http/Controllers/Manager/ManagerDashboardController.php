<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\JobRequest;
use App\Models\Emergencies;
use App\Models\Lead_approvals;
use Carbon\Carbon;

class ManagerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:team']);
    }

    /**
     * Display manager dashboard with leads and statistics
     */
    public function index(Request $request)
    {
        $user = Auth::guard('team')->user();
        
        return view('manageTeam.manager.dashboard', [
            'leads' => $this->getLeads($user, $request->input('seller_id')),
            'sellers' => $this->getSellers($user),
            'sellerId' => $request->input('seller_id'),
            'statusCounts' => $this->getStatusCounts($user),
            'statusSums' => $this->getStatusSums($user)
        ]);
    }

    /**
     * Display lead details view
     */
    public function show($id) 
    {
        $lead = Lead::with([
            'messages.user',
            'messages.team',
            'images',
            'files',
            'expenses',
            'finanzas',
            'team'
        ])->findOrFail($id);

        return view('manageTeam.manager.view', [
            'lead' => $lead,
            'messages' => $lead->messages->sortBy('created_at'),
            'images' => $lead->images->sortByDesc('created_at'),
            'statusMap' => $this->getStatusMap()
        ]);
    }

    /**
     * Update lead status
     */
    public function assignStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|integer|between:1,7']);

        $lead = Lead::findOrFail($id);
        $lead->update([
            'estado' => $request->status,
            'last_touched_at' => now()
        ]);

        return back()->with('success', 'Lead status updated successfully.');
    }

    /**
     * Submit approved lead data
     */
    public function submitApprovedData(Request $request, $id)
    {
        $request->validate([
            'lead_name' => 'required|string|max:255',
            'lead_address' => 'required|string|max:255',
            'lead_phone' => 'required|string|max:20',
            'installation_date' => 'required|date',
            'extra_info' => 'nullable|string|max:1000',
        ]);

        $lead = Lead::with('user')->findOrFail($id);
        
        if (!$lead->user) {
            return back()->withErrors('The lead does not have an assigned user.');
        }

        Lead_approvals::create([
            'lead_id' => $id,
            'user_id' => $lead->user->id,
            'company_name' => $lead->user->company_name,
            'company_representative' => $lead->user->name . ' ' . $lead->user->last_name,
            'company_phone' => $lead->user->phone,
            'lead_name' => $request->lead_name,
            'lead_address' => $request->lead_address,
            'lead_phone' => $request->lead_phone,
            'installation_date' => $request->installation_date,
            'extra_info' => $request->extra_info,
        ]);

        $lead->update([
            'approved_data_submitted' => true,
            'estado' => 4 // Completed
        ]);

        return back()->with('success', 'Lead approval data submitted successfully and status updated to Completed.');
    }

    /**
     * Display calendar with events
     */
    public function calendar()
    {
        $user = Auth::guard('team')->user();

        return view('manageTeam.calendar', [
            'events' => $this->getAllCalendarEvents($user),
            'user' => $user
        ]);
    }

    // ==================== PRIVATE METHODS ====================

    /**
     * Get leads for the manager
     */
    private function getLeads($user, $sellerId = null)
    {
        $query = Lead::with('team')->where('user_id', $user->user_id);

        if ($sellerId && $sellerId !== 'all') {
            $query->where('team_id', $sellerId);
        }

        return $query->paginate(10)->appends(['seller_id' => $sellerId]);
    }

    /**
     * Get sellers for the manager
     */
    private function getSellers($user)
    {
        return Team::where('user_id', $user->user_id)
                   ->where('role', 'sales')
                   ->get();
    }

    /**
     * Get lead status counts
     */
    private function getStatusCounts($user)
    {
        return [
            'leads' => Lead::where('estado', 1)->where('user_id', $user->user_id)->count(),
            'prospect' => Lead::where('estado', 2)->where('user_id', $user->user_id)->count(),
            'approved' => Lead::where('estado', 3)->where('user_id', $user->user_id)->count(),
            'completed' => Lead::where('estado', 4)->where('user_id', $user->user_id)->count(),
            'invoiced' => Lead::where('estado', 5)->where('user_id', $user->user_id)->count(),
            'finish' => Lead::where('estado', 6)->where('user_id', $user->user_id)->count(),
            'cancelled' => Lead::where('estado', 7)->where('user_id', $user->user_id)->count(),
        ];
    }

    /**
     * Get lead status sums
     */
    private function getStatusSums($user)
    {
        $statusSumsRaw = Lead::select('estado', DB::raw('SUM(contract_value) as total'))
            ->where('user_id', $user->user_id)
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        return [
            'leads' => $statusSumsRaw[1] ?? 0,
            'prospect' => $statusSumsRaw[2] ?? 0,
            'approved' => $statusSumsRaw[3] ?? 0,
            'completed' => $statusSumsRaw[4] ?? 0,
            'invoiced' => $statusSumsRaw[5] ?? 0,
            'finish' => $statusSumsRaw[6] ?? 0,
            'cancelled' => $statusSumsRaw[7] ?? 0,
        ];
    }

    /**
     * Get status mapping with colors
     */
    private function getStatusMap()
    {
        return [
            1 => ['name' => 'Lead', 'color' => 'bg-warning'],
            2 => ['name' => 'Prospect', 'color' => 'bg-orange'],
            3 => ['name' => 'Approved', 'color' => 'bg-success'],
            4 => ['name' => 'Completed', 'color' => 'bg-primary'],
            5 => ['name' => 'Invoiced', 'color' => 'bg-danger'],
            6 => ['name' => 'Finish', 'color' => 'bg-secondary'],
            7 => ['name' => 'Cancelled', 'color' => 'bg-secondary']
        ];
    }

    /**
     * Get all calendar events
     */
    private function getAllCalendarEvents($user)
    {
        return array_merge(
            $this->getJobRequestEvents($user),
            $this->getEmergencyEvents($user),
            $this->getLeadApprovalEvents()
        );
    }

    /**
     * Get Job Request events for calendar
     */
    private function getJobRequestEvents($user)
    {
        return $user->jobRequests()
            ->with('teamMembers')
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => 'Job: ' . $job->job_number_name,
                    'start' => $job->install_date_requested,
                    'url' => route('jobs.show', $job->id),
                    'type' => 'Job Request',
                    'company' => $job->company_name,
                    'rep' => $job->company_rep,
                    'rep_phone' => $job->company_rep_phone,
                    'rep_email' => $job->company_rep_email,
                    'customer' => $job->customer_first_name . ' ' . $job->customer_last_name,
                    'customer_phone' => $job->customer_phone_number,
                    'address' => $this->formatAddress([
                        'street' => $job->job_address_street_address,
                        'street2' => $job->job_address_street_address_line_2,
                        'city' => $job->job_address_city,
                        'state' => $job->job_address_state,
                        'zip' => $job->job_address_zip_code
                    ]),
                    'materials' => [
                        'starter' => $job->starter_bundles_ordered ?? 0,
                        'hip' => $job->hip_and_ridge_ordered ?? 0,
                        'field' => $job->field_shingle_bundles_ordered ?? 0,
                        'modified' => $job->modified_bitumen_cap_rolls_ordered ?? 0,
                    ],
                    'special_instructions' => $job->special_instructions,
                    'team' => $this->formatTeamMembers($job->teamMembers),
                    'files' => $this->processJobFiles($job),
                    'color' => '#198754', // Green for jobs
                    'textColor' => '#ffffff'
                ];
            })
            ->toArray();
    }

    /**
     * Get Emergency events for calendar
     */
    private function getEmergencyEvents($user)
    {
        return $user->Emergencies()
            ->with('teamMembers')
            ->get()
            ->map(function ($emergency) {
                return [
                    'id' => $emergency->id,
                    'title' => 'Emergency: ' . $emergency->job_number_name,
                    'start' => $emergency->date_submitted,
                    'url' => route('emergency.show', $emergency->id),
                    'type' => 'Emergency',
                    'company' => $emergency->company_name,
                    'email' => $emergency->company_contact_email,
                    'address' => $this->formatAddress([
                        'street' => $emergency->job_address,
                        'street2' => $emergency->job_address_line2,
                        'city' => $emergency->job_city,
                        'state' => $emergency->job_state,
                        'zip' => $emergency->job_zip_code
                    ]),
                    'supplement' => $emergency->type_of_supplement,
                    'terms' => $emergency->terms_conditions ? 'Accepted' : 'Not Accepted',
                    'requirements' => $emergency->requirements ? 'Accepted' : 'Not Accepted',
                    'team' => $this->formatTeamMembers($emergency->teamMembers),
                    'files' => $this->processEmergencyFiles($emergency),
                    'color' => '#dc3545', // Red for emergencies
                    'textColor' => '#ffffff'
                ];
            })
            ->toArray();
    }

    /**
     * Get Lead Approval events for calendar
     */
    private function getLeadApprovalEvents()
    {
        return Lead_approvals::all()
            ->map(function ($approval) {
                return [
                    'title' => 'Approved Lead - ' . $approval->lead_name,
                    'start' => Carbon::parse($approval->installation_date)->toDateString(),
                    'url' => route('manager.manage', $approval->lead_id),
                    'type' => 'Approved',
                    'color' => '#670ebb', // Purple for lead approvals
                    'textColor' => '#ffffff'
                ];
            })
            ->toArray();
    }

    /**
     * Process job request files
     */
    private function processJobFiles($job)
    {
        $fileGroups = [
            ['label' => 'Aerial Measurements', 'data' => $job->aerial_measurement ?? []],
            ['label' => 'Material Orders', 'data' => $job->material_order ?? []],
            ['label' => 'Pictures', 'data' => $job->file_upload ?? []]
        ];

        return $this->processFileGroups($fileGroups);
    }

    /**
     * Process emergency files
     */
    private function processEmergencyFiles($emergency)
    {
        $fileGroups = [
            ['label' => 'Aerial Measurements', 'data' => $emergency->aerial_measurement_path ?? []],
            ['label' => 'Contracts', 'data' => $emergency->contract_upload_path ?? []],
            ['label' => 'Pictures', 'data' => $emergency->file_picture_upload_path ?? []]
        ];

        return $this->processFileGroups($fileGroups);
    }

    /**
     * Process file groups from different sources
     */
    private function processFileGroups($fileGroups)
    {
        return collect($fileGroups)
            ->flatMap(function ($group) {
                $items = is_array($group['data']) ? $group['data'] : json_decode($group['data'], true) ?? [];
                
                return collect($items)->map(function ($file) use ($group) {
                    $path = is_array($file) ? $file['path'] : trim($file, '[]"');
                    $name = is_array($file) ? ($file['original_name'] ?? basename($path)) : basename($path);
                    
                    return [
                        'path' => $path,
                        'name' => $name,
                        'label' => $group['label']
                    ];
                });
            })
            ->filter()
            ->values()
            ->toArray();
    }

    /**
     * Format address from components
     */
    private function formatAddress($addressComponents)
    {
        $street = trim($addressComponents['street'] ?? '');
        $street2 = trim($addressComponents['street2'] ?? '');
        $city = trim($addressComponents['city'] ?? '');
        $state = trim($addressComponents['state'] ?? '');
        $zip = trim($addressComponents['zip'] ?? '');

        $addressParts = array_filter([$street, $street2, $city, $state, $zip]);
        
        return !empty($addressParts) ? implode(', ', $addressParts) : 'Address not available';
    }

    /**
     * Format team members with their roles
     */
    private function formatTeamMembers($teamMembers)
    {
        return $teamMembers->map(function ($member) {
            $role = ucfirst(str_replace('_', ' ', $member->role));
            return $member->name . ' (' . $role . ')';
        })->toArray();
    }
}