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

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class EventCalendarController extends Controller
{
  
public function index()
{
    $crews = Crew::all();

    // 1) Nombres de compañías (sin nulos/ni vacíos)
    $jobCompanies = JobRequest::whereNotNull('company_name')
        ->where('company_name', '!=', '')
        ->distinct()->pluck('company_name')->toArray();

    $emergCompanies = Emergencies::whereNotNull('company_name')
        ->where('company_name', '!=', '')
        ->distinct()->pluck('company_name')->toArray();

    $allNames = array_values(array_unique(array_merge($jobCompanies, $emergCompanies)));
    sort($allNames, SORT_NATURAL | SORT_FLAG_CASE);

    // 2) Mapa con color y is_active guardados en BD
    //    keyBy('name') => acceso rápido por nombre
    $saved = EventCompany::get(['name','color','is_active'])
        ->keyBy('name');

    // 3) Armar arreglo para la vista con color + active
    $companiesForView = array_map(function ($name) use ($saved) {
        $row = $saved->get($name); // Illuminate\Support\Collection::get
        return [
            'name'   => $name,
            'color'  => $row->color     ?? '#3788d8',
            'active' => $row->is_active ?? true,
            // Sugerencia útil para el HTML:
            'slug'   => \Illuminate\Support\Str::slug($name),
        ];
    }, $allNames);

    // (Opcional) Estados si los necesitas en la vista
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

    // Normalizador de nombre
    $norm = fn($s) => mb_strtolower(trim((string)$s));

    // 1) Mapas desde event_companies
    //    - colores por nombre normalizado
    //    - estado activo por nombre normalizado
    $companyRows = EventCompany::get(['name','color','is_active']);
    $colorByName = [];
    $activeByName = [];
    foreach ($companyRows as $row) {
        $k = $norm($row->name);
        $colorByName[$k]  = $row->color ?: '#3788d8';
        $activeByName[$k] = (bool) $row->is_active;
    }

    // 2) Política para compañías no registradas en event_companies:
    //    true = se muestran (comportamiento "legacy")
    //    false = se ocultan
    $unknownAreActive = true;

    // 3) Paleta fallback
    $fallbackPalette = ['#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd', '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf'];
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
        if (array_key_exists($k, $activeByName)) {
            return $activeByName[$k] === true;
        }
        return $unknownAreActive;
    };

    $events = [];

    // 4) Jobs (eager load crew para evitar N+1)
    JobRequest::with('crew')
        ->whereBetween('install_date_requested', [$start, $end])
        ->get()
        ->each(function ($job) use (&$events, $getColor, $isAllowed) {
            $company = $job->company_name ?? '';
            if (!$isAllowed($company)) return;

            $events[] = [
                'id'    => 'job-' . $job->id,
                'title' => "Job #{$job->job_number_name}",
                'start' => optional($job->install_date_requested)->toDateString(),
                'color' => $getColor($company),
                // Lo que tu Front espera leer:
                'extendedProps' => [
                    'type'        => 'job',
                    'companyName' => $company,
                    'crewName'    => optional($job->crew)->name,
                ],
            ];
        });

    // 5) Emergencies
    Emergencies::with('crew')
        ->whereBetween('date_submitted', [$start, $end])
        ->get()
        ->each(function ($e) use (&$events, $getColor, $isAllowed) {
            $company = $e->company_name ?? '';
            if (!$isAllowed($company)) return;

            $events[] = [
                'id'    => 'emergency-' . $e->id,
                'title' => "Emergency #{$e->job_number_name}",
                'start' => optional($e->date_submitted)->toDateString(),
                'color' => $getColor($company),
                'extendedProps' => [
                    'type'        => 'emergency',
                    'companyName' => $company,
                    'crewName'    => optional($e->crew)->name,
                ],
            ];
        });

    return response()->json($events);
}



    public function show(string $type, int $id)
    {
        if ($type === 'job') {
            $item = JobRequest::with(['crew', 'notes.user', 'notes.subcontractor'])->findOrFail($id);

            $data = [
                // General Info
                'install_date_requested' => optional($item->install_date_requested)->toDateString(),
                'company_name'           => $item->company_name,
                'company_rep'            => $item->company_rep,
                'company_rep_phone'      => $item->company_rep_phone,
                'company_rep_email'      => $item->company_rep_email,

                // Customer
                'customer_first_name'    => $item->customer_first_name,
                'customer_last_name'     => $item->customer_last_name,
                'customer_phone_number'  => $item->customer_phone_number,

                // Address
                'job_number_name'                   => $item->job_number_name,
                'job_address_street_address'        => $item->job_address_street_address,
                'job_address_street_address_line_2' => $item->job_address_street_address_line_2,
                'job_address_city'                  => $item->job_address_city,
                'job_address_state'                 => $item->job_address_state,
                'job_address_zip_code'              => $item->job_address_zip_code,

                // Materials & Delivery
                'material_roof_loaded'               => $item->material_roof_loaded,
                'starter_bundles_ordered'            => $item->starter_bundles_ordered,
                'hip_and_ridge_ordered'              => $item->hip_and_ridge_ordered,
                'field_shingle_bundles_ordered'      => $item->field_shingle_bundles_ordered,
                'modified_bitumen_cap_rolls_ordered' => $item->modified_bitumen_cap_rolls_ordered,
                'delivery_date'                      => optional($item->delivery_date)->toDateString(),

                // Inspections & Replacements
                'mid_roof_inspection'              => $item->mid_roof_inspection,
                'siding_being_replaced'            => $item->siding_being_replaced,
                'asphalt_shingle_layers_to_remove' => $item->asphalt_shingle_layers_to_remove,
                're_deck'                          => $item->re_deck,
                'skylights_replace'                => $item->skylights_replace,
                'gutter_remove'                    => $item->gutter_remove,
                'gutter_detached_and_reset'        => $item->gutter_detached_and_reset,
                'satellite_remove'                 => $item->satellite_remove,
                'satellite_goes_in_the_trash'      => $item->satellite_goes_in_the_trash,
                'open_soffit_ceiling'              => $item->open_soffit_ceiling,
                'detached_garage_roof'             => $item->detached_garage_roof,
                'detached_shed_roof'               => $item->detached_shed_roof,

                // Additional
                'special_instructions'   => $item->special_instructions,
                'material_verification'  => $item->material_verification,
                'stop_work_request'      => $item->stop_work_request,
                'documentationattachment'=> $item->documentationattachment,

                // Files -> URLs públicas normalizadas
                'aerial_measurement' => $this->toUrlArray($item->aerial_measurement),
                'material_order'     => $this->toUrlArray($item->material_order),
                'file_upload'        => $this->toUrlArray($item->file_upload),

                // Common
                'crew_id'   => $item->crew_id,
                'crew_name' => optional($item->crew)->name,

                // Notas con nombre y fechas unificadas
                'notes' => $item->notes->map(function ($n) {
                    $dt = $n->created_at ? Carbon::parse($n->created_at) : null;
                    return [
                        'id'               => $n->id,
                        'content'          => $n->content,
                        'user_name'        => optional($n->user)->name
                                            ?? optional($n->subcontractor)->name
                                            ?? 'Desconocido',
                        'created_at_human' => $dt ? $dt->timezone('America/Bogota')->format('d/m/Y H:i') : '',
                        'created_at_iso'   => $dt ? $dt->toISOString() : null,
                    ];
                })->all(),
            ];

        } else {
            $item = Emergencies::with(['crew', 'notes.user', 'notes.subcontractor'])->findOrFail($id);

            $data = [
                // General Info
                'date_submitted'        => optional($item->date_submitted)->toDateString(),
                'type_of_supplement'    => $item->type_of_supplement,
                'company_name'          => $item->company_name,
                'company_contact_email' => $item->company_contact_email,
                'job_number_name'       => $item->job_number_name,

                // Address
                'job_address'       => $item->job_address,
                'job_address_line2' => $item->job_address_line2,
                'job_city'          => $item->job_city,
                'job_state'         => $item->job_state,
                'job_zip_code'      => $item->job_zip_code,

                // Terms & Requirements
                'terms_conditions'  => $item->terms_conditions,
                'requirements'      => $item->requirements,

                // Files -> URLs públicas normalizadas
                'aerial_measurement_path'  => $this->toUrlArray($item->aerial_measurement_path),
                'contract_upload_path'     => $this->toUrlArray($item->contract_upload_path),
                'file_picture_upload_path' => $this->toUrlArray($item->file_picture_upload_path),

                // Common
                'crew_id'   => $item->crew_id,
                'crew_name' => optional($item->crew)->name,

                // Notas con nombre y fechas unificadas
                'notes' => $item->notes->map(function ($n) {
                    $dt = $n->created_at ? Carbon::parse($n->created_at) : null;
                    return [
                        'id'               => $n->id,
                        'content'          => $n->content,
                        'user_name'        => optional($n->user)->name
                                            ?? optional($n->subcontractor)->name
                                            ?? 'Desconocido',
                        'created_at_human' => $dt ? $dt->timezone('America/Bogota')->format('d/m/Y H:i') : '',
                        'created_at_iso'   => $dt ? $dt->toISOString() : null,
                    ];
                })->all(),
            ];
        }

        return response()->json([
            'type' => $type,
            'data' => $data,
        ]);
    }





    protected function toUrlPairs($value): array
    {
        if (empty($value)) return [];

        if (is_string($value)) {
            $trim = trim($value);
            if ((str_starts_with($trim, '[') && str_ends_with($trim, ']')) ||
                (str_starts_with($trim, '{') && str_ends_with($trim, '}'))) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }
        }

        $items = \Illuminate\Support\Arr::wrap($value);

        return array_values(array_filter(array_map(function ($p) {
            if (!$p) return null;

            // URL absoluta
            if (is_string($p) && preg_match('#^https?://#i', $p)) {
                return ['url' => $p];
            }

            // Array con 'url'/'path'/'location'
            if (is_array($p)) {
                $candidate = $p['url'] ?? $p['path'] ?? $p['location'] ?? null;
                if (!$candidate) return null;
                if (preg_match('#^https?://#i', $candidate)) return ['url' => $candidate];
                return [
                    'url'  => URL::to(Storage::url($candidate)),
                    'path' => $candidate
                ];
            }

            // Ruta relativa en disco 'public'
            return [
                'url'  => URL::to(Storage::url($p)),
                'path' => $p
            ];
        }, $items)));
    }

    protected function toLabelledWithPath(array $pairs, string $label): array
    {
        return array_map(function ($p) use ($label) {
            return [
                'label' => $label,
                'url'   => $p['url'],
                'path'  => $p['path'] ?? null,
            ];
        }, $pairs);
    }

     public function downloadFile(Request $request)
    {
        $disk = 'public';
        $path = $this->normalizePath($request->query('path'));

        abort_unless($path, 400, 'Missing path');

        // Intenta con el path dado; si no existe, quita prefijos comunes
        if (!Storage::disk($disk)->exists($path)) {
            $alt = $this->stripCommonPrefixes($path); // quita "storage/" o "public/"
            if ($alt !== $path && Storage::disk($disk)->exists($alt)) {
                $path = $alt;
            } else {
                return response()->json(['message' => 'File not found', 'path' => $path], 404);
            }
        }

        $name = basename($path);

        // 👍 Usa el helper nativo: no hay fpassthru, no hay 500 por stream falso
        return Storage::disk($disk)->download($path, $name, [
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

        // Para inline es mejor servir el archivo físico (solo si el disk es local)
        $absolute = Storage::disk($disk)->path($path);
        $name = basename($path);

        // Symfony/Response se encarga de Content-Type; añadimos inline
        return response()->file($absolute, [
            'Content-Disposition'         => 'inline; filename="'.$name.'"',
            'Access-Control-Allow-Origin' => 'http://localhost:8100',
            'Content-Security-Policy'     => "frame-ancestors 'self' http://localhost:8100 http://127.0.0.1:8100",
        ]);
    }

    protected function normalizePath(?string $p): ?string
    {
        if (!$p) return null;
        // Laravel ya decodifica los query params, pero por si acaso:
        $p = urldecode($p);
        // Quita espacios accidentales
        $p = trim($p);
        return $p;
    }
  
    protected function stripCommonPrefixes(string $p): string
    {
        // Si tu disco 'public' apunta a storage/app/public, el path interno NO lleva "public/" ni "storage/"
        return preg_replace('#^(storage/|public/)+#i', '', $p);
    }

    







    public function files(string $type, int $id)
    {
        if ($type === 'job') {
            $item = JobRequest::findOrFail($id);
            $files = array_merge(
                $this->toLabelledWithPath($this->toUrlPairs($item->aerial_measurement), 'Aerial'),
                $this->toLabelledWithPath($this->toUrlPairs($item->material_order),     'Material Order'),
                $this->toLabelledWithPath($this->toUrlPairs($item->file_upload),         'Files'),
            );
        } else {
            $item = Emergencies::findOrFail($id);
            $files = array_merge(
                $this->toLabelledWithPath($this->toUrlPairs($item->aerial_measurement_path),  'Aerial'),
                $this->toLabelledWithPath($this->toUrlPairs($item->contract_upload_path),     'Contract'),
                $this->toLabelledWithPath($this->toUrlPairs($item->file_picture_upload_path), 'Pictures'),
            );
        }

        return response()->json([
            'type'  => $type,
            'id'    => $id,
            'files' => $files, // [{label, url, path?}, ...]
        ]);
    }

    protected function toLabelled(array $urls, string $label): array
    {
        return array_map(fn ($u) => ['label' => $label, 'url' => $u], $urls);
    }

    protected function toUrlArray($value): array
    {
        if (empty($value)) return [];

        if (is_string($value)) {
            $trim = trim($value);
            if ((str_starts_with($trim, '[') && str_ends_with($trim, ']')) ||
                (str_starts_with($trim, '{') && str_ends_with($trim, '}'))) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }
        }

        $paths = Arr::wrap($value);

        return array_values(array_filter(array_map(function ($p) {
            if (!$p) return null;

            if (is_string($p) && preg_match('#^https?://#i', $p)) {
                return $p; // ya es absoluta
            }

            if (is_array($p)) {
                $candidate = $p['url'] ?? $p['path'] ?? $p['location'] ?? null;
                if (!$candidate) return null;
                if (preg_match('#^https?://#i', $candidate)) return $candidate;
                return Storage::url($candidate);
            }

            return Storage::url($p); // storage público
        }, $paths)));
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
            'type'     => 'required|in:job,emergency',
            'id'       => 'required|integer',
            'since_id' => 'nullable|integer', // Nuevo
        ]);

        $model = $data['type'] === 'job'
            ? JobRequest::find($data['id'])
            : Emergencies::find($data['id']);

        if (!$model) {
            return response()->json(['error' => 'Modelo no encontrado'], 404);
        }

        $query = $model->notes()->with(['user:id,name', 'subcontractor:id,name'])
            ->orderBy('id', 'asc');

        if (!empty($data['since_id'])) {
            $query->where('id', '>', $data['since_id']);
        }

        $notes = $query->get();

        return response()->json($notes->map(function ($note) {
            return [
                'id'               => $note->id,
                'content'          => $note->content,
                'user_name'        => optional($note->user)->name
                                    ?? optional($note->subcontractor)->name
                                    ?? 'N/A',
                'created_at_human' => $note->created_at
                    ? $note->created_at->timezone('America/Bogota')->format('d/m/Y H:i')
                    : '',
                'created_at_iso'   => $note->created_at
                    ? $note->created_at->toISOString()
                    : null,
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

        // Autenticado por Sanctum con Bearer token (desde el móvil o web)
        $auth = $request->user(); // puede ser User o Subcontractors
        if (!$auth) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $model = $data['type'] === 'job'
            ? JobRequest::find($data['id'])
            : Emergencies::find($data['id']);

        if (!$model) {
            return response()->json(['error' => 'Modelo no encontrado'], 404);
        }

        // Determinar emisor según el tipo autenticado
        $note = $model->notes()->create([
            'content'          => $data['content'],
            'user_id'          => $auth instanceof User ? $auth->id : null,
            'subcontractor_id' => $auth instanceof Subcontractors ? $auth->id : null,
        ]);

        $note->load(['user:id,name', 'subcontractor:id,name']);

        $isUser = !is_null($note->user_id);

        return response()->json([
            'id'          => $note->id,
            'content'     => $note->content,
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

        $name = trim($data['name']);

        $company = EventCompany::updateOrCreate(
            ['name' => $name],
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
