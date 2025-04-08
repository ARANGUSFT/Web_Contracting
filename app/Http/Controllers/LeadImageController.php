<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeadImageController extends Controller
{
    /**
     * Subir una imagen asociada a un Lead.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            if (!Storage::disk('public')->exists('lead_images')) {
                Storage::disk('public')->makeDirectory('lead_images');
                Log::info("Carpeta lead_images creada en storage.");
            }

            $path = $request->file('image')->store('lead_images', 'public');

            if (!$path) {
                throw new \Exception("Error al guardar la imagen en almacenamiento.");
            }

            $userId = auth('web')->check() ? auth('web')->id() : null;
            $teamId = auth('team')->check() ? auth('team')->id() : null;

            if (!$userId && !$teamId) {
                throw new \Exception("No autenticado.");
            }

            $image = LeadImage::create([
                'lead_id' => $request->lead_id,
                'user_id' => $userId,
                'team_id' => $teamId,
                'image_path' => $path,
            ]);

            Log::info("Imagen subida correctamente: " . $path);

            return response()->json([
                'success' => true,
                'message' => 'Imagen subida correctamente.',
                'image' => $image
            ]);
        } catch (\Exception $e) {
            Log::error("Error al subir la imagen: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Error al subir la imagen: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Eliminar una imagen.
     */
    public function destroy($id)
    {
        try {
            $image = LeadImage::findOrFail($id);

            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
                Log::info("Imagen eliminada del disco: {$image->image_path}");
            } else {
                Log::warning("Imagen no encontrada en disco: {$image->image_path}");
            }

            $image->delete();

            return redirect()->back()->with('success', 'Imagen eliminada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar imagen: " . $e->getMessage());

            return redirect()->back()->with('error', 'Error al eliminar la imagen: ' . $e->getMessage());
        }
    }

    
    
}
