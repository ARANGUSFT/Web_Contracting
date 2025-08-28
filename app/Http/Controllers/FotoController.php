<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\JobRequest;
use App\Models\Emergencies;
use App\Models\PhotoShare;
use Illuminate\Http\Request;

class FotoController extends Controller
{
    // ✅ API: Obtener fotos de un proyecto
    public function index($tipo, $id)
    {
        $modelo = $tipo === 'job_request'
            ? JobRequest::findOrFail($id)
            : Emergencies::findOrFail($id);

        $fotos = $modelo->fotos()->get()->map(function ($foto) {
            return [
                'url' => asset('storage/' . $foto->url),
                'takenAt' => optional($foto->created_at)->toIso8601String(), // 2025-08-11T14:03:22Z
            ];
        });

        return response()->json($fotos);
    }

    // ✅ API: Guardar foto desde la app
    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:job_request,emergency',
            'id'   => 'required|integer',
            'foto' => 'required|image|max:2048',
        ]);

        $file = $request->file('foto');
        $path = $file->store('fotos', 'public');

        $foto = new Foto(['url' => $path]);
        $modelo = $request->tipo === 'job_request'
            ? JobRequest::findOrFail($request->id)
            : Emergencies::findOrFail($request->id);

        $modelo->fotos()->save($foto); // setea created_at

        return response()->json([
            'id'      => $foto->id,
            'url'     => asset('storage/' . $foto->url),
            'takenAt' => optional($foto->created_at)->toIso8601String(),
        ], 201);
    }


    // ✅ Web: Vista de selección de proyectos
    public function projects()
    {
      $jobs = JobRequest::has('fotos')->get(); // Solo los que tienen fotos
      $emergencies = Emergencies::has('fotos')->get(); // Solo los que tienen fotos

        return view('admin.photos.projects', compact('jobs', 'emergencies'));
    }

    // ✅ Web: Vista de fotos por proyecto
    public function view(string $tipo, int $id)
    {
        $modelo = $this->findModel($tipo, $id);

        $existingShare = PhotoShare::for($tipo, $id)->first();
        $shareUrl = $existingShare?->public_url;

        return view('admin.photos.view', [
            'fotos' => $modelo->fotos,
            'tipo'  => $tipo,
            'id'    => $id,
            'shareUrl' => $shareUrl,
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

        // Vista pública (simple, solo lectura)
        return view('admin.photos.public', [
            'fotos' => $modelo->fotos,
            'tipo'  => $share->type,
            'id'    => $share->model_id,
        ]);
    }

    private function findModel(string $tipo, int $id)
    {
        return $tipo === 'job_request'
            ? JobRequest::with('fotos')->findOrFail($id)
            : Emergencies::with('fotos')->findOrFail($id);
    }
}
