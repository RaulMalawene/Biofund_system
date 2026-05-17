<?php

namespace App\Enums;

/**
 * OriginEnum
 *
 * Indica a origem de uma ocorrência registada no sistema.
 *
 * - external → submetida pelo formulário público (sem login).
 *              Os dados do reclamante ficam nos campos complainant_* da ocorrência.
 *
 * - internal → submetida por um utilizador autenticado (funcionário, gestor ou admin).
 *              O ID do utilizador é guardado em submitted_by_user_id.
 *              O fluxo de tratamento é exactamente o mesmo.
 */
enum OriginEnum: string
{
    case External = 'external';
    case Internal = 'internal';

    /**
     * Retorna o label legível.
     */
    public function label(): string
    {
        return match($this) {
            OriginEnum::External => 'Externo',
            OriginEnum::Internal => 'Interno',
        };
    }
}