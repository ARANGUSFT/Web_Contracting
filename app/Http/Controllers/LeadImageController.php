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
            // Verificar si la carpeta existe, si no, crearla
            if (!Storage::disk('public')->exists('lead_images')) {
                Storage::disk('public')->makeDirectory('lead_images');
                Log::info("Carpeta lead_images creada en storage.");
            }

            // Guardar imagen en almacenamiento público
            $path = $request->file('image')->store('lead_images', 'public');

            if (!$path) {
                throw new \Exception("Error al guardar la imagen en almacenamiento.");
            }

            // Verificar si el usuario autenticado es un `User` o `Team`
            if (!Auth::check()) {
                throw new \Exception("No se pudo determinar el usuario autenticado.");
            }

            $user = Auth::user();

            // Si el usuario tiene rol de `seller`, `manager` o `crew`, se guarda como `Team`
            if (property_exists($user, 'role') && in_array($user->role, ['seller', 'manager', 'crew'])) {
                $uploadedByType = 'App\Models\Team';
            } else {
                $uploadedByType = 'App\Models\User';
            }

            // Guardar en la base de datos
            $image = LeadImage::create([
                'lead_id' => $request->lead_id,
                'uploaded_by_id' => $user->id,
                'uploaded_by_type' => $uploadedByType,
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
