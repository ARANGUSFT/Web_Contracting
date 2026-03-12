<?php

namespace App\Http\Controllers;

use App\Models\LeadImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache; // ← AGREGAR ESTA LÍNEA
use ZipArchive;

class LeadImageController extends Controller
{
    /**
     * Display paginated images for a lead
     */
    public function index($lead_id)
    {
        try {
            $images = LeadImage::where('lead_id', $lead_id)
                ->latest()
                ->paginate(20);

            return response()->json([
                'success' => true,
                'images' => $images,
                'pagination' => [
                    'current_page' => $images->currentPage(),
                    'last_page' => $images->lastPage(),
                    'per_page' => $images->perPage(),
                    'total' => $images->total(),
                    'from' => $images->firstItem(),
                    'to' => $images->lastItem(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading lead images: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading images'
            ], 500);
        }
    }

    /**
     * Store multiple uploaded images - VERSION SIMPLIFICADA Y SEGURA
     */
    public function store(Request $request)
    {
        try {
            Log::info('=== UPLOAD START ===', [
                'lead_id' => $request->lead_id,
                'files_count' => $request->hasFile('images') ? count($request->file('images')) : 0
            ]);

            if (!$request->has('lead_id')) {
                return response()->json(['success' => false, 'message' => 'Lead ID is required'], 422);
            }

            if (!$request->hasFile('images')) {
                return response()->json(['success' => false, 'message' => 'No images provided'], 422);
            }

            $files = $request->file('images');
            $uploaded = [];
            $processedHashes = []; // Para evitar duplicados en la misma subida

            DB::beginTransaction();

            foreach ($files as $index => $file) {
                if (!$file->isValid()) {
                    Log::warning('Invalid file skipped: ' . $file->getClientOriginalName());
                    continue;
                }

                // Calcular hash del contenido
                $fileHash = md5_file($file->getRealPath());

                // Verificar duplicados en la misma subida
                if (in_array($fileHash, $processedHashes)) {
                    Log::info('Duplicate file skipped (same request): ' . $file->getClientOriginalName());
                    continue;
                }

                // Verificar duplicados en base de datos por hash
                $existingImage = LeadImage::where('lead_id', $request->lead_id)
                    ->where('file_hash', $fileHash)
                    ->first();

                if ($existingImage) {
                    Log::info('Duplicate file skipped (exists in DB): ' . $file->getClientOriginalName());
                    continue;
                }

                $processedHashes[] = $fileHash;

                // Generar nombre único
                $fileName = 'lead_' . $request->lead_id . '_' . time() . '_' . $index . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('leads/images', $fileName, 'public');

                // Preparar datos
                $imageData = [
                    'lead_id' => $request->lead_id,
                    'image_path' => $path,
                    'user_id' => auth()->id(),
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'file_hash' => $fileHash,
                ];

                // Crear registro
                $image = LeadImage::create($imageData);
                $uploaded[] = $image;

                Log::info('File uploaded: ' . $file->getClientOriginalName() . ' -> ID: ' . $image->id);
            }

            DB::commit();

            Log::info('=== UPLOAD COMPLETED ===', [
                'lead_id' => $request->lead_id,
                'files_uploaded' => count($uploaded),
                'files_skipped' => count($files) - count($uploaded)
            ]);

            return response()->json([
                'success' => true,
                'message' => count($uploaded) . ' image(s) uploaded successfully',
                'uploaded_count' => count($uploaded),
                'skipped_count' => count($files) - count($uploaded),
                'images' => $uploaded,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== UPLOAD FAILED ===', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error uploading images: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a single image
     */
    public function destroy($id)
    {
        try {
            $image = LeadImage::findOrFail($id);

            // Delete physical file
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            $image->delete();

            return response()->json([
                'success' => true, 
                'message' => 'Image deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting image ID ' . $id . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image'
            ], 500);
        }
    }

    /**
     * Bulk delete selected images
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'exists:lead_images,id'
            ]);

            $ids = $request->ids;
            $deletedCount = 0;

            DB::beginTransaction();

            $images = LeadImage::whereIn('id', $ids)->get();
            
            foreach ($images as $image) {
                // Delete physical file
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                
                // Delete database record
                $image->delete();
                $deletedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => "{$deletedCount} images deleted successfully",
                'deleted_count' => $deletedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk delete failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting selected images'
            ], 500);
        }
    }

    /**
     * Delete all images for a specific lead
     */
    public function deleteAll($lead_id)
    {
        try {
            $deletedCount = 0;

            DB::beginTransaction();

            $images = LeadImage::where('lead_id', $lead_id)->get();
            
            foreach ($images as $image) {
                // Delete physical file
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                
                // Delete database record
                $image->delete();
                $deletedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => "All {$deletedCount} images deleted successfully",
                'deleted_count' => $deletedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete all failed for lead ' . $lead_id . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting all images'
            ], 500);
        }
    }

    /**
     * Get image count for a lead
     */
    public function getCount($lead_id)
    {
        try {
            $count = LeadImage::where('lead_id', $lead_id)->count();
            
            return response()->json([
                'success' => true,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting image count for lead ' . $lead_id . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error getting image count'
            ], 500);
        }
    }


    public function getAllIds($lead_id)
    {
        try {
            $ids = LeadImage::where('lead_id', $lead_id)->pluck('id');
            return response()->json([
                'success' => true,
                'ids' => $ids
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error'], 500);
        }
    }

    /**
     * Download selected images as a ZIP file
     */
    public function downloadZip(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'exists:lead_images,id'
            ]);

            $images = LeadImage::whereIn('id', $request->ids)->get();

            if ($images->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No images found'
                ], 404);
            }

            // Crear archivo ZIP temporal
            $zipFileName = 'lead_' . $images->first()->lead_id . '_images_' . time() . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);

            // Crear directorio temporal si no existe
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $zip = new ZipArchive;
            if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
                throw new \Exception('Cannot create zip file');
            }

            $addedCount = 0;
            foreach ($images as $image) {
                // Usar el disco 'public' para obtener la ruta física
                $filePath = Storage::disk('public')->path($image->image_path);
                
                if (file_exists($filePath)) {
                    // Usar el nombre original si existe, si no, el nombre del archivo en el path
                    $fileNameInZip = $image->file_name ?? basename($image->image_path);
                    $zip->addFile($filePath, $fileNameInZip);
                    $addedCount++;
                } else {
                    Log::warning('Image file not found for ZIP: ' . $image->image_path);
                }
            }

            $zip->close();

            if ($addedCount === 0) {
                unlink($zipPath);
                return response()->json([
                    'success' => false,
                    'message' => 'No valid image files found to zip'
                ], 404);
            }

            // Descargar y eliminar el archivo temporal después de enviarlo
            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error creating ZIP: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating ZIP: ' . $e->getMessage()
            ], 500);
        }
    }

}