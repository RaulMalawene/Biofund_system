<?php

namespace App\Enums;

/**
 * OccurrenceStatusEnum
 *
 * Ciclo de vida de uma ocorrência:
 *
 *   por_validar → por_resolver → resolvendo → resolvido
 *              ↘ nao_validado
 *
 * Regras:
 *   - 'nao_validado' e 'resolvido' EXIGEM comentário obrigatório.
 *   - Comentários podem ser adicionados em qualquer estado (endpoint próprio).
 *   - Edição de projecto/categoria só é possível no estado 'por_resolver'.
 */
enum OccurrenceStatusEnum: string
{
    case PorValidar    = 'por_validar';
    case PorResolver   = 'por_resolver';
    case NaoValidado   = 'nao_validado';
    case Resolvendo    = 'resolvendo';
    case Resolvido     = 'resolvido';
    case NaoResolvida  = 'nao_resolvida';

    public function label(): string
    {
        return match($this) {
            self::PorValidar   => 'Por Validar',
            self::PorResolver  => 'Por Resolver',
            self::NaoValidado  => 'Não Validado',
            self::Resolvendo   => 'Resolvendo',
            self::Resolvido    => 'Resolvido',
            self::NaoResolvida => 'Não Resolvida',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PorValidar   => 'blue',
            self::PorResolver  => 'yellow',
            self::NaoValidado  => 'red',
            self::Resolvendo   => 'orange',
            self::Resolvido    => 'green',
            self::NaoResolvida => 'purple',
        };
    }

    public function requiresComment(): bool
    {
        return in_array($this, [
            self::NaoValidado,
            self::Resolvido,
        ]);
    }

    public function allowedTransitions(): array
    {
        return match($this) {
            self::PorValidar   => [self::PorResolver, self::NaoValidado],
            self::PorResolver  => [self::Resolvendo],
            self::Resolvendo   => [self::Resolvido],
            self::NaoValidado  => [],
            self::Resolvido    => [],
            // Estado terminal automático — sem transições manuais permitidas
            self::NaoResolvida => [],
        };
    }

    public function canTransitionTo(OccurrenceStatusEnum $new): bool
    {
        return in_array($new, $this->allowedTransitions());
    }
}