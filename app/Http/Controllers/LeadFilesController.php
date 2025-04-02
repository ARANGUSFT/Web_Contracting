<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadFile;
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

        // Validación de permisos (user_id o team_id deben coincidir)
        if ($lead->user_id !== $user->id && $lead->team_id !== $user->id) {
            abort(403);
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

        // Asignar correctamente user_id o team_id
        if ($current['type'] === 'user') {
            $data['user_id'] = $user->id;
        } else {
            $data['team_id'] = $user->id;
        }

        LeadFile::create($data);

        return redirect()->back()->with('success', 'Document uploaded successfully');
    }

    public function destroy($id)
    {
        $current = $this->getCurrentUser();
        $file = LeadFile::findOrFail($id);
        $user = $current['instance'];

        if (
            ($file->lead->user_id !== $user->id) &&
            ($file->lead->team_id !== $user->id)
        ) {
            abort(403);
        }

        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return redirect()->back()->with('success', 'File successfully deleted');
    }
}
