<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * OccurrenceAttachment
 *
 * Representa um ficheiro (imagem, documento, etc.) anexado
 * a uma ocorrência no momento do registo ou posteriormente.
 *
 * Os ficheiros são guardados no disco configurado em FILESYSTEM_DISK
 * (local durante o desenvolvimento, s3 em produção).
 *
 * O campo `uploaded_by` é null quando o ficheiro foi enviado
 * por um utilizador externo (formulário público sem login).
 *
 * @property int         $id
 * @property int         $occurrence_id
 * @property int|null    $uploaded_by
 * @property string      $original_name
 * @property string      $stored_name
 * @property string      $disk
 * @property string      $path
 * @property string      $mime_type
 * @property int         $size_bytes
 */
class OccurrenceAttachment extends Model
{
    protected $fillable = [
        'occurrence_id',
        'uploaded_by',
        'original_name',
        'stored_name',
        'disk',
        'path',
        'mime_type',
        'size_bytes',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Ocorrência à qual este anexo pertence.
     */
    public function occurrence(): BelongsTo
    {
        return $this->belongsTo(Occurrence::class);
    }

    /**
     * Utilizador que fez o upload (null = utilizador externo).
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // ─── Helpers ────────────────────────────────────────────────

    /**
     * Retorna a URL autenticada para download do ficheiro.
     */
    public function getUrl(): string
    {
        return route('occurrences.attachments.download', [
            'occurrence' => $this->occurrence_id,
            'attachment' => $this->id,
        ]);
    }

    /**
     * Retorna o tamanho do ficheiro em formato legível (KB, MB).
     */
    public function getFormattedSize(): string
    {
        $bytes = $this->size_bytes;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        return round($bytes / 1024, 2) . ' KB';
    }

    /**
     * Verifica se o ficheiro é uma imagem.
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }
}