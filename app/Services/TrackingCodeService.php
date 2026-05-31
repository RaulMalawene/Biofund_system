<?php

namespace App\Services;

use App\Models\Occurrence;
use Illuminate\Support\Str;

/**
 * TrackingCodeService
 *
 * Responsável por gerar o código único de seguimento (tracking_code)
 * atribuído a cada ocorrência no momento do registo.
 *
 * Formato do código: MDR-XXXXXX-YYYY
 *   - MDR       → prefixo fixo do sistema
 *   - XXXXXX    → 6 caracteres alfanuméricos aleatórios (maiúsculas)
 *   - YYYY      → ano corrente (4 dígitos)
 *
 * Exemplo: MDR-K7P2QA-2024
 *
 * O código é público - entregue ao reclamante para acompanhar
 * a sua ocorrência sem necessidade de login.
 *
 * Garantia de unicidade: o serviço verifica na base de dados se o
 * código já existe e regenera até obter um código único.
 */
class TrackingCodeService
{
    /**
     * Gera um tracking_code único e verifica a sua disponibilidade
     * na base de dados antes de o retornar.
     *
     * @return string  Código único no formato MDR-XXXXXX-YYYY
     */
    public function generate(): string
    {
        do {
            $code = $this->buildCode();
        } while ($this->alreadyExists($code));

        return $code;
    }

    /**
     * Constrói o código no formato definido.
     * Usa Str::upper e Str::random do Laravel para gerar a parte aleatória.
     *
     * @return string
     */
    private function buildCode(): string
    {
        $random = Str::upper(Str::random(6));
        $year   = now()->year;

        return "MDR-{$random}-{$year}";
    }

    /**
     * Verifica se o código já existe na base de dados.
     *
     * @param  string $code
     * @return bool
     */
    private function alreadyExists(string $code): bool
    {
        return Occurrence::withTrashed()
                         ->where('tracking_code', $code)
                         ->exists();
    }
}