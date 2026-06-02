<?php

namespace App\Console\Commands;

use App\Enums\OccurrenceStatusEnum;
use App\Models\Occurrence;
use App\Models\OccurrenceStatusHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * MarkOccurrencesAsUnresolved
 *
 * Executa diariamente às 01h00.
 *
 * Regra de negócio: qualquer ocorrência que permaneça num estado
 * não-terminal sem qualquer actividade (mudança de estado ou comentário)
 * durante mais de 5 dias é automaticamente marcada como 'nao_resolvida'.
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
    protected $description = 'Marca como Não Resolvida qualquer ocorrência sem actividade há mais de 5 dias';

    public function handle(): int
    {
        $cutoff = now()->subDays(5);

        $terminalStatuses = [
            OccurrenceStatusEnum::Resolvido->value,
            OccurrenceStatusEnum::NaoValidado->value,
            OccurrenceStatusEnum::NaoResolvida->value,
        ];

        // Ocorrências em estado não-terminal sem qualquer registo de actividade
        // nos últimos 5 dias (nenhuma entrada em occurrence_status_history
        // com changed_at posterior ao cutoff).
        $occurrences = Occurrence::whereNotIn('status', $terminalStatuses)
            ->whereDoesntHave('statusHistory', fn($q) => $q->where('changed_at', '>', $cutoff))
            ->get();

        if ($occurrences->isEmpty()) {
            $this->info('Nenhuma ocorrência para marcar.');
            return self::SUCCESS;
        }

        $count = 0;

        DB::transaction(function () use ($occurrences, &$count) {
            $now = now();

            foreach ($occurrences as $occurrence) {
                $oldStatus = $occurrence->status;

                $occurrence->update(['status' => OccurrenceStatusEnum::NaoResolvida]);

                OccurrenceStatusHistory::create([
                    'occurrence_id' => $occurrence->id,
                    'from_status'   => $oldStatus->value,
                    'to_status'     => OccurrenceStatusEnum::NaoResolvida->value,
                    'changed_by'    => null,
                    'comment'       => 'Ocorrência marcada automaticamente como Não Resolvida por falta de actividade durante 5 dias.',
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
