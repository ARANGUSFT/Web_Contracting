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
        return Auth::guard('web')->check() ? Auth::guard('web')->user() : Auth::guard('team')->user();
    }

    public function store(Request $request, $leadId)
    {
        $user = $this->getCurrentUser();
        $lead = Lead::findOrFail($leadId);

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
        
        LeadFile::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'type' => $request->type,
            'file_path' => "lead_files/{$lead->id}/{$request->type}/{$originalName}",
        ]);
        

        return redirect()->back()->with('success', 'Document uploaded successfully');
    }

    public function destroy($id)
    {
        $user = $this->getCurrentUser();
        $file = LeadFile::findOrFail($id);

        if ($file->lead->user_id !== $user->id && $file->lead->team_id !== $user->id) {
            abort(403);
        }

        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return redirect()->back()->with('success', 'File successfully deleted');
    }
}
