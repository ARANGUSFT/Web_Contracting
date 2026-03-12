<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\leadFile;
use App\Models\LeadFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeadFilesController extends Controller
{
    private function getCurrentUser()
    {
        return Auth::guard('web')->check()
            ? ['type' => 'user', 'instance' => Auth::guard('web')->user()]
            : ['type' => 'team', 'instance' => Auth::guard('team')->user()];
    }

    public function storeFolder(Request $request, $leadId)
    {
        $lead = Lead::findOrFail($leadId);

        $request->validate([
            'folder_name' => 'required|string|max:255',
        ]);

        $lead->folders()->create([
            'name' => $request->folder_name,
        ]);

        return back()->with('success', 'Folder created successfully.');
    }

    public function store(Request $request, $leadId)
    {
        $current = $this->getCurrentUser();
        $lead = Lead::findOrFail($leadId);
        $user = $current['instance'];

        if (!$this->canManageLead($lead, $user, $current['type'])) {
            abort(403, 'You do not have permissions to delete this lead.');
        }

        // Validación para múltiples archivos
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|max:10240',
            'folder_id' => 'nullable|exists:lead_folders,id',
            'type' => 'nullable|string|max:255',
        ]);

        $uploadedFiles = [];

        // Procesar cada archivo
        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();

            // Si hay carpeta, guardamos dentro de ella; si no, usamos tipo antiguo
            if ($request->filled('folder_id')) {
                $folder = LeadFolder::findOrFail($request->folder_id);
                $folderName = $folder->name;
            } else {
                $folderName = $request->input('type', 'Other');
            }

            $path = $file->storeAs(
                "lead_files/{$lead->id}/{$folderName}",
                $originalName,
                'public'
            );

            $data = [
                'lead_id'   => $lead->id,
                'folder_id' => $request->folder_id,
                'file_path' => $path,
            ];

            if ($current['type'] === 'user') {
                $data['user_id'] = $user->id;
            } else {
                $data['team_id'] = $user->id;
            }

            $uploadedFile = leadFile::create($data);
            $uploadedFiles[] = $uploadedFile;
        }

        $fileCount = count($uploadedFiles);
        return redirect()->back()->with('success', "{$fileCount} document(s) uploaded successfully");
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
        ]);

        $folder = LeadFolder::findOrFail($id);
        $folder->name = $request->folder_name;
        $folder->save();

        return back()->with('success', 'Folder renamed successfully.');
    }

    public function destroy($id)
    {
        $current = $this->getCurrentUser();
        $file = leadFile::findOrFail($id);
        $user = $current['instance'];

        if (!$this->canManageLead($file->lead, $user, $current['type'])) {
            abort(403, 'You do not have permissions to delete this document.');
        }

        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return redirect()->back()->with('success', 'File successfully deleted');
    }

    public function destroyFolder($id)
    {
        $folder = \App\Models\LeadFolder::findOrFail($id);

        // Eliminar archivos del storage físico
        $leadId = $folder->lead_id;
        $folderPath = "lead_files/{$leadId}/{$folder->name}";
        \Illuminate\Support\Facades\Storage::disk('public')->deleteDirectory($folderPath);

        // Eliminar registros de archivos en la base de datos
        $folder->files()->delete();

        // Eliminar la carpeta de la base de datos
        $folder->delete();

        return redirect()->back()->with('success', 'Folder and its files deleted successfully.');
    }

    private function canManageLead($lead, $user, $type)
    {
        if ($type === 'user') {
            return $lead->user_id === $user->id;
        } else {
            return $lead->team_id === $user->id || in_array($user->role, [
                'manager', 'company_admin', 'project_manager'
            ]);
        }
    }
}