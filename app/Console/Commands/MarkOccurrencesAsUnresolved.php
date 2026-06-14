<?php

namespace App\Console\Commands;

use App\Enums\OccurrenceStatusEnum;
use App\Models\Occurrence;
use App\Models\OccurrenceStatusHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * MarkOccurrencesAsUnresolved
 *
 * Executa diariamente às 01h00.
 *
 * Regra de negócio: qualquer ocorrência que permaneça num estado
 * não-terminal sem qualquer actividade (mudança de estado ou comentário)
 * durante mais dias ÚTEIS (segunda a sexta-feira, excluindo sábados e
 * domingos) do que o limite aplicável ao seu nível de alerta é
 * automaticamente marcada como 'nao_resolvida'.
 *
 * Limite por ocorrência (Occurrence::statusUpdateBusinessDaysLimit):
 *   - ocorrências externas (formulário público)         → 5 dias úteis
 *   - ocorrências internas com alert_type = urgent      → 3 dias úteis
 *   - ocorrências internas com os restantes alert_type  → 5 dias úteis
 *
 * A "actividade" é medida pela entrada mais recente na tabela
 * occurrence_status_history. O addComment() também cria uma entrada
 * nessa tabela, pelo que um comentário reinicia o contador.
 *
 * O campo changed_by fica null para sinalizar que foi o sistema.
 */
class MarkOccurrencesAsUnresolved extends Command
{
    protected $signature   = 'occurrences:mark-unresolved';
    protected $description = 'Marca como Não Resolvida qualquer ocorrência sem actividade há mais de 5 dias úteis (3 para ocorrências internas urgentes)';

    /**
     * Maior limite de dias úteis entre todos os níveis de alerta.
     * Usado apenas como corte seguro no pré-filtro SQL.
     */
    private const MAX_BUSINESS_DAYS_LIMIT = 5;

    public function handle(): int
    {
        $now = now();

        $terminalStatuses = [
            OccurrenceStatusEnum::Resolvido->value,
            OccurrenceStatusEnum::NaoValidado->value,
            OccurrenceStatusEnum::NaoResolvida->value,
        ];

        // Pré-filtro em SQL: como o número de dias úteis nunca excede o
        // número de dias de calendário, uma ocorrência só pode ultrapassar
        // o seu limite (no máximo 5 dias úteis) sem actividade se também já
        // tiver mais de 5 dias de calendário sem actividade. Isto evita
        // carregar ocorrências que nunca poderiam ser marcadas.
        $occurrences = Occurrence::whereNotIn('status', $terminalStatuses)
            ->whereDoesntHave('statusHistory', fn($q) => $q->where('changed_at', '>', $now->copy()->subDays(self::MAX_BUSINESS_DAYS_LIMIT)))
            ->withMax('statusHistory', 'changed_at')
            ->get()
            ->filter(function (Occurrence $occurrence) use ($now) {
                $lastActivity = Carbon::parse($occurrence->status_history_max_changed_at);

                return $lastActivity->diffInWeekdays($now) > $occurrence->statusUpdateBusinessDaysLimit();
            });

        if ($occurrences->isEmpty()) {
            $this->info('Nenhuma ocorrência para marcar.');
            return self::SUCCESS;
        }

        $count = 0;

        DB::transaction(function () use ($occurrences, $now, &$count) {
            foreach ($occurrences as $occurrence) {
                $oldStatus = $occurrence->status;
                $limit     = $occurrence->statusUpdateBusinessDaysLimit();

                $occurrence->update(['status' => OccurrenceStatusEnum::NaoResolvida]);

                OccurrenceStatusHistory::create([
                    'occurrence_id' => $occurrence->id,
                    'from_status'   => $oldStatus->value,
                    'to_status'     => OccurrenceStatusEnum::NaoResolvida->value,
                    'changed_by'    => null,
                    'comment'       => "Ocorrência marcada automaticamente como Não Resolvida por exceder {$limit} dias úteis sem actualização de estado.",
                    'internal_note' => null,
                    'changed_at'    => $now,
                ]);

                $count++;
            }
        });

        Cache::forget('dashboard.admin');

        $this->info("{$count} ocorrência(s) marcada(s) como Não Resolvida.");
        return self::SUCCESS;
    }
}
