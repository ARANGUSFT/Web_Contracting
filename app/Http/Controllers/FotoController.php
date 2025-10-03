<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\JobRequest;
use App\Models\Emergencies;
use App\Models\PhotoShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // ⬅️ añade esto
use Illuminate\Support\Facades\URL;   // ⬅️ agrega esto


class FotoController extends Controller
{

    public function index($tipo, $id)
    {
        $modelo = $tipo === 'job_request'
            ? JobRequest::findOrFail($id)
            : Emergencies::findOrFail($id);

        $fotos = $modelo->fotos()->get()->map(function ($foto) {
            return [
                'url' => URL::to(Storage::url($foto->url)),  // antes: Storage::url(...)

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

        $path = $request->file('foto')->store('fotos', 'public'); // guarda 'fotos/xxx.png'

        $foto = new Foto(['url' => $path]);

        $modelo = $request->tipo === 'job_request'
            ? JobRequest::findOrFail($request->id)
            : Emergencies::findOrFail($request->id);

        $modelo->fotos()->save($foto);

        return response()->json([
            'id'      => $foto->id,
            'url' => URL::to(Storage::url($foto->url)),  // antes: Storage::url(...)
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
