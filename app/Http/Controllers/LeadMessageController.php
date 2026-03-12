<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadMessage;
use App\Models\Lead;


class LeadMessageController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'message' => 'required|string',
        ]);
    
        $userId = auth('web')->check() ? auth('web')->id() : null;
        $teamId = auth('team')->check() ? auth('team')->id() : null;
    
        if (!$userId && !$teamId) {
            return back()->withErrors(['error' => 'No autenticado']);
        }
    
        LeadMessage::create([
            'lead_id' => $request->lead_id,
            'user_id' => $userId,
            'team_id' => $teamId,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true
        ]);
    }
    

    



    public function index($lead_id)
    {
        $query = LeadMessage::where('lead_id', $lead_id)->with(['user', 'team']);
    
        if (auth('team')->check()) {
            // Solo mensajes creados por el vendedor (team_id no nulo)
            $query->where('team_id', auth('team')->id());
        }
    
        return response()->json($query->orderBy('created_at', 'asc')->get());
    }


    public function destroy($id)
{
    $message = LeadMessage::findOrFail($id);

    if ($message->user_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $message->delete();

    return response()->json(['success' => true]);
}



    
}
