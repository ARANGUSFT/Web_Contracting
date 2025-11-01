<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LeadImageController extends Controller
{
    public function store(Request $request)
    {
        @ini_set('upload_max_filesize', '512M');
        @ini_set('post_max_size', '512M');
        @ini_set('max_file_uploads', '1000');
        @ini_set('max_execution_time', '600');
        @ini_set('memory_limit', '1024M');

        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:51200',
        ]);

        try {
            Storage::disk('public')->makeDirectory('lead_images');

            $userId = auth('web')->id() ?? null;
            $teamId = auth('team')->id() ?? null;

            if (!$userId && !$teamId) {
                return response()->json(['success' => false, 'error' => 'No autenticado.'], 401);
            }

            $uploadedImages = collect();
            $files = $request->file('images');

            foreach (array_chunk($files, 50) as $chunkIndex => $chunk) {
                foreach ($chunk as $image) {
                    try {
                        $path = $image->store('lead_images', 'public');

                        $imageModel = LeadImage::create([
                            'lead_id' => $request->lead_id,
                            'user_id' => $userId,
                            'team_id' => $teamId,
                            'image_path' => $path,
                            'original_name' => $image->getClientOriginalName(),
                            'file_size' => $image->getSize(),
                        ]);

                        $uploadedImages->push([
                            'id' => $imageModel->id,
                            'url' => asset('storage/' . $path),
                            'name' => $image->getClientOriginalName(),
                            'path' => $path,
                            'created_at' => $imageModel->created_at->format('Y-m-d H:i:s'),
                            'file_size' => round($imageModel->file_size / 1024, 1) . ' KB',
                        ]);
                    } catch (\Throwable $t) {
                        Log::error("Error uploading image chunk {$chunkIndex}: " . $t->getMessage());
                    }
                }
                gc_collect_cycles();
            }

            if ($uploadedImages->isEmpty()) {
                return response()->json(['success' => false, 'error' => 'No se pudo subir ninguna imagen.'], 400);
            }

            // ✅ Obtener el nuevo total de imágenes después del upload
            $totalImages = LeadImage::where('lead_id', $request->lead_id)->count();

            return response()->json([
                'success' => true,
                'message' => "{$uploadedImages->count()} imagen(es) subida(s) correctamente.",
                'images' => $uploadedImages,
                'uploaded_count' => $uploadedImages->count(),
                'total_images' => $totalImages // ✅ Nuevo campo
            ]);
        } catch (\Throwable $e) {
            Log::error("Error uploading images: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Error subiendo imágenes.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $image = LeadImage::findOrFail($id);
            $leadId = $image->lead_id; // ✅ Guardar lead_id antes de eliminar

            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
                Log::info("Imagen eliminada: {$image->image_path}");
            }

            $image->delete();

            // ✅ Obtener el nuevo total de imágenes después de eliminar
            $totalImages = LeadImage::where('lead_id', $leadId)->count();

            return response()->json([
                'success' => true, 
                'message' => 'Imagen eliminada correctamente.',
                'total_images' => $totalImages // ✅ Nuevo campo
            ]);
        } catch (\Throwable $e) {
            Log::error("Error deleting image: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Error al eliminar imagen.'], 500);
        }
    }

    public function getLeadImagesPaginated(Request $request, $leadId)
    {
        $perPage = $request->get('per_page', 20);
        $page = $request->get('page', 1);

        $images = LeadImage::where('lead_id', $leadId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $mapped = $images->map(function ($img) {
            return [
                'id' => $img->id,
                'url' => asset('storage/' . $img->image_path),
                'name' => $img->original_name,
                'created_at' => $img->created_at->format('M d, Y'),
            ];
        });

        return response()->json([
            'success' => true,
            'images' => $mapped,
            'next_page' => $images->hasMorePages() ? $images->currentPage() + 1 : null,
            'total' => $images->total(),
            'current_count' => $images->count(),
        ]);
    }

    // ✅ NUEVO: Endpoint para obtener solo el conteo total
    public function getImagesCount($leadId)
    {
        try {
            $count = LeadImage::where('lead_id', $leadId)->count();
            return response()->json(['success' => true, 'total_images' => $count]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'Error getting count'], 500);
        }
    }
}