<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Verifica diariamente às 01h00 se há ocorrências sem actividade há mais de 5 dias.
// Quando encontradas, marca-as automaticamente como 'nao_resolvida' e regista no histórico.
Schedule::command('occurrences:mark-unresolved')->dailyAt('01:00');
