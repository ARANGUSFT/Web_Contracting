<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Utilidad para respuestas JSON sin cache (útil en polling)
    private function jsonNoCache($payload, int $status = 200)
    {
        return response()
            ->json($payload, $status)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    /**
     * GET /.../chat/{userId}?after_id=NN&limit=200
     * Respuesta uniforme: { messages: [...], last_id: int }
     */
    public function index(Request $request, int $userId)
    {
        $me      = Auth::user();
        $afterId = (int) $request->query('after_id', 0);
        $limit   = min(max((int) $request->query('limit', 200), 1), 200);

        // 1) Validar existencia de userId
        $other = User::find($userId);
        if (!$other) {
            return $this->jsonNoCache(['messages' => [], 'last_id' => $afterId], Response::HTTP_NOT_FOUND);
        }

        // 1b) (Opcional) Regla de quién puede hablar con quién
        // if ($me->is_admin === $other->is_admin) {
        //     return $this->jsonNoCache(['messages' => [], 'last_id' => $afterId], 403);
        // }

        if ($me->id === $userId) {
            return $this->jsonNoCache(['messages' => [], 'last_id' => $afterId]);
        }

        $messages = Chat::query()
            ->where(function ($q) use ($me, $userId) {
                $q->where('sender_id', $me->id)->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($me, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $me->id);
            })
            ->when($afterId > 0, fn ($q) => $q->where('id', '>', $afterId))
            ->orderBy('id') // asc
            ->limit($limit)
            ->get(['id','sender_id','receiver_id','message','is_read','created_at']);

        // (Opcional) formatear created_at ISO para clientes no-Laravel
        // $messages->transform(function($m){ $m->created_at = $m->created_at->toIso8601String(); return $m; });

        return $this->jsonNoCache([
            'messages' => $messages,
            'last_id'  => $messages->last()->id ?? $afterId,
        ]);
    }

    /**
     * POST /.../chat/send  {receiver_id, message}
     */
    public function send(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => ['required','exists:users,id'],
            'message'     => ['required','string','max:5000'],
        ]);

        $me = Auth::id();

        if ($me === (int) $data['receiver_id']) {
            return $this->jsonNoCache(['error' => 'No puedes enviarte mensajes a ti mismo.'], 422);
        }

        // (Opcional) Regla de roles
        // $receiver = User::find($data['receiver_id']);
        // if (!$receiver || $receiver->is_admin === Auth::user()->is_admin) {
        //     return $this->jsonNoCache(['error' => 'No autorizado.'], 403);
        // }

        $msg = Chat::create([
            'sender_id'   => $me,
            'receiver_id' => (int) $data['receiver_id'],
            'message'     => $data['message'],
            'is_read'     => false,
        ]);


        return $this->jsonNoCache($msg->only(['id','sender_id','receiver_id','message','is_read','created_at']), 201);
    }

    /**
     * POST /.../chat/{userId}/read
     * Marca como leídos todos los mensajes del otro → yo.
     */
    public function markRead(int $userId)
    {
        $me = Auth::id();

        // (Opcional) validar existencia
        if (!User::whereKey($userId)->exists()) {
            return $this->jsonNoCache(['updated' => 0], 404);
        }

        $updated = Chat::query()
            ->where('sender_id', $userId)
            ->where('receiver_id', $me)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return $this->jsonNoCache(['updated' => $updated]);
    }

    /**
     * GET /.../chat-unread/count
     * Total de no leídos del autenticado.
     */
    public function unreadCount()
    {
        $me = Auth::id();

        $count = Chat::query()
            ->where('receiver_id', $me)
            ->where('is_read', false)
            ->count();

        return $this->jsonNoCache(['unread' => $count]);
    }

    /**
     * GET /superadmin/chat  o  GET /chat
     * Admin ve contratistas; contratista ve admins.
     */
    public function chatView()
    {
        $me = Auth::user();

        $users = $me->is_admin
            ? User::where('is_admin', false)->orderBy('name')->get(['id','name','email'])
            : User::where('is_admin', true)->orderBy('name')->get(['id','name','email']);

        $view = $me->is_admin ? 'admin.chat_admin.index' : 'chat_contractor.user';
        return view($view, compact('users'));
    }

    /**
     * GET /superadmin/chat-users (solo admins)
     */
    public function users()
    {
        $me = Auth::user();

        if (!$me->is_admin) {
            return $this->jsonNoCache(['error' => 'Acceso no autorizado'], 403);
        }

        $users = User::where('is_admin', false)
            ->where('id', '!=', $me->id)
            ->orderBy('name')
            ->get(['id','name','email']);

        return $this->jsonNoCache($users);
    }
}
