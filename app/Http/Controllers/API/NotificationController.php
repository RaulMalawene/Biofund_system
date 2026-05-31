<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    /**
     * Lista notificações de sistema do utilizador autenticado.
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = NotificationLog::where('user_id', $request->user()->id)
            ->system()
            ->with('occurrence:id,tracking_code,subject,status')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(fn ($n) => [
                'id'          => $n->id,
                'event_type'  => $n->event_type,
                'message'     => $n->message,
                'read_at'     => $n->read_at,
                'created_at'  => $n->created_at,
                'occurrence'  => $n->occurrence ? [
                    'id'            => $n->occurrence->id,
                    'tracking_code' => $n->occurrence->tracking_code,
                    'subject'       => $n->occurrence->subject,
                    'status'        => $n->occurrence->status,
                ] : null,
            ]);

        return response()->json(['notifications' => $notifications]);
    }

    /**
     * Conta notificações de sistema não lidas.
     * Cache de 30s por utilizador - reduz queries repetidas do polling do frontend.
     */
    public function count(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $unread = Cache::remember("notif_count.{$userId}", 30, function () use ($userId) {
            return NotificationLog::where('user_id', $userId)
                ->system()
                ->unread()
                ->count();
        });

        return response()->json(['unread' => $unread]);
    }

    /**
     * Marca uma notificação como lida.
     */
    public function markRead(Request $request, NotificationLog $notification): JsonResponse
    {
        abort_if($notification->user_id !== $request->user()->id, 403);

        $notification->update(['read_at' => now()]);
        Cache::forget("notif_count.{$request->user()->id}");

        return response()->json(['message' => 'Notificação marcada como lida.']);
    }

    /**
     * Marca todas as notificações do utilizador como lidas.
     */
    public function markAllRead(Request $request): JsonResponse
    {
        NotificationLog::where('user_id', $request->user()->id)
            ->system()
            ->unread()
            ->update(['read_at' => now()]);

        Cache::forget("notif_count.{$request->user()->id}");

        return response()->json(['message' => 'Todas as notificações marcadas como lidas.']);
    }
}
