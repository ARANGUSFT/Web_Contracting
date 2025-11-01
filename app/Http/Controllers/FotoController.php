<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\JobRequest;
use App\Models\Emergencies;
use App\Models\PhotoShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class FotoController extends Controller
{
    public function index($tipo, $id)
    {
        $modelo = $tipo === 'job_request'
            ? JobRequest::findOrFail($id)
            : Emergencies::findOrFail($id);

        $fotos = $modelo->fotos()->get()->map(function ($foto) {
            return [
                'url' => URL::to(Storage::url($foto->url)),
                'takenAt' => optional($foto->created_at)->toIso8601String(),
            ];
        });

        return response()->json($fotos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:job_request,emergency',
            'id'   => 'required|integer',
            'foto' => 'required|image|max:2048',
        ]);

        $path = $request->file('foto')->store('fotos', 'public');

        $foto = new Foto(['url' => $path]);

        $modelo = $request->tipo === 'job_request'
            ? JobRequest::findOrFail($request->id)
            : Emergencies::findOrFail($request->id);

        $modelo->fotos()->save($foto);

        return response()->json([
            'id'      => $foto->id,
            'url' => URL::to(Storage::url($foto->url)),
            'takenAt' => optional($foto->created_at)->toIso8601String(),
        ], 201);
    }

    // ✅ Web: Vista de selección de proyectos
    public function projects()
    {
        $jobs = JobRequest::has('fotos')->get();
        $emergencies = Emergencies::has('fotos')->get();

        return view('admin.photos.projects', compact('jobs', 'emergencies'));
    }

    // ✅ Web: Vista de fotos por proyecto - CORREGIDO CON CAMPOS REALES
    public function view(string $tipo, int $id)
    {
        $modelo = $this->findModel($tipo, $id);
        
        // Obtener información completa del proyecto
        $projectInfo = $this->getProjectInfo($modelo, $tipo);

        $existingShare = PhotoShare::for($tipo, $id)->first();
        $shareUrl = $existingShare?->public_url;

        return view('admin.photos.view', [
            'fotos' => $modelo->fotos,
            'tipo'  => $tipo,
            'id'    => $id,
            'shareUrl' => $shareUrl,
            'projectInfo' => $projectInfo,
        ]);
    }

    public function createShareWeb(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|in:job_request,emergency',
            'id'   => 'required|integer',
        ]);

        $share = PhotoShare::ensure($data['tipo'], (int) $data['id'], optional($request->user())->id);

        return back()
            ->with('status', 'Public link created')
            ->with('share_url', $share->public_url);
    }

    public function publicGallery(string $token)
    {
        $share = PhotoShare::where('token', $token)->firstOrFail();
        $modelo = $this->findModel($share->type, $share->model_id);

        // Obtener información del proyecto para la vista pública
        $projectInfo = $this->getProjectInfo($modelo, $share->type);

        return view('admin.photos.public', [
            'fotos' => $modelo->fotos,
            'tipo'  => $share->type,
            'id'    => $share->model_id,
            'projectInfo' => $projectInfo,
        ]);
    }

    private function findModel(string $tipo, int $id)
    {
        return $tipo === 'job_request'
            ? JobRequest::with('fotos')->findOrFail($id)
            : Emergencies::with('fotos')->findOrFail($id);
    }

    /**
     * Obtiene información estructurada del proyecto según el tipo
     */
    private function getProjectInfo($model, string $tipo)
    {
        if ($tipo === 'job_request') {
            return $this->getJobRequestInfo($model);
        } else {
            return $this->getEmergencyInfo($model);
        }
    }

    /**
     * Información específica para JobRequest - CAMPOS REALES
     */
    private function getJobRequestInfo(JobRequest $job)
    {
        return (object) [
            // Información básica
            'company_name' => $job->company_name,
            'job_number_name' => $job->job_number_name,
            
            // Representante de la compañía
            'company_rep' => $job->company_rep,
            'company_rep_phone' => $job->company_rep_phone,
            'company_rep_email' => $job->company_rep_email,
            
            // Información del cliente
            'customer_first_name' => $job->customer_first_name,
            'customer_last_name' => $job->customer_last_name,
            'customer_phone' => $job->customer_phone_number, // Nota: se llama customer_phone_number en el modelo
            
            // Ubicación - CAMPOS REALES
            'job_address_street' => $job->job_address_street_address,
            'job_address_street_line2' => $job->job_address_street_address_line_2,
            'job_address_city' => $job->job_address_city,
            'job_address_state' => $job->job_address_state,
            'job_address_zip' => $job->job_address_zip_code,
            
            // Fechas
            'install_date_requested' => $job->install_date_requested,
            'delivery_date' => $job->delivery_date,
            'date_submitted' => $job->created_at,
            
            // Información adicional
            'special_instructions' => $job->special_instructions,
            'material_verification' => $job->material_verification,
            'status' => $job->status,
            
            // Materiales - CAMPOS REALES
            'material_roof_loaded' => $job->material_roof_loaded,
            'starter_bundles_ordered' => $job->starter_bundles_ordered,
            'hip_and_ridge_ordered' => $job->hip_and_ridge_ordered,
            'field_shingle_bundles_ordered' => $job->field_shingle_bundles_ordered,
            'modified_bitumen_cap_rolls_ordered' => $job->modified_bitumen_cap_rolls_ordered,
        ];
    }

    /**
     * Información específica para Emergencies - CAMPOS REALES
     */
    private function getEmergencyInfo(Emergencies $emergency)
    {
        return (object) [
            // Información básica
            'company_name' => $emergency->company_name,
            'job_number_name' => $emergency->job_number_name,
            'type_of_supplement' => $emergency->type_of_supplement,
            
            // Contacto de la compañía
            'company_contact_email' => $emergency->company_contact_email,
            
            // Ubicación - CAMPOS REALES
            'job_address' => $emergency->job_address,
            'job_address_line2' => $emergency->job_address_line2,
            'job_city' => $emergency->job_city,
            'job_state' => $emergency->job_state,
            'job_zip' => $emergency->job_zip_code, // Nota: se llama job_zip_code en el modelo
            
            // Fechas
            'date_submitted' => $emergency->date_submitted,
            'created_at' => $emergency->created_at,
            
            // Información adicional
            'terms_conditions' => $emergency->terms_conditions,
            'requirements' => $emergency->requirements,
            'status' => $emergency->status,
        ];
    }
}