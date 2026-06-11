<?php

namespace App\Enums;

/**
 * AlertLevelEnum
 *
 * Define os níveis de alerta de uma ocorrência.
 * Usado na tabela `occurrence_types`, coluna `alert_level`.
 *
 * O nível de alerta determina:
 *   1. A prioridade visual no painel do gestor/admin.
 *   2. Quais utilizadores recebem notificação imediata por email.
 *   3. O prazo de resolução (SLA).
 *
 * Regras de notificação:
 *   - normal  → notifica apenas o gestor responsável
 *   - urgent  → notifica gestores com receives_urgent_alerts = true
 *   - gbv     → notifica utilizadores com receives_gbv_alerts = true (prioridade máxima)
 */
enum AlertLevelEnum: string
{
    case Normal = 'normal';
    case Urgent = 'urgent';
    case Gbv    = 'gbv';

    /**
     * Retorna o label legível.
     */
    public function label(): string
    {
        return match($this) {
            AlertLevelEnum::Normal => 'Normal',
            AlertLevelEnum::Urgent => 'Urgente',
            AlertLevelEnum::Gbv    => 'GBV - Violência Baseada no Género',
        };
    }

    /**
     * Retorna a cor do badge (útil para o frontend).
     */
    public function color(): string
    {
        return match($this) {
            AlertLevelEnum::Normal => 'blue',
            AlertLevelEnum::Urgent => 'orange',
            AlertLevelEnum::Gbv    => 'red',
        };
    }

    /**
     * Retorna o campo da tabela `users` que controla
     * quem recebe notificação para este nível de alerta.
     *
     * Usado no NotificationService para filtrar destinatários.
     */
    public function userAlertColumn(): ?string
    {
        return match($this) {
            AlertLevelEnum::Normal => null,                    // só gestor da área
            AlertLevelEnum::Urgent => 'receives_urgent_alerts',
            AlertLevelEnum::Gbv    => 'receives_gbv_alerts',
        };
    }

    /**
     * Número máximo de dias úteis sem actualização de estado antes da
     * ocorrência ser marcada automaticamente como 'Não Resolvida'.
     *
     * Ocorrências urgentes ou de GBV têm um prazo mais curto (3 dias úteis)
     * por serem de prioridade máxima; as restantes têm 5 dias úteis.
     *
     * Usado pelo comando occurrences:mark-unresolved e pelas notificações
     * enviadas aos gestores responsáveis.
     */
    public function statusUpdateBusinessDaysLimit(): int
    {
        return match($this) {
            AlertLevelEnum::Normal => 5,
            AlertLevelEnum::Urgent, AlertLevelEnum::Gbv => 3,
        };
    }
}