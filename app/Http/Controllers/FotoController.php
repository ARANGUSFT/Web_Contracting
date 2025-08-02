<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\JobRequest;
use App\Models\Emergencies;
use Illuminate\Http\Request;

class FotoController extends Controller
{
    // ✅ API: Obtener fotos de un proyecto
    public function index($tipo, $id)
    {
        $modelo = $tipo === 'job_request'
            ? JobRequest::findOrFail($id)
            : Emergencies::findOrFail($id);

        $fotos = $modelo->fotos()->get()->map(function($foto) {
            return asset('storage/' . $foto->url);
        });

        return response()->json($fotos);
    }

    // ✅ API: Guardar foto desde la app
    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:job_request,emergency',
            'id' => 'required|integer',
            'foto' => 'required|image|max:2048'
        ]);

        $file = $request->file('foto');
        $path = $file->store('fotos', 'public');

        $foto = new Foto(['url' => $path]);

        $modelo = $request->tipo === 'job_request'
            ? JobRequest::findOrFail($request->id)
            : Emergencies::findOrFail($request->id);

        $modelo->fotos()->save($foto);

        return response()->json([
            'id' => $foto->id,
            'url' => asset('storage/' . $foto->url)
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
    public function view($tipo, $id)
    {
        $modelo = $tipo === 'job_request'
            ? JobRequest::with('fotos')->findOrFail($id)
            : Emergencies::with('fotos')->findOrFail($id);

        return view('admin.photos.view', [
            'fotos' => $modelo->fotos,
            'tipo' => $tipo,
            'id' => $id
        ]);
    }
}
