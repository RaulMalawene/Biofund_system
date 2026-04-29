<?php

namespace App\Enums;

/**
 * OccurrenceStatusEnum
 *
 * Define o ciclo de vida completo de uma ocorrência no sistema.
 *
 * Fluxo normal:
 *   pending → in_review → resolved → closed
 *
 * Fluxo de rejeição:
 *   pending → in_review → rejected
 *
 * Regras de negócio:
 *   - 'resolved' e 'rejected' EXIGEM comentário obrigatório de quem muda o estado.
 *   - 'closed' é aplicado automaticamente após prazo definido ou confirmação.
 *   - Não é possível voltar atrás depois de 'closed'.
 */
enum OccurrenceStatusEnum: string
{
    case Pending   = 'pending';
    case InReview  = 'in_review';
    case Resolved  = 'resolved';
    case Rejected  = 'rejected';
    case Closed    = 'closed';

    /**
     * Retorna o label legível para o frontend e relatórios.
     */
    public function label(): string
    {
        return match($this) {
            OccurrenceStatusEnum::Pending   => 'Pendente',
            OccurrenceStatusEnum::InReview  => 'Em Análise',
            OccurrenceStatusEnum::Resolved  => 'Resolvida',
            OccurrenceStatusEnum::Rejected  => 'Rejeitada',
            OccurrenceStatusEnum::Closed    => 'Encerrada',
        };
    }

    /**
     * Retorna a cor associada ao estado (útil para badges no frontend).
     */
    public function color(): string
    {
        return match($this) {
            OccurrenceStatusEnum::Pending   => 'yellow',
            OccurrenceStatusEnum::InReview  => 'blue',
            OccurrenceStatusEnum::Resolved  => 'green',
            OccurrenceStatusEnum::Rejected  => 'red',
            OccurrenceStatusEnum::Closed    => 'gray',
        };
    }

    /**
     * Indica se este estado exige comentário obrigatório ao ser aplicado.
     * Resolve e Rejeita obrigam o gestor/admin a explicar a decisão.
     */
    public function requiresComment(): bool
    {
        return in_array($this, [
            OccurrenceStatusEnum::Resolved,
            OccurrenceStatusEnum::Rejected,
        ]);
    }

    /**
     * Define as transições de estado válidas.
     * Impede que o sistema aceite mudanças de estado inválidas.
     *
     * Exemplo: não é possível ir de 'closed' para 'pending'.
     */
    public function allowedTransitions(): array
    {
        return match($this) {
            OccurrenceStatusEnum::Pending   => [self::InReview],
            OccurrenceStatusEnum::InReview  => [self::Resolved, self::Rejected],
            OccurrenceStatusEnum::Resolved  => [self::Closed],
            OccurrenceStatusEnum::Rejected  => [],   // estado terminal
            OccurrenceStatusEnum::Closed    => [],   // estado terminal
        };
    }

    /**
     * Verifica se a transição para um novo estado é válida.
     *
     * @param OccurrenceStatusEnum $new O novo estado pretendido
     */
    public function canTransitionTo(OccurrenceStatusEnum $new): bool
    {
        return in_array($new, $this->allowedTransitions());
    }
}