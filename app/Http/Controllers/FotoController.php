<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\JobRequest;
use App\Models\Emergencies;
use App\Models\RepairTicket;
use App\Models\PhotoShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class FotoController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // API: Listar fotos de un modelo
    // GET /api/fotos/{tipo}/{id}
    // tipo: job_request | emergency | repair
    // ─────────────────────────────────────────────────────────────
    public function index($tipo, $id)
    {
        $modelo = $this->findModel($tipo, $id);

        // Para repair devolvemos admin y crew separados
        if ($tipo === 'repair') {
            return response()->json([
                'admin' => $modelo->fotosAdmin()->get()->map(fn($f) => [
                    'id'      => $f->id,
                    'url'     => $this->absoluteUrl($f->url),
                    'source'  => 'admin',
                    'takenAt' => optional($f->created_at)->toIso8601String(),
                ]),
                'crew'  => $modelo->fotosCrew()->get()->map(fn($f) => [
                    'id'      => $f->id,
                    'url'     => $this->absoluteUrl($f->url),
                    'source'  => 'crew',
                    'takenAt' => optional($f->created_at)->toIso8601String(),
                ]),
            ]);
        }

        // job_request | emergency — lista plana
        $fotos = $modelo->fotos()->get()->map(fn($foto) => [
            'id'      => $foto->id,
            'url'     => $this->absoluteUrl($foto->url),
            'takenAt' => optional($foto->created_at)->toIso8601String(),
        ]);

        return response()->json($fotos);
    }

    // ─────────────────────────────────────────────────────────────
    // API: Subir foto
    // POST /api/fotos
    // source: admin (default) | crew  — solo aplica a repair
    // ─────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'tipo'   => 'required|in:job_request,emergency,repair',
            'id'     => 'required|integer',
            'foto'   => 'required|image|max:10240',
            'source' => 'nullable|in:admin,crew',
        ]);

        $tipo   = $request->tipo;
        $source = $request->input('source', $tipo === 'repair' ? 'crew' : null);

        // Carpeta según tipo y source
        $folder = match(true) {
            $tipo === 'repair' && $source === 'crew'  => 'repair-tickets/crew',
            $tipo === 'repair'                         => 'repair-tickets/admin',
            $tipo === 'emergency'                      => 'fotos/emergency',
            default                                    => 'fotos',
        };

        $path = $request->file('foto')->store($folder, 'public');

        $modelo = $this->findModel($tipo, $request->id);

        $data = ['url' => $path];
        if ($source) $data['source'] = $source;

        $foto = $modelo->fotos()->create($data);

        return response()->json([
            'id'      => $foto->id,
            'url'     => $this->absoluteUrl($foto->url),
            'source'  => $foto->source ?? null,
            'takenAt' => optional($foto->created_at)->toIso8601String(),
        ], 201);
    }

    // ─────────────────────────────────────────────────────────────
    // API: Subir múltiples fotos del crew para un repair
    // POST /api/repair-tickets/{id}/crew-photos
    // ─────────────────────────────────────────────────────────────
    public function storeCrewPhotos(Request $request, int $id)
    {
        $request->validate([
            'photos.*' => 'required|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
        ]);

        $rt = RepairTicket::findOrFail($id);

        $saved = [];
        foreach ($request->file('photos', []) as $file) {
            if (!$file) continue;

            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = $safeName . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path     = $file->storeAs('repair-tickets/crew', $filename, 'public');

            $foto    = $rt->fotos()->create(['url' => $path, 'source' => 'crew']);
            $saved[] = $this->absoluteUrl($foto->url);
        }

        return response()->json([
            'success'     => true,
            'crew_photos' => $saved,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // API: Subir múltiples fotos admin para un repair
    // POST /superadmin/repair-tickets/{id}/upload-photos
    // ─────────────────────────────────────────────────────────────
    public function storeAdminPhotos(Request $request, RepairTicket $repairTicket)
    {
        $request->validate([
            'photos.*' => 'required|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx|max:20480',
        ]);

        $saved = [];
        foreach ($request->file('photos', []) as $file) {
            if (!$file) continue;

            // Preservar nombre original + timestamp para evitar duplicados
            $original  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $baseName  = pathinfo($original, PATHINFO_FILENAME);
            $safeName  = preg_replace('/[^a-zA-Z0-9._-]/', '_', $baseName);
            $filename  = $safeName . '_' . time() . '.' . $extension;

            $path = $file->storeAs('repair-tickets/admin', $filename, 'public');
            $foto = $repairTicket->fotos()->create(['url' => $path, 'source' => 'admin']);
            $saved[] = $this->absoluteUrl($foto->url);
        }

        return response()->json([
            'success' => true,
            'photos'  => $saved,
        ]);
    }
    // ─────────────────────────────────────────────────────────────
    // WEB: Vista de selección de proyectos
    // ─────────────────────────────────────────────────────────────
    public function projects()
    {
        $jobs        = JobRequest::has('fotos')->get();
        $emergencies = Emergencies::has('fotos')->get();
        $repairs     = RepairTicket::has('fotos')->with(['jobRequest','emergency'])->get();

        return view('admin.photos.projects', compact('jobs', 'emergencies', 'repairs'));
    }

    // ─────────────────────────────────────────────────────────────
    // WEB: Vista de fotos por proyecto
    // ─────────────────────────────────────────────────────────────
    public function view(string $tipo, int $id)
    {
        $modelo      = $this->findModel($tipo, $id);
        $projectInfo = $this->getProjectInfo($modelo, $tipo);

        $existingShare = ($tipo !== 'repair')
            ? PhotoShare::for($tipo, $id)->first()
            : null;
        $shareUrl = $existingShare?->public_url;

        // Para repair pasamos las 2 colecciones por separado
        $fotosAdmin = $tipo === 'repair' ? $modelo->fotosAdmin()->get() : null;
        $fotosCrew  = $tipo === 'repair' ? $modelo->fotosCrew()->get()  : null;
        $fotos      = $tipo !== 'repair' ? $modelo->fotos               : null;

        return view('admin.photos.view', compact(
            'fotos', 'fotosAdmin', 'fotosCrew',
            'tipo', 'id', 'shareUrl', 'projectInfo'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // WEB: Crear link público compartido
    // ─────────────────────────────────────────────────────────────
    public function createShareWeb(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|in:job_request,emergency,repair',  // ← agrega repair
            'id'   => 'required|integer',
        ]);

        $share = PhotoShare::ensure($data['tipo'], (int) $data['id'], optional($request->user())->id);

        return back()
            ->with('status', 'Public link created')
            ->with('share_url', $share->public_url);
    }

    // ─────────────────────────────────────────────────────────────
    // WEB: Galería pública por token
    // ─────────────────────────────────────────────────────────────
    public function publicGallery(string $token)
    {
        $share  = PhotoShare::where('token', $token)->firstOrFail();
        $modelo = $this->findModel($share->type, $share->model_id);

        $projectInfo = $this->getProjectInfo($modelo, $share->type);

        return view('admin.photos.public', [
            'fotos'       => $modelo->fotos,
            'tipo'        => $share->type,
            'id'          => $share->model_id,
            'projectInfo' => $projectInfo,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // API / WEB: Eliminar una foto
    // DELETE /api/fotos/{tipo}/{id}
    // ─────────────────────────────────────────────────────────────
    public function destroy(Request $request, string $tipo, int $id)
    {
        $request->validate([
            'url' => 'required|string',
        ]);

        $url  = $request->input('url');
        $path = preg_replace('#^.*/storage/#', '', $url);

        $modelClass = match($tipo) {
            'repair'    => RepairTicket::class,
            'emergency' => Emergencies::class,
            default     => JobRequest::class,
        };

        $foto = Foto::where(function ($q) use ($path, $url) {
                $q->where('url', $path)->orWhere('url', $url);
            })
            ->where('imageable_type', $modelClass)
            ->where('imageable_id', $id)
            ->first();

        if (!$foto) {
            $foto = Foto::where('url', $path)->orWhere('url', $url)->first();
        }

        if (!$foto) {
            return response()->json(['message' => 'Photo not found'], 404);
        }

        if (Storage::disk('public')->exists($foto->url)) {
            Storage::disk('public')->delete($foto->url);
        }

        $foto->delete();

        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────
    private function findModel(string $tipo, int $id)
    {
        return match($tipo) {
            'repair'    => RepairTicket::with('fotos')->findOrFail($id),
            'emergency' => Emergencies::with('fotos')->findOrFail($id),
            default     => JobRequest::with('fotos')->findOrFail($id),
        };
    }

    private function absoluteUrl(string $path): string
    {
        // Si ya es URL absoluta la devolvemos tal cual
        if (str_starts_with($path, 'http')) return $path;
        return URL::to(Storage::url($path));
    }

    private function getProjectInfo($model, string $tipo)
    {
        return match($tipo) {
            'repair'    => $this->getRepairInfo($model),
            'emergency' => $this->getEmergencyInfo($model),
            default     => $this->getJobRequestInfo($model),
        };
    }

    private function getJobRequestInfo(JobRequest $job)
    {
        return (object) [
            'company_name'                       => $job->company_name,
            'job_number_name'                    => $job->job_number_name,
            'company_rep'                        => $job->company_rep,
            'company_rep_phone'                  => $job->company_rep_phone,
            'company_rep_email'                  => $job->company_rep_email,
            'customer_first_name'                => $job->customer_first_name,
            'customer_last_name'                 => $job->customer_last_name,
            'customer_phone'                     => $job->customer_phone_number,
            'job_address_street'                 => $job->job_address_street_address,
            'job_address_street_line2'           => $job->job_address_street_address_line_2,
            'job_address_city'                   => $job->job_address_city,
            'job_address_state'                  => $job->job_address_state,
            'job_address_zip'                    => $job->job_address_zip_code,
            'install_date_requested'             => $job->install_date_requested,
            'delivery_date'                      => $job->delivery_date,
            'date_submitted'                     => $job->created_at,
            'special_instructions'               => $job->special_instructions,
            'material_verification'              => $job->material_verification,
            'status'                             => $job->status,
            'material_roof_loaded'               => $job->material_roof_loaded,
            'starter_bundles_ordered'            => $job->starter_bundles_ordered,
            'hip_and_ridge_ordered'              => $job->hip_and_ridge_ordered,
            'field_shingle_bundles_ordered'      => $job->field_shingle_bundles_ordered,
            'modified_bitumen_cap_rolls_ordered' => $job->modified_bitumen_cap_rolls_ordered,
        ];
    }

    private function getEmergencyInfo(Emergencies $emergency)
    {
        return (object) [
            'company_name'          => $emergency->company_name,
            'job_number_name'       => $emergency->job_number_name,
            'type_of_supplement'    => $emergency->type_of_supplement,
            'company_contact_email' => $emergency->company_contact_email,
            'job_address'           => $emergency->job_address,
            'job_address_line2'     => $emergency->job_address_line2,
            'job_city'              => $emergency->job_city,
            'job_state'             => $emergency->job_state,
            'job_zip'               => $emergency->job_zip_code,
            'date_submitted'        => $emergency->date_submitted,
            'created_at'            => $emergency->created_at,
            'terms_conditions'      => $emergency->terms_conditions,
            'requirements'          => $emergency->requirements,
            'status'                => $emergency->status,
        ];
    }

    private function getRepairInfo(RepairTicket $repair)
    {
        $ref     = $repair->reference_type === 'job'
            ? optional($repair->jobRequest)->job_number_name
            : optional($repair->emergency)->job_number_name;
        $company = $repair->reference_type === 'job'
            ? optional($repair->jobRequest)->company_name
            : optional($repair->emergency)->company_name;
        $address = $repair->reference_type === 'job'
            ? optional($repair->jobRequest)->job_address_street_address
            : optional($repair->emergency)->job_address;

        return (object) [
            'ref_number'     => $ref      ?? '—',
            'company_name'   => $company  ?? '—',
            'job_address'    => $address  ?? null,
            'description'    => $repair->description,
            'status'         => $repair->status,
            'repair_date'    => $repair->repair_date,
            'reference_type' => $repair->reference_type,
        ];
    }
}