<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\leadFile;
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

    public function store(Request $request, $leadId)
    {
        $current = $this->getCurrentUser();
        $lead = Lead::findOrFail($leadId);
        $user = $current['instance'];

        // Nueva validación de permisos:
        if (!$this->canManageLead($lead, $user, $current['type'])) {
            abort(403, 'No tienes permisos para modificar este lead.');
        }

        $request->validate([
            'type' => 'required|in:files,finanzas,anexos,contratos',
            'file' => 'required|file|max:10240',
        ]);

        $originalName = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs(
            "lead_files/{$lead->id}/{$request->type}",
            $originalName,
            'public'
        );

        $data = [
            'lead_id'   => $lead->id,
            'type'      => $request->type,
            'file_path' => "lead_files/{$lead->id}/{$request->type}/{$originalName}",
        ];

        if ($current['type'] === 'user') {
            $data['user_id'] = $user->id;
        } else {
            $data['team_id'] = $user->id;
        }

        leadFile::create($data);

        return redirect()->back()->with('success', 'Document uploaded successfully');
    }

    public function destroy($id)
    {
        $current = $this->getCurrentUser();
        $file = leadFile::findOrFail($id);
        $user = $current['instance'];

        if (!$this->canManageLead($file->lead, $user, $current['type'])) {
            abort(403, 'No tienes permisos para eliminar este documento.');
        }

        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return redirect()->back()->with('success', 'File successfully deleted');
    }

    private function canManageLead($lead, $user, $type)
    {
        if ($type === 'user') {
            return $lead->user_id === $user->id;
        } else {
            // Si es team (vendedor o manager)
            return $lead->team_id === $user->id || in_array($user->role, ['manager', 'company_admin', 'project_manager']);
        }
    }
}
