<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * AuditService
 *
 * Responsável por registar todas as acções relevantes no sistema
 * na tabela `audit_logs`.
 *
 * É chamado manualmente nos controllers e services sempre que
 * ocorre uma operação que deve ser auditada:
 *   - Criação, edição e remoção de ocorrências
 *   - Mudanças de estado (validação/rejeição)
 *   - Criação e edição de utilizadores
 *   - Login e logout
 *   - Alterações de parametrização
 *
 * O snapshot dos dados antes/depois (old_values/new_values)
 * permite ao administrador ver exactamente o que foi alterado.
 */
class AuditService
{
    /**
     * Regista uma acção de criação de uma entidade.
     *
     * @param  Model  $model    A entidade criada
     * @param  array  $data     Os dados que foram inseridos
     */
    public function logCreated(Model $model, array $data = []): void
    {
        $this->log($model, 'created', null, $data ?: $model->toArray());
    }

    /**
     * Regista uma acção de actualização de uma entidade.
     *
     * @param  Model  $model      A entidade actualizada
     * @param  array  $oldValues  Estado anterior dos dados
     * @param  array  $newValues  Novo estado dos dados
     */
    public function logUpdated(Model $model, array $oldValues, array $newValues): void
    {
        $this->log($model, 'updated', $oldValues, $newValues);
    }

    /**
     * Regista uma acção de remoção (soft delete) de uma entidade.
     *
     * @param  Model  $model  A entidade removida
     */
    public function logDeleted(Model $model): void
    {
        $this->log($model, 'deleted', $model->toArray(), null);
    }

    /**
     * Regista uma mudança de estado de uma ocorrência.
     *
     * @param  Model   $model       A ocorrência afectada
     * @param  string  $fromStatus  Estado anterior
     * @param  string  $toStatus    Novo estado
     * @param  string|null $comment Comentário da mudança
     */
    public function logStatusChanged(
        Model $model,
        string $fromStatus,
        string $toStatus,
        ?string $comment = null
    ): void {
        $this->log($model, 'status_changed', [
            'status'  => $fromStatus,
        ], [
            'status'  => $toStatus,
            'comment' => $comment,
        ]);
    }

    /**
     * Regista um evento de login.
     * O modelo passado deve ser o utilizador que fez login.
     *
     * @param  Model  $user  O utilizador autenticado
     */
    public function logLogin(Model $user): void
    {
        $this->log($user, 'login', null, ['email' => $user->email]);
    }

    /**
     * Regista um evento de logout.
     *
     * @param  Model  $user  O utilizador que fez logout
     */
    public function logLogout(Model $user): void
    {
        $this->log($user, 'logout', null, ['email' => $user->email]);
    }

    /**
     * Método base que cria o registo de auditoria.
     * Captura automaticamente o utilizador autenticado, o IP e o User-Agent.
     *
     * @param  Model        $model      Entidade afectada
     * @param  string       $event      Tipo de evento
     * @param  array|null   $oldValues  Estado anterior (null em criações)
     * @param  array|null   $newValues  Novo estado (null em eliminações)
     */
    private function log(
        Model $model,
        string $event,
        ?array $oldValues,
        ?array $newValues
    ): void {
        // Remove campos sensíveis dos snapshots
        $sensitive = ['password', 'remember_token'];

        AuditLog::create([
            'user_id'        => Auth::id(),  // null se a acção for do sistema
            'auditable_type' => get_class($model),
            'auditable_id'   => $model->getKey(),
            'event'          => $event,
            'old_values'     => $oldValues
                                    ? array_diff_key($oldValues, array_flip($sensitive))
                                    : null,
            'new_values'     => $newValues
                                    ? array_diff_key($newValues, array_flip($sensitive))
                                    : null,
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::userAgent(),
            'occurred_at'    => now(),
        ]);
    }
}