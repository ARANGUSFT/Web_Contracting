<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Obtener los mensajes entre el usuario autenticado y otro usuario
     */
    public function index(int $userId)
    {
        $authUser = Auth::user();

        $messages = Chat::where(function ($q) use ($authUser, $userId) {
                $q->where('sender_id', $authUser->id)
                  ->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($authUser, $userId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', $authUser->id);
            })
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }

    /**
     * Enviar un nuevo mensaje
     */
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = Chat::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message'     => $request->message,
        ]);

        return response()->json($message, 201);
    }

    /**
     * Obtener lista de usuarios disponibles para chatear (solo admins)
     */
    public function users()
    {
        $authUser = Auth::user();

        if (!$authUser->is_admin) {
            return response()->json(['error' => 'Acceso no autorizado'], 403);
        }

        $users = User::where('is_admin', false)->get();

        return response()->json($users);
    }

    /**
     * Vista principal del chat
     */
    public function chatView()
    {
        $authUser = Auth::user();

        $users = $authUser->is_admin
            ? User::where('is_admin', false)->get()
            : User::where('is_admin', true)->get();

        $view = $authUser->is_admin ? 'admin.chat_admin.index' : 'chat_contractor.user';

        return view($view, compact('users'));
    }

}