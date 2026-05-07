<?php

namespace App\Http\Controllers\Api;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Occurrence;
use App\Models\OccurrenceAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttachmentController extends Controller
{
    /**
     * Faz o download de um ficheiro anexado a uma ocorrência.
     * O acesso é restrito de acordo com o perfil do utilizador autenticado:
     *   - Admin       → acede a qualquer ficheiro
     *   - Gestor      → acede a ficheiros de ocorrências da sua província
     *   - Funcionário → acede apenas a ficheiros das suas próprias ocorrências
     *
     * ROTA: GET /api/occurrences/{occurrence}/attachments/{attachment}
     * ACESSO: Utilizadores autenticados (com permissão sobre a ocorrência)
     */
    public function download(
        Request $request,
        Occurrence $occurrence,
        OccurrenceAttachment $attachment
    ): StreamedResponse|JsonResponse {
        if ($attachment->occurrence_id !== $occurrence->id) {
            return response()->json(['message' => 'Ficheiro não encontrado.'], 404);
        }

        $user = $request->user();

        $canAccess = match ($user->role) {
            RoleEnum::Admin       => true,
            RoleEnum::Gestor      => $user->province_id === $occurrence->province_id,
            RoleEnum::Funcionario => $occurrence->submitted_by_user_id === $user->id,
        };

        if (!$canAccess) {
            return response()->json(['message' => 'Não tem acesso a este ficheiro.'], 403);
        }

        if (!Storage::disk($attachment->disk)->exists($attachment->path)) {
            return response()->json(['message' => 'Ficheiro não encontrado no servidor.'], 404);
        }

        return Storage::disk($attachment->disk)->download(
            $attachment->path,
            $attachment->original_name,
            ['Content-Type' => $attachment->mime_type]
        );
    }
}
