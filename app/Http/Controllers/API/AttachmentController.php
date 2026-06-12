<?php

namespace App\Http\Controllers\Api;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Occurrence;
use App\Models\OccurrenceAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AttachmentController extends Controller
{
    /**
     * Faz o download de um ficheiro anexado a uma ocorrência.
     * O acesso é restrito de acordo com o perfil do utilizador autenticado:
     *   - Admin       → acede a qualquer ficheiro
     *   - Gestor      → acede a ficheiros de ocorrências da sua província
     *   - Funcionário → acede apenas a ficheiros das suas próprias ocorrências
     *   - Observador  → acede a ficheiros de ocorrências das suas províncias/projectos atribuídos
     *
     * ROTA: GET /api/occurrences/{occurrence}/attachments/{attachment}
     * ACESSO: Utilizadores autenticados (com permissão sobre a ocorrência)
     */
    public function download(
        Request $request,
        Occurrence $occurrence,
        OccurrenceAttachment $attachment
    ): BinaryFileResponse|JsonResponse {
        if ($attachment->occurrence_id !== $occurrence->id) {
            return response()->json(['message' => 'Ficheiro não encontrado.'], 404);
        }

        $user = $request->user();

        $canAccess = match ($user->role) {
            RoleEnum::Admin       => true,
            RoleEnum::Gestor      => $occurrence->submitted_by_user_id === $user->id
                                     || $user->province_id === $occurrence->province_id
                                     || $user->provinces()->where('provinces.id', $occurrence->province_id)->exists(),
            RoleEnum::Funcionario => $occurrence->submitted_by_user_id === $user->id,
            RoleEnum::Observador  => ($user->province_id === $occurrence->province_id
                                     || $user->provinces()->where('provinces.id', $occurrence->province_id)->exists())
                                     && (!$user->projects()->exists()
                                         || $user->projects()->where('projects.id', $occurrence->project_id)->exists()),
        };

        if (!$canAccess) {
            return response()->json(['message' => 'Não tem acesso a este ficheiro.'], 403);
        }

        if (!Storage::disk($attachment->disk)->exists($attachment->path)) {
            return response()->json(['message' => 'Ficheiro não encontrado no servidor.'], 404);
        }

        $fullPath = Storage::disk($attachment->disk)->path($attachment->path);

        $inlineTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
        $disposition = in_array($attachment->mime_type, $inlineTypes) ? 'inline' : 'attachment';

        return response()->file($fullPath, [
            'Content-Type'        => $attachment->mime_type,
            'Content-Disposition' => $disposition . '; filename="' . $attachment->original_name . '"',
        ]);
    }
}
