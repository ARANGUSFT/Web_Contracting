<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobRequest;
use App\Models\Emergencies;
use App\Models\Crew;
use App\Models\User;
use App\Models\EventCompany;
use App\Models\Subcontractors;
use App\Models\EventNote;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Services\PushNotificationService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Models\RepairTicket;


class EventCalendarController extends Controller
{
    private function contractorEditUrlFrom($item): ?string
    {
        $contractorUserId =
            $item->contractor_user_id
            ?? optional($item->contractor)->id
            ?? optional($item->crew)->contractor_user_id
            ?? null;

        return $contractorUserId
            ? route('superadmin.contractors.edit', ['user' => $contractorUserId])
            : null;
    }

    // ── Título de repair: EM-BAN-0001 · RT-0001 ───────────────
    // Secuencia por trabajo padre — recibe el objeto $rt completo
    private function repairTitle(?string $ref, \App\Models\RepairTicket $rt): string
    {
        $seq = str_pad($rt->sequence_number ?? $rt->id, 4, '0', STR_PAD_LEFT);
        return ($ref ?? 'Repair') . '  / RT-' . $seq;
    }

    public function index()
    {
        $crews = Crew::all();

        $jobCompanies = JobRequest::whereNotNull('company_name')
            ->where('company_name', '!=', '')
            ->distinct()->pluck('company_name')->toArray();

        $emergCompanies = Emergencies::whereNotNull('company_name')
            ->where('company_name', '!=', '')
            ->distinct()->pluck('company_name')->toArray();

        $allNames = array_values(array_unique(array_merge($jobCompanies, $emergCompanies)));
        sort($allNames, SORT_NATURAL | SORT_FLAG_CASE);

        $saved = EventCompany::get(['name','color','is_active'])->keyBy('name');

        $companiesForView = array_map(function ($name) use ($saved) {
            $row = $saved->get($name);
            return [
                'name'   => $name,
                'color'  => $row->color     ?? '#3788d8',
                'active' => $row->is_active ?? true,
                'slug'   => \Illuminate\Support\Str::slug($name),
            ];
        }, $allNames);

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

        $norm = fn($s) => mb_strtolower(trim((string)$s));

        $companyRows  = EventCompany::get(['name','color','is_active']);
        $colorByName  = [];
        $activeByName = [];
        foreach ($companyRows as $row) {
            $k = $norm($row->name);
            $colorByName[$k]  = $row->color ?: '#3788d8';
            $activeByName[$k] = (bool) $row->is_active;
        }

        $unknownAreActive = true;
        $fallbackPalette  = ['#1f77b4','#ff7f0e','#2ca02c','#d62728','#9467bd','#8c564b','#e377c2','#7f7f7f','#bcbd22','#17becf'];
        $assignedFallback = [];

        $getColor = function ($company) use ($norm, &$colorByName, &$assignedFallback, $fallbackPalette) {
            $k = $norm($company);
            if (isset($colorByName[$k])) return $colorByName[$k];
            if (!isset($assignedFallback[$k])) {
                $assignedFallback[$k] = $fallbackPalette[count($assignedFallback) % count($fallbackPalette)];
            }
            return $assignedFallback[$k];
        };

        $isAllowed = function ($company) use ($norm, $activeByName, $unknownAreActive) {
            $k = $norm($company);
            if (array_key_exists($k, $activeByName)) return $activeByName[$k] === true;
            return $unknownAreActive;
        };

        $events = [];

        // ── Jobs ──────────────────────────────────────────────
        JobRequest::with('crew')
            ->whereBetween('install_date_requested', [$start, $end])
            ->get()
            ->each(function ($job) use (&$events, $getColor, $isAllowed) {
                $company = $job->company_name ?? '';
                if (!$isAllowed($company)) return;
                $events[] = [
                    'id'    => $job->id,
                    'title' => "{$job->job_number_name}",
                    'start' => optional($job->install_date_requested)->toDateString(),
                    'color' => $getColor($company),
                    'extendedProps' => [
                        'type'        => 'job',
                        'companyName' => $company,
                        'crewName'    => optional($job->crew)->name,
                        'status'      => $job->status ?? 'pending',
                    ],
                ];
            });

        // ── Emergencies ───────────────────────────────────────
        Emergencies::with('crew')
            ->whereBetween('date_submitted', [$start, $end])
            ->get()
            ->each(function ($e) use (&$events, $getColor, $isAllowed) {
                $company = $e->company_name ?? '';
                if (!$isAllowed($company)) return;
                $events[] = [
                    'id'    => $e->id,
                    'title' => "{$e->job_number_name}",
                    'start' => optional($e->date_submitted)->toDateString(),
                    'color' => $getColor($company),
                    'extendedProps' => [
                        'type'        => 'emergency',
                        'companyName' => $company,
                        'crewName'    => optional($e->crew)->name,
                        'status'      => $e->status ?? 'pending',
                    ],
                ];
            });

        // ── Repair Tickets ────────────────────────────────────
        \App\Models\RepairTicket::with(['jobRequest', 'emergency', 'crew'])
            ->whereBetween('repair_date', [$start, $end])
            ->get()
            ->each(function ($rt) use (&$events) {
                $ref = $rt->reference_type === 'job'
                    ? optional($rt->jobRequest)->job_number_name
                    : optional($rt->emergency)->job_number_name;

                $company = $rt->reference_type === 'job'
                    ? optional($rt->jobRequest)->company_name
                    : optional($rt->emergency)->company_name;

                $events[] = [
                    'id'    => 'rt-' . $rt->id,
                    'title' => $this->repairTitle($ref, $rt),   // ← objeto completo
                    'start' => \Carbon\Carbon::parse($rt->repair_date)->toDateString(),
                    'color' => '#000000d0',
                    'extendedProps' => [
                        'type'           => 'repair',
                        'repair_id'      => $rt->id,
                        'reference_type' => $rt->reference_type,
                        'reference_id'   => $rt->reference_id,
                        'status'         => $rt->status,
                        'description'    => $rt->description,
                        'companyName'    => $company ?? '',
                        'crewName'       => optional($rt->crew)->name ?? '',
                        'crew_id'        => $rt->crew_id,
                        'crew_name'      => optional($rt->crew)->name ?? null,
                        'ref_number'     => $ref ?? '—',
                        'repair_date'    => \Carbon\Carbon::parse($rt->repair_date)->toDateString(),
                        'photos'         => collect($rt->photos ?? [])->map(fn($p) =>
                            asset('storage/' . (is_array($p) ? $p['path'] : $p))
                        )->values()->all(),
                    ],
                ];
            });

        return response()->json($events);
    }

    public function show(string $type, int $id)
    {
        // ── REPAIR ──────────────────────────────────────────────
        if ($type === 'repair') {
            $item = \App\Models\RepairTicket::findOrFail($id);

            $latestInvoice = $item->invoices()
                ->with('payoutItems')
                ->latest()
                ->first();

            $payoutTotal = $latestInvoice?->payoutItems?->sum('total') ?? 0;
            $payoutDate  = $latestInvoice?->payoutItems?->first()?->created_at?->toDateString();

            return response()->json([
                'type' => 'repair',
                'data' => [
                    'id'                  => $item->id,
                    'amount'              => $item->amount,
                    'payment_status'      => $item->payment_status ?? 'unpaid',
                    'payment_date'        => $item->payment_date,
                    'payment_receipt_url' => $item->payment_receipt_path
                        ? Storage::disk('public')->url($item->payment_receipt_path)
                        : null,
                    'payout_total'        => $payoutTotal,
                    'payout_date'         => $payoutDate,
                ],
            ]);
        }

        // ── JOB ─────────────────────────────────────────────────
        if ($type === 'job') {
            $item = JobRequest::with(['crew', 'notes.user', 'notes.subcontractor'])->findOrFail($id);

            $latestInvoice = $item->invoices()
                ->with('payoutItems')
                ->latest()
                ->first();

            $payoutTotal = $latestInvoice?->payoutItems?->sum('total') ?? 0;
            $payoutDate  = $latestInvoice?->payoutItems?->first()?->created_at?->toDateString();

            $data = [
                'install_date_requested'             => optional($item->install_date_requested)->toDateString(),
                'company_name'                       => $item->company_name,
                'company_rep'                        => $item->company_rep,
                'company_rep_phone'                  => $item->company_rep_phone,
                'company_rep_email'                  => $item->company_rep_email,
                'customer_first_name'                => $item->customer_first_name,
                'customer_last_name'                 => $item->customer_last_name,
                'customer_phone_number'              => $item->customer_phone_number,
                'job_number_name'                    => $item->job_number_name,
                'job_address_street_address'         => $item->job_address_street_address,
                'job_address_street_address_line_2'  => $item->job_address_street_address_line_2,
                'job_address_city'                   => $item->job_address_city,
                'job_address_state'                  => $item->job_address_state,
                'job_address_zip_code'               => $item->job_address_zip_code,
                'material_roof_loaded'               => $item->material_roof_loaded,
                'starter_bundles_ordered'            => $item->starter_bundles_ordered,
                'hip_and_ridge_ordered'              => $item->hip_and_ridge_ordered,
                'field_shingle_bundles_ordered'      => $item->field_shingle_bundles_ordered,
                'modified_bitumen_cap_rolls_ordered' => $item->modified_bitumen_cap_rolls_ordered,
                'delivery_date'                      => optional($item->delivery_date)->toDateString(),
                'mid_roof_inspection'                => $item->mid_roof_inspection,
                'siding_being_replaced'              => $item->siding_being_replaced,
                'asphalt_shingle_layers_to_remove'   => $item->asphalt_shingle_layers_to_remove,
                're_deck'                            => $item->re_deck,
                'skylights_replace'                  => $item->skylights_replace,
                'gutter_remove'                      => $item->gutter_remove,
                'gutter_detached_and_reset'          => $item->gutter_detached_and_reset,
                'satellite_remove'                   => $item->satellite_remove,
                'satellite_goes_in_the_trash'        => $item->satellite_goes_in_the_trash,
                'open_soffit_ceiling'                => $item->open_soffit_ceiling,
                'detached_garage_roof'               => $item->detached_garage_roof,
                'detached_shed_roof'                 => $item->detached_shed_roof,
                'special_instructions'               => $item->special_instructions,
                'material_verification'              => $item->material_verification,
                'stop_work_request'                  => $item->stop_work_request,
                'documentationattachment'            => $item->documentationattachment,
                'aerial_measurement'                 => $this->toUrlArray($item->aerial_measurement),
                'material_order'                     => $this->toUrlArray($item->material_order),
                'file_upload'                        => $this->toUrlArray($item->file_upload),
                'crew_id'                            => $item->crew_id,
                'crew_name'                          => optional($item->crew)->name,
                'status'                             => $item->status,
                'amount'                             => $item->amount,
                'payment_status'                     => $item->payment_status ?? 'unpaid',
                'payment_date'                       => $item->payment_date,
                'payment_receipt_url'                => $item->payment_receipt_path
                    ? Storage::disk('public')->url($item->payment_receipt_path)
                    : null,
                'payout_total'                       => $payoutTotal,
                'payout_date'                        => $payoutDate,
                'notes' => $item->notes->map(function ($n) {
                    $dt = $n->created_at ? Carbon::parse($n->created_at) : null;
                    return [
                        'id'               => $n->id,
                        'content'          => $n->content,
                        'image_url'        => $n->image_path
                            ? URL::to(Storage::disk('public')->url($n->image_path))
                            : null,
                        'user_id'          => $n->user_id ?? $n->subcontractor_id,
                        'user_name'        => optional($n->user)->name
                                            ?? optional($n->subcontractor)->name
                                            ?? 'Unknown',
                        'created_at_human' => $dt ? $dt->timezone('America/Bogota')->format('d/m/Y H:i') : '',
                        'created_at_iso'   => $dt ? $dt->toISOString() : null,
                    ];
                })->all(),
                'contractor_edit_url' => $this->contractorEditUrlFrom($item),
            ];

        // ── EMERGENCY ────────────────────────────────────────────
        } else {
            $item = Emergencies::with(['crew', 'notes.user', 'notes.subcontractor'])->findOrFail($id);

            $latestInvoice = $item->invoices()
                ->with('payoutItems')
                ->latest()
                ->first();

            $payoutTotal = $latestInvoice?->payoutItems?->sum('total') ?? 0;
            $payoutDate  = $latestInvoice?->payoutItems?->first()?->created_at?->toDateString();

            $data = [
                'date_submitted'           => optional($item->date_submitted)->toDateString(),
                'type_of_supplement'       => $item->type_of_supplement,
                'company_name'             => $item->company_name,
                'company_contact_email'    => $item->company_contact_email,
                'job_number_name'          => $item->job_number_name,
                'job_address'              => $item->job_address,
                'job_address_line2'        => $item->job_address_line2,
                'job_city'                 => $item->job_city,
                'job_state'                => $item->job_state,
                'job_zip_code'             => $item->job_zip_code,
                'terms_conditions'         => $item->terms_conditions,
                'requirements'             => $item->requirements,
                'aerial_measurement_path'  => $this->toUrlArray($item->aerial_measurement_path),
                'contract_upload_path'     => $this->toUrlArray($item->contract_upload_path),
                'file_picture_upload_path' => $this->toUrlArray($item->file_picture_upload_path),
                'crew_id'                  => $item->crew_id,
                'crew_name'                => optional($item->crew)->name,
                'status'                   => $item->status,
                'amount'                   => $item->amount,
                'payment_status'           => $item->payment_status ?? 'unpaid',
                'payment_date'             => $item->payment_date,
                'payment_receipt_url'      => $item->payment_receipt_path
                    ? Storage::disk('public')->url($item->payment_receipt_path)
                    : null,
                'payout_total'             => $payoutTotal,
                'payout_date'              => $payoutDate,
                'notes' => $item->notes->map(function ($n) {
                    $dt = $n->created_at ? Carbon::parse($n->created_at) : null;
                    return [
                        'id'               => $n->id,
                        'content'          => $n->content,
                        'image_url'        => $n->image_path
                            ? URL::to(Storage::disk('public')->url($n->image_path))
                            : null,
                        'user_id'          => $n->user_id ?? $n->subcontractor_id,
                        'user_name'        => optional($n->user)->name
                                            ?? optional($n->subcontractor)->name
                                            ?? 'Unknown',
                        'created_at_human' => $dt ? $dt->timezone('America/Bogota')->format('d/m/Y H:i') : '',
                        'created_at_iso'   => $dt ? $dt->toISOString() : null,
                    ];
                })->all(),
            ];
        }

        return response()->json(['type' => $type, 'data' => $data]);
    }

    protected function toUrlPairs($value): array
    {
        if (empty($value)) return [];

        if (is_string($value)) {
            $trim = trim($value);
            if ((str_starts_with($trim, '[') && str_ends_with($trim, ']')) ||
                (str_starts_with($trim, '{') && str_ends_with($trim, '}'))) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) $value = $decoded;
            }
        }

        $items = \Illuminate\Support\Arr::wrap($value);

        return array_values(array_filter(array_map(function ($p) {
            if (!$p) return null;
            if (is_string($p) && preg_match('#^https?://#i', $p)) return ['url' => $p];
            if (is_array($p)) {
                $candidate = $p['url'] ?? $p['path'] ?? $p['location'] ?? null;
                if (!$candidate) return null;
                if (preg_match('#^https?://#i', $candidate)) return ['url' => $candidate];
                return ['url' => URL::to(Storage::url($candidate)), 'path' => $candidate];
            }
            return ['url' => URL::to(Storage::url($p)), 'path' => $p];
        }, $items)));
    }

    protected function toLabelledWithPath(array $pairs, string $label): array
    {
        return array_map(function ($p) use ($label) {
            return ['label' => $label, 'url' => $p['url'], 'path' => $p['path'] ?? null];
        }, $pairs);
    }

    public function downloadFile(Request $request)
    {
        $disk = 'public';
        $path = $this->normalizePath($request->query('path'));
        abort_unless($path, 400, 'Missing path');

        if (!Storage::disk($disk)->exists($path)) {
            $alt = $this->stripCommonPrefixes($path);
            if ($alt !== $path && Storage::disk($disk)->exists($alt)) {
                $path = $alt;
            } else {
                return response()->json(['message' => 'File not found', 'path' => $path], 404);
            }
        }

        return Storage::disk($disk)->download($path, basename($path), [
            'Access-Control-Allow-Origin' => 'http://localhost:8100',
        ]);
    }

    public function inlineFile(Request $request)
    {
        $disk = 'public';
        $path = $this->normalizePath($request->query('path'));
        abort_unless($path, 400, 'Missing path');

        if (!Storage::disk($disk)->exists($path)) {
            $alt = $this->stripCommonPrefixes($path);
            if ($alt !== $path && Storage::disk($disk)->exists($alt)) {
                $path = $alt;
            } else {
                return response()->json(['message' => 'File not found', 'path' => $path], 404);
            }
        }

        $name = basename($path);
        return response()->file(Storage::disk($disk)->path($path), [
            'Content-Disposition'         => 'inline; filename="'.$name.'"',
            'Access-Control-Allow-Origin' => 'http://localhost:8100',
            'Content-Security-Policy'     => "frame-ancestors 'self' http://localhost:8100 http://127.0.0.1:8100",
        ]);
    }

    protected function normalizePath(?string $p): ?string
    {
        if (!$p) return null;
        return trim(urldecode($p));
    }

    protected function stripCommonPrefixes(string $p): string
    {
        return preg_replace('#^(storage/|public/)+#i', '', $p);
    }

    public function files(string $type, int $id)
    {
        if ($type === 'job') {
            $item  = JobRequest::findOrFail($id);
            $files = array_merge(
                $this->toLabelledWithPath($this->toUrlPairs($item->aerial_measurement), 'Aerial'),
                $this->toLabelledWithPath($this->toUrlPairs($item->material_order),     'Material Order'),
                $this->toLabelledWithPath($this->toUrlPairs($item->file_upload),         'Files'),
            );
        } else {
            $item  = Emergencies::findOrFail($id);
            $files = array_merge(
                $this->toLabelledWithPath($this->toUrlPairs($item->aerial_measurement_path),  'Aerial'),
                $this->toLabelledWithPath($this->toUrlPairs($item->contract_upload_path),     'Contract'),
                $this->toLabelledWithPath($this->toUrlPairs($item->file_picture_upload_path), 'Pictures'),
            );
        }

        return response()->json(['type' => $type, 'id' => $id, 'files' => $files]);
    }

    protected function toLabelled(array $urls, string $label): array
    {
        return array_map(fn($u) => ['label' => $label, 'url' => $u], $urls);
    }

    protected function toUrlArray($value): array
    {
        if (empty($value)) return [];

        if (is_string($value)) {
            $trim = trim($value);
            if ((str_starts_with($trim, '[') && str_ends_with($trim, ']')) ||
                (str_starts_with($trim, '{') && str_ends_with($trim, '}'))) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) $value = $decoded;
            }
        }

        $paths = Arr::wrap($value);

        return array_values(array_filter(array_map(function ($p) {
            if (!$p) return null;
            if (is_string($p) && preg_match('#^https?://#i', $p)) return $p;
            if (is_array($p)) {
                $candidate = $p['url'] ?? $p['path'] ?? $p['location'] ?? null;
                if (!$candidate) return null;
                if (preg_match('#^https?://#i', $candidate)) return $candidate;
                return Storage::url($candidate);
            }
            return Storage::url($p);
        }, $paths)));
    }

    public function assignCrew(Request $request)
    {
        try {
            $data = $request->validate([
                'type'    => 'required|in:job,emergency,repair',
                'id'      => 'required|integer',
                'crew_id' => 'required|exists:crews,id',
            ]);

            $model = match($data['type']) {
                'job'       => JobRequest::findOrFail($data['id']),
                'emergency' => Emergencies::findOrFail($data['id']),
                'repair'    => \App\Models\RepairTicket::findOrFail($data['id']),
            };

            $previousCrewId = $model->crew_id;
            $model->crew_id = $data['crew_id'];
            $model->save();

            if ($previousCrewId != $data['crew_id']) {
                try {
                    $crew = \App\Models\Crew::find($data['crew_id']);
                    if ($crew) {
                        $subcontractors = $crew->subcontractors ?? collect();
                        $jobNumber = $data['type'] === 'repair'
                            ? ('Repair #' . $model->id)
                            : ($model->job_number_name ?? 'N/A');
                        $company = $data['type'] === 'repair'
                            ? (optional($model->jobRequest)->company_name ?? optional($model->emergency)->company_name ?? '')
                            : ($model->company_name ?? '');
                        $push = app(PushNotificationService::class);
                        foreach ($subcontractors as $sub) {
                            $push->sendToSubcontractor(
                                subcontractorId: $sub->id,
                                title:           $data['type'] === 'repair' ? '🔧 Repair Ticket Assigned' : '🔨 New Job Assigned',
                                body:            $data['type'] === 'repair'
                                    ? "Repair ticket for {$jobNumber} has been assigned to your crew."
                                    : "Job #{$jobNumber} from {$company} has been assigned to your crew.",
                                data: ['type' => $data['type'], 'id' => (string) $model->id]
                            );
                        }
                    }
                } catch (\Throwable $e) {
                    \Log::warning('assignCrew push failed: ' . $e->getMessage());
                }
            }

            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            \Log::error('assignCrew error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function fetchNotes(Request $request)
    {
        $data = $request->validate([
            'type'     => 'required|in:job,emergency,repair',
            'id'       => 'required|integer',
            'since_id' => 'nullable|integer',
        ]);

        $model = match($data['type']) {
            'job'       => JobRequest::find($data['id']),
            'emergency' => Emergencies::find($data['id']),
            'repair'    => \App\Models\RepairTicket::find($data['id']),
        };

        if (!$model) return response()->json(['error' => 'Modelo no encontrado'], 404);

        $query = $model->notes()
            ->with(['user:id,name', 'subcontractor:id,name'])
            ->orderBy('id', 'asc');

        if (!empty($data['since_id'])) $query->where('id', '>', $data['since_id']);

        return response()->json($query->get()->map(function ($note) {
            return [
                'id'               => $note->id,
                'content'          => $note->content,
                'image_url'        => $note->image_path
                    ? URL::to(Storage::disk('public')->url($note->image_path))
                    : null,
                'user_id'          => $note->user_id ?? $note->subcontractor_id,
                'user_name'        => optional($note->user)->name
                                    ?? optional($note->subcontractor)->name
                                    ?? 'N/A',
                'created_at_human' => $note->created_at
                    ? $note->created_at->timezone('America/Bogota')->format('d/m/Y H:i')
                    : '',
                'created_at_iso'   => $note->created_at?->toISOString(),
            ];
        }));
    }

    public function storeNote(Request $request)
    {
        $request->validate([
            'type'    => 'required|in:job,emergency,repair',
            'id'      => 'required|integer',
            'content' => 'nullable|string|max:1000',
            'image'   => 'nullable|image|max:5120',
        ]);

        $content = $request->input('content');
        if (empty(trim($content ?? '')) && !$request->hasFile('image')) {
            return response()->json(['error' => 'content or image required'], 422);
        }

        $auth = $request->user();
        if (!$auth) return response()->json(['error' => 'No autenticado'], 401);

        $model = match($request->type) {
            'job'       => JobRequest::find($request->id),
            'emergency' => Emergencies::find($request->id),
            'repair'    => \App\Models\RepairTicket::find($request->id),
        };

        if (!$model) return response()->json(['error' => 'Modelo no encontrado'], 404);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        $note = $model->notes()->create([
            'content'          => $request->input('content', ''),
            'image_path'       => $imagePath,
            'user_id'          => $auth instanceof User ? $auth->id : null,
            'subcontractor_id' => $auth instanceof Subcontractors ? $auth->id : null,
        ]);

        $note->load(['user:id,name', 'subcontractor:id,name']);
        $isUser = !is_null($note->user_id);

        return response()->json([
            'id'          => $note->id,
            'content'     => $note->content,
            'image_url'   => $note->image_path
                ? URL::to(Storage::disk('public')->url($note->image_path))
                : null,
            'user_id'     => $note->user_id ?? $note->subcontractor_id,
            'user_name'   => optional($note->user)->name
                            ?? optional($note->subcontractor)->name
                            ?? 'N/A',
            'sender_type' => $isUser ? 'user' : 'subcontractor',
            'created_at'  => $note->created_at->format('d/m/Y H:i'),
        ], 201);
    }

    public function updateVisibility(Request $request)
    {
        $data = $request->validate([
            'name'   => ['required','string','max:255'],
            'active' => ['required','boolean'],
        ]);

        $company = EventCompany::updateOrCreate(
            ['name' => trim($data['name'])],
            ['is_active' => $data['active']]
        );

        return response()->json([
            'success' => true,
            'name'    => $company->name,
            'active'  => (bool) $company->is_active,
        ]);
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
        if (!$sub) return response()->json(['message' => 'Subcontractor not found'], 404);

        $crew = $sub->crews()->first();
        if (!$crew) return response()->json(['message' => 'No crew assigned'], 404);

        $jobs        = JobRequest::where('crew_id', $crew->id)->get();
        $emergencies = Emergencies::where('crew_id', $crew->id)->get();
        $repairs     = \App\Models\RepairTicket::where('crew_id', $crew->id)
                        ->with(['jobRequest', 'emergency'])->get();

        $trabajos = collect()
            ->merge($jobs->map(function ($job) {
                return [
                    'id'                                => $job->id,
                    'type'                              => 'job',
                    'title'                             => $job->title ?? 'Job Request',
                    'date'                              => $job->created_at,
                    'install_date_requested'            => optional($job->install_date_requested)->toDateString(),
                    'company_name'                      => $job->company_name,
                    'company_rep'                       => $job->company_rep,
                    'company_rep_phone'                 => $job->company_rep_phone,
                    'company_rep_email'                 => $job->company_rep_email,
                    'customer_first_name'               => $job->customer_first_name,
                    'customer_last_name'                => $job->customer_last_name,
                    'customer_phone_number'             => $job->customer_phone_number,
                    'job_address_street_address'        => $job->job_address_street_address,
                    'job_address_street_address_line_2' => $job->job_address_street_address_line_2,
                    'job_address_city'                  => $job->job_address_city,
                    'job_address_state'                 => $job->job_address_state,
                    'job_address_zip_code'              => $job->job_address_zip_code,
                    'job_number_name'                   => $job->job_number_name,
                    'status'                            => $job->status,
                    'amount'                            => $job->amount,
                    'payment_status'                    => $job->payment_status,
                    'payment_date'                      => $job->payment_date,
                    'payment_receipt_url'               => $job->payment_receipt_path
                        ? Storage::disk('public')->url($job->payment_receipt_path) : null,
                ];
            }))
            ->merge($emergencies->map(function ($emergency) {
                return [
                    'id'                    => $emergency->id,
                    'type'                  => 'emergency',
                    'title'                 => $emergency->title ?? 'Emergency',
                    'date'                  => $emergency->created_at,
                    'date_submitted'        => optional($emergency->date_submitted)->toDateString(),
                    'type_of_supplement'    => $emergency->type_of_supplement,
                    'company_name'          => $emergency->company_name,
                    'company_contact_email' => $emergency->company_contact_email,
                    'job_number_name'       => $emergency->job_number_name,
                    'job_address'           => $emergency->job_address,
                    'job_address_line2'     => $emergency->job_address_line2,
                    'job_city'              => $emergency->job_city,
                    'job_state'             => $emergency->job_state,
                    'job_zip_code'          => $emergency->job_zip_code,
                    'status'                => $emergency->status,
                    'amount'                => $emergency->amount,
                    'payment_status'        => $emergency->payment_status,
                    'payment_date'          => $emergency->payment_date,
                    'payment_receipt_url'   => $emergency->payment_receipt_path
                        ? Storage::disk('public')->url($emergency->payment_receipt_path) : null,
                ];
            }))
            ->merge($repairs->map(function ($rt) {
                $ref     = $rt->reference_type === 'job'
                    ? optional($rt->jobRequest)->job_number_name
                    : optional($rt->emergency)->job_number_name;
                $company = $rt->reference_type === 'job'
                    ? optional($rt->jobRequest)->company_name
                    : optional($rt->emergency)->company_name;
                $address = $rt->reference_type === 'job'
                    ? optional($rt->jobRequest)->job_address_street_address
                    : optional($rt->emergency)->job_address;

                return [
                    'id'           => $rt->id,
                    'type'         => 'repair',
                    'title'        => $this->repairTitle($ref, $rt),  // ← objeto completo
                    'date'         => $rt->repair_date,
                    'ref_number'   => $ref     ?? '—',
                    'company_name' => $company ?? '—',
                    'job_address'  => $address ?? '—',
                    'description'  => $rt->description,
                    'status'       => $rt->status,
                    'photos_admin' => $rt->fotosAdmin()->get()->map(fn($f) =>
                        str_starts_with($f->url, 'http') ? $f->url : asset('storage/' . $f->url)
                    )->values()->all(),
                    'photos_crew'  => $rt->fotosCrew()->get()->map(fn($f) =>
                        str_starts_with($f->url, 'http') ? $f->url : asset('storage/' . $f->url)
                    )->values()->all(),
                ];
            }));

        return response()->json($trabajos->values());
    }

    public function showTrabajo(string $type, int $id)
    {
        if ($type === 'job') {
            $item = JobRequest::findOrFail($id);
            return response()->json([
                'id'                                 => $item->id,
                'type'                               => 'job',
                'title'                              => $item->job_number_name ?? 'Job Request',
                'install_date_requested'             => optional($item->install_date_requested)->toDateString(),
                'company_name'                       => $item->company_name,
                'company_rep'                        => $item->company_rep,
                'company_rep_phone'                  => $item->company_rep_phone,
                'company_rep_email'                  => $item->company_rep_email,
                'customer_first_name'                => $item->customer_first_name,
                'customer_last_name'                 => $item->customer_last_name,
                'customer_phone_number'              => $item->customer_phone_number,
                'job_number_name'                    => $item->job_number_name,
                'job_address_street_address'         => $item->job_address_street_address,
                'job_address_street_address_line_2'  => $item->job_address_street_address_line_2,
                'job_address_city'                   => $item->job_address_city,
                'job_address_state'                  => $item->job_address_state,
                'job_address_zip_code'               => $item->job_address_zip_code,
                'status'                             => $item->status ?? 'pending',
                'amount'                             => $item->amount,
                'payment_status'                     => $item->payment_status ?? 'unpaid',
                'payment_date'                       => $item->payment_date,
                'created_at'                         => optional($item->created_at)->toDateString(),
            ]);

        } elseif ($type === 'emergency') {
            $item = Emergencies::findOrFail($id);
            return response()->json([
                'id'                         => $item->id,
                'type'                       => 'emergency',
                'title'                      => $item->job_number_name ?? 'Emergency',
                'date_submitted'             => optional($item->date_submitted)->toDateString(),
                'company_name'               => $item->company_name,
                'company_rep'                => $item->company_rep             ?? null,
                'company_rep_phone'          => $item->company_rep_phone       ?? null,
                'company_rep_email'          => $item->company_contact_email   ?? null,
                'company_contact_email'      => $item->company_contact_email,
                'type_of_supplement'         => $item->type_of_supplement,
                'job_number_name'            => $item->job_number_name,
                'job_address'                => $item->job_address,
                'job_address_street_address' => $item->job_address,
                'job_address_line2'          => $item->job_address_line2,
                'job_city'                   => $item->job_city,
                'job_address_city'           => $item->job_city,
                'job_state'                  => $item->job_state,
                'job_address_state'          => $item->job_state,
                'job_zip_code'               => $item->job_zip_code,
                'terms_conditions'           => $item->terms_conditions,
                'requirements'               => $item->requirements,
                'status'                     => $item->status ?? 'pending',
                'amount'                     => $item->amount,
                'payment_status'             => $item->payment_status ?? 'unpaid',
                'payment_date'               => $item->payment_date,
                'created_at'                 => optional($item->created_at)->toDateString(),
            ]);

        } else {
            // ── REPAIR ──────────────────────────────────────────
            $item = \App\Models\RepairTicket::with(['jobRequest', 'emergency'])->findOrFail($id);

            $ref     = $item->reference_type === 'job'
                ? optional($item->jobRequest)->job_number_name
                : optional($item->emergency)->job_number_name;
            $company = $item->reference_type === 'job'
                ? optional($item->jobRequest)->company_name
                : optional($item->emergency)->company_name;
            $address = $item->reference_type === 'job'
                ? optional($item->jobRequest)->job_address_street_address
                : optional($item->emergency)->job_address;

            return response()->json([
                'id'             => $item->id,
                'type'           => 'repair',
                'title'          => $this->repairTitle($ref, $item),  // ← objeto completo
                'repair_date'    => optional($item->repair_date)->toDateString(),
                'description'    => $item->description,
                'status'         => $item->status ?? 'pending',
                'company_name'   => $company ?? '—',
                'ref_number'     => $ref ?? '—',
                'job_address'    => $address ?? null,
                'reference_type' => $item->reference_type,
                'reference_id'   => $item->reference_id,
                'photos_admin'   => $item->fotosAdmin()->get()->map(fn($f) =>
                    str_starts_with($f->url, 'http') ? $f->url : asset('storage/' . $f->url)
                )->values(),
                'photos_crew'    => $item->fotosCrew()->get()->map(fn($f) =>
                    str_starts_with($f->url, 'http') ? $f->url : asset('storage/' . $f->url)
                )->values(),
                'created_at'     => optional($item->created_at)->toDateString(),
            ]);
        }
    }

    public function pendingPayments()
    {
        $jobs = JobRequest::where('status', 'completed')
            ->where(function($q) {
                $q->where('payment_status', 'unpaid')->orWhereNull('payment_status');
            })
            ->with('crew')
            ->orderBy('install_date_requested', 'desc')
            ->get()
            ->map(fn($j) => [
                'id'         => $j->id,
                'type'       => 'job',
                'job_number' => $j->job_number_name ?? 'N/A',
                'company'    => $j->company_name ?? '—',
                'crew'       => optional($j->crew)->name ?? '—',
                'date'       => optional($j->install_date_requested)->toDateString(),
                'amount'     => $j->amount,
            ]);

        $emergencies = Emergencies::where('status', 'completed')
            ->where(function($q) {
                $q->where('payment_status', 'unpaid')->orWhereNull('payment_status');
            })
            ->with('crew')
            ->orderBy('date_submitted', 'desc')
            ->get()
            ->map(fn($e) => [
                'id'         => $e->id,
                'type'       => 'emergency',
                'job_number' => $e->job_number_name ?? 'N/A',
                'company'    => $e->company_name ?? '—',
                'crew'       => optional($e->crew)->name ?? '—',
                'date'       => optional($e->date_submitted)->toDateString(),
                'amount'     => $e->amount,
            ]);

        $all = collect($jobs)->merge($emergencies)->sortByDesc('date')->values();
        return response()->json(['count' => $all->count(), 'items' => $all]);
    }

    public function updatePayment(Request $request, int $id)
    {
        $request->validate([
            'amount'          => 'nullable|numeric|min:0',
            'payment_date'    => 'nullable|date',
            'payment_receipt' => 'nullable|file|mimes:pdf|max:10240',
            'payment_status'  => 'required|in:unpaid,paid',
        ]);

        $job  = JobRequest::findOrFail($id);

          // ── Eliminar receipt existente ──
        if ($request->boolean('remove_receipt') && $job->payment_receipt_path) {
            Storage::disk('public')->delete($job->payment_receipt_path);
            $job->payment_receipt_path = null;
            $job->save();
            return response()->json(['success' => true]);
        }

        $data = $request->only(['amount', 'payment_date', 'payment_status']);

        if ($request->hasFile('payment_receipt')) {
            if ($job->payment_receipt_path) Storage::disk('public')->delete($job->payment_receipt_path);
            $data['payment_receipt_path'] = $request->file('payment_receipt')->store('receipts/jobs', 'public');
        }

        $job->update($data);
        return response()->json(['success' => true]);
    }

    public function updateEmergencyPayment(Request $request, int $id)
    {
        $request->validate([
            'amount'          => 'nullable|numeric|min:0',
            'payment_date'    => 'nullable|date',
            'payment_receipt' => 'nullable|file|mimes:pdf|max:10240',
            'payment_status'  => 'required|in:unpaid,paid',
        ]);

        $emergency = Emergencies::findOrFail($id);

        // ── Eliminar receipt existente ──
        if ($request->boolean('remove_receipt') && $emergency->payment_receipt_path) {
            Storage::disk('public')->delete($emergency->payment_receipt_path);
            $emergency->payment_receipt_path = null;
            $emergency->save();
            return response()->json(['success' => true]);
        }

        $data      = $request->only(['amount', 'payment_date', 'payment_status']);

        if ($request->hasFile('payment_receipt')) {
            if ($emergency->payment_receipt_path) Storage::disk('public')->delete($emergency->payment_receipt_path);
            $data['payment_receipt_path'] = $request->file('payment_receipt')->store('receipts/emergencies', 'public');
        }

        $emergency->update($data);
        return response()->json(['success' => true]);  // ← cambié back() por JSON
    }

    public function viewReceipt(string $type, int $id)
    {
        $item = $type === 'job'
            ? JobRequest::findOrFail($id)
            : Emergencies::findOrFail($id);

        if (!$item->payment_receipt_path || !Storage::disk('public')->exists($item->payment_receipt_path)) {
            abort(404, 'Soporte no encontrado.');
        }

        return response()->file(Storage::disk('public')->path($item->payment_receipt_path));
    }

    public function updateStatus(Request $request, string $type, int $id)
    {
        $request->validate([
            'status' => 'required|in:pending,en_process,completed',
        ]);

        $model = match($type) {
            'job'       => JobRequest::findOrFail($id),
            'emergency' => Emergencies::findOrFail($id),
            default     => \App\Models\RepairTicket::findOrFail($id),
        };

        $model->status = $request->status;
        $model->save();

        return response()->json(['success' => true, 'status' => $model->status]);
    }

    public function pagos($subcontractor_id)
    {
        $sub = Subcontractors::find($subcontractor_id);
        if (!$sub) return response()->json(['message' => 'Subcontractor not found'], 404);

        $crew = $sub->crews()->first();
        if (!$crew) return response()->json(['total_pagado' => 0, 'total_pendiente' => 0, 'pagos' => []]);

        $jobs        = JobRequest::where('crew_id', $crew->id)->get();
        $emergencies = Emergencies::where('crew_id', $crew->id)->get();
        $repairs     = \App\Models\RepairTicket::where('crew_id', $crew->id)
                        ->with(['jobRequest', 'emergency'])->get();

        $todos = collect()
            ->merge($jobs->map(fn($j) => [
                'id'                  => $j->id,
                'type'                => 'job',
                'job_number_name'     => $j->job_number_name,
                'amount'              => $j->amount,
                'payment_status'      => $j->payment_status ?? 'unpaid',
                'job_status'          => $j->status ?? 'pending',
                'payment_date'        => $j->payment_date,
                'payment_receipt_url' => $j->payment_receipt_path
                    ? Storage::disk('public')->url($j->payment_receipt_path) : null,
            ]))
            ->merge($emergencies->map(fn($e) => [
                'id'                  => $e->id,
                'type'                => 'emergency',
                'job_number_name'     => $e->job_number_name,
                'amount'              => $e->amount,
                'payment_status'      => $e->payment_status ?? 'unpaid',
                'job_status'          => $e->status ?? 'pending',
                'payment_date'        => $e->payment_date,
                'payment_receipt_url' => $e->payment_receipt_path
                    ? Storage::disk('public')->url($e->payment_receipt_path) : null,
            ]))
            ->merge($repairs->map(function ($rt) {
                $ref = $rt->reference_type === 'job'
                    ? optional($rt->jobRequest)->job_number_name
                    : optional($rt->emergency)->job_number_name;

                return [
                    'id'                  => $rt->id,
                    'type'                => 'repair',
                    'job_number_name'     => $this->repairTitle($ref, $rt),
                    'amount'              => $rt->amount,
                    'payment_status'      => $rt->payment_status ?? 'unpaid',
                    'payment_date'        => $rt->payment_date,
                    'payment_receipt_url' => $rt->payment_receipt_path
                        ? Storage::disk('public')->url($rt->payment_receipt_path)
                        : null,
                    'repair_date'         => optional($rt->repair_date)->toDateString(),
                    'description'         => $rt->description,
                ];
            }));

        return response()->json([
            'total_pagado'    => $todos->where('payment_status', 'paid')->sum('amount'),
            'total_pendiente' => $todos->where('payment_status', 'unpaid')->sum('amount'),
            'pagos'           => $todos->values(),
        ]);
    }

    public function invoiceableOptions(Request $request)
    {
        $type = $request->query('type');

        if ($type === 'job') {
            return response()->json(
                JobRequest::orderByDesc('created_at')
                    ->get(['id', 'job_number_name'])
                    ->map(fn($j) => ['id' => $j->id, 'label' => $j->job_number_name])
            );
        }

        if ($type === 'emergency') {
            return response()->json(
                Emergencies::orderByDesc('created_at')
                    ->get(['id', 'job_number_name'])
                    ->map(fn($e) => ['id' => $e->id, 'label' => $e->job_number_name])
            );
        }

        if ($type === 'repair') {
            return response()->json(
                \App\Models\RepairTicket::with(['jobRequest', 'emergency'])
                    ->orderByDesc('created_at')
                    ->get()
                    ->map(fn($rt) => [
                        'id'    => $rt->id,
                        'label' => $this->repairTitle(
                            $rt->reference_type === 'job'
                                ? optional($rt->jobRequest)->job_number_name
                                : optional($rt->emergency)->job_number_name,
                            $rt
                        )
                    ])
            );
        }

        return response()->json([]);
    }
}