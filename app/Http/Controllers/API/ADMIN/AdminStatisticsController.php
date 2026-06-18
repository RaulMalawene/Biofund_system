<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Occurrence;
use App\Models\User;
use App\Models\NotificationLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * AdminStatisticsController
 *
 * Fornece os dados estatísticos para o painel do administrador.
 * Todos os endpoints retornam dados agregados para exibição
 * em gráficos, cards e tabelas no frontend.
 */
class AdminStatisticsController extends Controller
{
    /**
     * Retorna as estatísticas gerais do painel principal (dashboard).
     * Inclui totais por estado, alertas, SLA e actividade recente.
     *
     * ROTA: GET /api/admin/statistics/dashboard
     * ACESSO: Autenticado (admin, gestor)
     *
     * Resposta (200):
     *   {
     *     "totals": { "all": 120, "pending": 30, "in_review": 45, ... },
     *     "overdue": 8,
     *     "by_alert_level": { "normal": 100, "urgent": 15, "gbv": 5 },
     *     "by_province": [...],
     *     "recent": [...]
     *   }
     */
    public function dashboard(Request $request): JsonResponse
    {
        $user            = $request->user();
        $filterStatus    = $request->input('status');
        $filterAlertType = $request->input('alert_type');
        $filterYear      = $request->input('year')        ? (int) $request->input('year')        : null;
        $filterProvince  = $request->input('province_id') ? (int) $request->input('province_id') : null;
        $filterProject   = $request->input('project_id')  ? (int) $request->input('project_id')  : null;
        $filterCategory  = $request->input('category_id') ? (int) $request->input('category_id') : null;
        $filterGender    = $request->input('gender');
        $filterAgeRange  = $request->input('age_range');

        // Filtered requests bypass cache (params vary)
        if ($filterStatus || $filterAlertType || $filterYear || $filterProvince || $filterProject || $filterCategory || $filterGender || $filterAgeRange) {
            return response()->json(
                $this->buildDashboardData($user, $filterStatus, $filterAlertType, $filterYear, $filterProvince, $filterProject, $filterCategory, $filterGender, $filterAgeRange),
                200
            );
        }

        $cacheKey = match (true) {
            $user->isGestor()     => "dashboard.gestor.{$user->id}",
            $user->isObservador() => "dashboard.observador.{$user->id}",
            default               => 'dashboard.admin',
        };
        return response()->json(
            Cache::remember($cacheKey, 120, fn() => $this->buildDashboardData($user)),
            200
        );
    }

    private function buildDashboardData(
        User    $user,
        ?string $filterStatus    = null,
        ?string $filterAlertType = null,
        ?int    $filterYear      = null,
        ?int    $filterProvince  = null,
        ?int    $filterProject   = null,
        ?int    $filterCategory  = null,
        ?string $filterGender    = null,
        ?string $filterAgeRange  = null,
    ): array {
        $scopedProvinceIds = [];
        $scopedProjectIds  = [];
        if ($user->isGestor() || $user->isObservador()) {
            $user->loadMissing(['provinces', 'projects']);
            $scopedProvinceIds = collect($user->province_id ? [$user->province_id] : [])
                ->merge($user->provinces->pluck('id'))
                ->unique()->values()->all();
            $scopedProjectIds = $user->projects->pluck('id')->all();
        }

        $baseQuery = fn() => Occurrence::when(
            $user->isGestor() || $user->isObservador(),
            fn($q) => $q->whereIn('province_id', $scopedProvinceIds)
                        ->whereIn('project_id', $scopedProjectIds)
        )
        ->when($filterStatus,    fn($q) => $q->where('status',      $filterStatus))
        ->when($filterAlertType, fn($q) => $q->where('alert_type',  $filterAlertType))
        ->when($filterYear,      fn($q) => $q->whereYear('created_at', $filterYear))
        ->when($filterProvince,  fn($q) => $q->where('province_id', $filterProvince))
        ->when($filterProject,   fn($q) => $q->where('project_id',  $filterProject))
        ->when($filterCategory,  fn($q) => $q->where('category_id', $filterCategory))
        ->when($filterGender,    fn($q) => $q->where('complainant_gender', $filterGender))
        ->when($filterAgeRange,  fn($q) => $q->where('complainant_age',    $filterAgeRange));

        $totals   = $baseQuery()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        $totalAll = array_sum($totals);

        $overdue = $baseQuery()->overdue()->count();

        $byTypeCode = $baseQuery()
            ->join('occurrence_types', 'occurrences.occurrence_type_id', '=', 'occurrence_types.id')
            ->select('occurrence_types.code', DB::raw('count(*) as total'))
            ->whereIn('occurrence_types.code', ['REC', 'ELO', 'SUG'])
            ->groupBy('occurrence_types.code')
            ->pluck('total', 'code')
            ->toArray();

        $byAlertLevel = $baseQuery()
            ->join('occurrence_types', 'occurrences.occurrence_type_id', '=', 'occurrence_types.id')
            ->select('occurrence_types.alert_level', DB::raw('count(*) as total'))
            ->groupBy('occurrence_types.alert_level')
            ->pluck('total', 'alert_level')
            ->toArray();

        $byProvince = Occurrence::join('provinces', 'occurrences.province_id', '=', 'provinces.id')
            ->select('provinces.name', DB::raw('count(*) as total'))
            ->when(
                $user->isGestor() || $user->isObservador(),
                fn($q) => $q->whereIn('occurrences.province_id', $scopedProvinceIds)->limit(5)
            )
            ->when($filterStatus,    fn($q) => $q->where('occurrences.status',      $filterStatus))
            ->when($filterAlertType, fn($q) => $q->where('occurrences.alert_type',  $filterAlertType))
            ->when($filterYear,      fn($q) => $q->whereYear('occurrences.created_at', $filterYear))
            ->when($filterProvince,  fn($q) => $q->where('occurrences.province_id', $filterProvince))
            ->when($filterProject,   fn($q) => $q->where('occurrences.project_id',  $filterProject))
            ->when($filterCategory,  fn($q) => $q->where('occurrences.category_id', $filterCategory))
            ->when($filterGender,    fn($q) => $q->where('occurrences.complainant_gender', $filterGender))
            ->when($filterAgeRange,  fn($q) => $q->where('occurrences.complainant_age',    $filterAgeRange))
            ->groupBy('provinces.name')
            ->orderByDesc('total')
            ->get();

        $byCategory = $baseQuery()
            ->join('categories', 'occurrences.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $byMonthRaw = $baseQuery()
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total'),
                DB::raw("sum(case when status = 'resolvido' then 1 else 0 end) as resolved")
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get();

        $byGender = $baseQuery()
            ->select(
                DB::raw("CASE
                    WHEN complainant_gender = 'masculino' THEN 'Masculino'
                    WHEN complainant_gender = 'feminino'  THEN 'Feminino'
                    ELSE 'Não Identificado'
                END as gender_label"),
                DB::raw('count(*) as total')
            )
            ->groupBy('gender_label')
            ->pluck('total', 'gender_label')
            ->toArray();

        $ageOrder   = ['Menos de 18', '18 - 25', '26 - 35', '36 - 45', '46 - 55', '56 - 65', 'Mais de 65'];
        $byAgeRaw   = $baseQuery()
            ->select('complainant_age', DB::raw('count(*) as total'))
            ->whereNotNull('complainant_age')
            ->where('complainant_age', '!=', '')
            ->groupBy('complainant_age')
            ->pluck('total', 'complainant_age')
            ->toArray();
        $byAgeRange = collect($ageOrder)->map(fn($age) => [
            'label' => $age,
            'total' => (int) ($byAgeRaw[$age] ?? 0),
        ])->all();

        // Canal pelo qual a ocorrência chegou ao sistema. Submissões externas
        // (formulário público) não preenchem submission_channel, por isso
        // entram no agrupamento "Portal Online".
        $byChannel = $baseQuery()
            ->select(
                DB::raw("CASE
                    WHEN submission_channel = 'green_line'        THEN 'Linha Verde'
                    WHEN submission_channel = 'email'              THEN 'Email'
                    WHEN submission_channel = 'phone'              THEN 'Telefone'
                    WHEN submission_channel = 'community_meeting'  THEN 'Reunião Comunitária'
                    ELSE 'Portal Online'
                END as label"),
                DB::raw('count(*) as total')
            )
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $byProject = $baseQuery()
            ->join('projects', 'occurrences.project_id', '=', 'projects.id')
            ->select('projects.name', DB::raw('count(*) as total'))
            ->groupBy('projects.name')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $recent = $baseQuery()
            ->with([
                'project:id,name',
                'province:id,name',
                'category:id,name',
                'occurrenceType:id,name,alert_level',
            ])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get([
                'id', 'tracking_code', 'complainant_name', 'subject',
                'status', 'created_at',
                'project_id', 'province_id', 'category_id', 'occurrence_type_id',
            ]);

        // Converte Collections para plain arrays antes de guardar em cache.
        // Eloquent Collections serializadas via PHP serialize/unserialize podem
        // produzir JSON objects ({}) em vez de arrays ([]) ao ser json_encode'd,
        // quebrando o frontend que espera sempre um array.
        return [
            'totals'            => array_merge(['all' => $totalAll], $totals),
            'overdue'           => $overdue,
            'reclamacoes'       => (int) ($byTypeCode['REC'] ?? 0),
            'elogios'           => (int) ($byTypeCode['ELO'] ?? 0),
            'sugestoes'         => (int) ($byTypeCode['SUG'] ?? 0),
            'by_alert_level'    => $byAlertLevel,
            'by_category'       => $byCategory->values()->toArray(),
            'by_province'       => $byProvince->values()->toArray(),
            'by_gender'         => $byGender,
            'by_age_range'      => $byAgeRange,
            'by_channel'        => $byChannel->values()->toArray(),
            'by_project'        => $byProject->values()->toArray(),
            'by_month'          => $byMonthRaw->map(fn($r) => [
                'label' => sprintf('%04d-%02d', $r->year, $r->month),
                'total' => (int) $r->total,
            ])->values()->all(),
            'by_month_resolved' => $byMonthRaw->map(fn($r) => [
                'label' => sprintf('%04d-%02d', $r->year, $r->month),
                'total' => (int) $r->resolved,
            ])->values()->all(),
            'recent'            => $recent->values()->toArray(),
        ];
    }

    /**
     * Gera um relatório periódico (trimestral ou semestral) de ocorrências.
     *
     * ROTA: GET /api/admin/statistics/report/periodic
     * ACESSO: Autenticado (admin, gestor)
     *
     * Query params obrigatórios:
     *   ?period=Q1|Q2|Q3|Q4|S1|S2   — trimestre ou semestre
     *   ?year=2025                   — ano (default: ano actual)
     *
     * Query params opcionais (filtros adicionais):
     *   ?project_id=1
     *   ?province_id=2
     *   ?category_id=3
     *
     * Resposta:
     *   {
     *     "meta": { "period_label", "date_from", "date_to", "year", "period", "generated_at" },
     *     "summary": { "total", "resolved", "unresolved", "overdue", "resolution_rate", "by_status", "by_alert_level" },
     *     "by_category":  [...],
     *     "by_province":  [...],
     *     "by_project":   [...],
     *     "by_month":     [...],
     *     "by_type":      [...],
     *     "by_gender":    {...},
     *     "by_age_range": [...],
     *     "occurrences":  [...]
     *   }
     */
    public function periodicReport(Request $request): JsonResponse
    {
        $request->validate([
            'period'      => ['required', 'string', 'regex:/^(Q[1-4]|S[1-2]|M(0[1-9]|1[0-2]))$/'],
            'year'        => ['nullable', 'integer', 'min:2020', 'max:2099'],
            'project_id'  => ['nullable', 'integer', 'exists:projects,id'],
            'province_id' => ['nullable', 'integer', 'exists:provinces,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        $period = strtoupper($request->input('period'));
        $year   = (int) ($request->input('year', now()->year));

        // Calcular datas de início e fim do período
        [$dateFrom, $dateTo, $periodLabel] = $this->resolvePeriodDates($period, $year);

        $user = $request->user();

        // Âmbito geográfico para gestores
        $scopedProvinceIds = [];
        $scopedProjectIds  = [];
        if ($user->isGestor()) {
            $user->loadMissing(['provinces', 'projects']);
            $scopedProvinceIds = collect($user->province_id ? [$user->province_id] : [])
                ->merge($user->provinces->pluck('id'))
                ->unique()->values()->all();
            $scopedProjectIds = $user->projects->pluck('id')->all();
        }

        // Query base com filtros de período + âmbito + filtros opcionais.
        // Todas as colunas sem JOIN usam prefixo 'occurrences.' para evitar
        // ambiguidade quando queries derivadas adicionam JOINs.
        $base = fn() => Occurrence::whereBetween('occurrences.created_at', [$dateFrom, $dateTo])
            ->when(
                $user->isGestor(),
                fn($q) => $q->whereIn('occurrences.province_id', $scopedProvinceIds)
                             ->whereIn('occurrences.project_id', $scopedProjectIds)
            )
            ->when($request->project_id,  fn($q) => $q->where('occurrences.project_id',  $request->project_id))
            ->when($request->province_id, fn($q) => $q->where('occurrences.province_id', $request->province_id))
            ->when($request->category_id, fn($q) => $q->where('occurrences.category_id', $request->category_id));

        // ── Totais e resumo ─────────────────────────────────────
        $total    = $base()->count();
        $resolved = $base()->where('occurrences.status', 'resolvido')->count();
        $overdue  = $base()->overdue()->count();

        $byStatus = $base()
            ->select('occurrences.status', DB::raw('count(*) as total'))
            ->groupBy('occurrences.status')
            ->pluck('total', 'status')
            ->toArray();

        $byAlertLevel = $base()
            ->join('occurrence_types', 'occurrences.occurrence_type_id', '=', 'occurrence_types.id')
            ->select('occurrence_types.alert_level', DB::raw('count(*) as total'))
            ->groupBy('occurrence_types.alert_level')
            ->pluck('total', 'alert_level')
            ->toArray();

        // ── Breakdown por dimensão ───────────────────────────────
        $byCategory = $base()
            ->join('categories', 'occurrences.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get()->values()->toArray();

        $byProvince = $base()
            ->join('provinces', 'occurrences.province_id', '=', 'provinces.id')
            ->select('provinces.name', DB::raw('count(*) as total'))
            ->groupBy('provinces.name')
            ->orderByDesc('total')
            ->get()->values()->toArray();

        $byProject = $base()
            ->join('projects', 'occurrences.project_id', '=', 'projects.id')
            ->select('projects.name', DB::raw('count(*) as total'))
            ->groupBy('projects.name')
            ->orderByDesc('total')
            ->get()->values()->toArray();

        $byType = $base()
            ->join('occurrence_types', 'occurrences.occurrence_type_id', '=', 'occurrence_types.id')
            ->select('occurrence_types.name', 'occurrence_types.alert_level', DB::raw('count(*) as total'))
            ->groupBy('occurrence_types.name', 'occurrence_types.alert_level')
            ->orderByDesc('total')
            ->get()->values()->toArray();

        // Evolução mensal dentro do período
        $byMonth = $base()
            ->select(
                DB::raw('YEAR(occurrences.created_at) as year'),
                DB::raw('MONTH(occurrences.created_at) as month'),
                DB::raw('count(*) as total'),
                DB::raw("sum(case when occurrences.status = 'resolvido' then 1 else 0 end) as resolved")
            )
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get()
            ->map(fn($r) => [
                'label'    => sprintf('%04d-%02d', $r->year, $r->month),
                'total'    => (int) $r->total,
                'resolved' => (int) $r->resolved,
            ])->values()->toArray();

        // Género
        $byGender = $base()
            ->select(
                DB::raw("CASE
                    WHEN occurrences.complainant_gender = 'masculino' THEN 'Masculino'
                    WHEN occurrences.complainant_gender = 'feminino'  THEN 'Feminino'
                    ELSE 'Não Identificado'
                END as gender_label"),
                DB::raw('count(*) as total')
            )
            ->groupBy('gender_label')
            ->pluck('total', 'gender_label')
            ->toArray();

        // Faixa etária
        $ageOrder   = ['Menos de 18', '18 - 25', '26 - 35', '36 - 45', '46 - 55', '56 - 65', 'Mais de 65'];
        $byAgeRaw   = $base()
            ->select('occurrences.complainant_age', DB::raw('count(*) as total'))
            ->whereNotNull('occurrences.complainant_age')
            ->where('occurrences.complainant_age', '!=', '')
            ->groupBy('occurrences.complainant_age')
            ->pluck('total', 'complainant_age')
            ->toArray();
        $byAgeRange = collect($ageOrder)->map(fn($age) => [
            'label' => $age,
            'total' => (int) ($byAgeRaw[$age] ?? 0),
        ])->all();

        // ── Lista de ocorrências ──────────────────────────────────
        $occurrences = $base()
            ->with([
                'project:id,name',
                'province:id,name',
                'category:id,name',
                'occurrenceType:id,name,alert_level',
                'assignedTo:id,name',
            ])
            ->orderBy('occurrences.created_at', 'desc')
            ->get()
            ->map(fn($o) => [
                'tracking_code' => $o->tracking_code,
                'subject'       => $o->subject ?? '-',
                'status'        => $o->status->label(),
                'project'       => $o->project?->name  ?? '-',
                'province'      => $o->province?->name ?? '-',
                'category'      => $o->category?->name ?? '-',
                'type'          => $o->occurrenceType?->name ?? '-',
                'alert_level'   => $o->occurrenceType?->alert_level?->label() ?? '-',
                'assigned_to'   => $o->assignedTo?->name ?? '-',
                'submitted_at'  => $o->created_at->format('d/m/Y'),
                'due_date'      => $o->due_date?->format('d/m/Y') ?? '-',
                'gender'        => $o->complainant_gender ?? '-',
            ])->values()->toArray();

        return response()->json([
            'meta' => [
                'period'       => $period,
                'period_label' => $periodLabel,
                'year'         => $year,
                'date_from'    => $dateFrom->format('d/m/Y'),
                'date_to'      => $dateTo->format('d/m/Y'),
                'generated_at' => now()->format('d/m/Y H:i'),
                'generated_by' => $user->name,
            ],
            'summary' => [
                'total'           => $total,
                'resolved'        => $resolved,
                'unresolved'      => $total - $resolved,
                'overdue'         => $overdue,
                'resolution_rate' => $total > 0 ? round(($resolved / $total) * 100, 1) : 0,
                'by_status'       => $byStatus,
                'by_alert_level'  => $byAlertLevel,
            ],
            'by_category'  => $byCategory,
            'by_province'  => $byProvince,
            'by_project'   => $byProject,
            'by_type'      => $byType,
            'by_month'     => $byMonth,
            'by_gender'    => $byGender,
            'by_age_range' => $byAgeRange,
            'occurrences'  => $occurrences,
        ], 200);
    }

    /**
     * Resolve as datas de início e fim para um dado período e ano.
     * Suporta meses (M01–M12), trimestres (Q1–Q4) e semestres (S1–S2).
     * Retorna [\Carbon\Carbon $from, \Carbon\Carbon $to, string $label].
     */
    private function resolvePeriodDates(string $period, int $year): array
    {
        // Período mensal: M01, M02, ... M12
        if (preg_match('/^M(\d{2})$/', $period, $m)) {
            $month = (int) $m[1];
            $nomesMeses = [
                1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
            ];

            $from  = \Carbon\Carbon::create($year, $month, 1)->startOfDay();
            $to    = $from->copy()->endOfMonth()->endOfDay();
            $label = "{$nomesMeses[$month]} {$year}";

            return [$from, $to, $label];
        }

        $ranges = [
            'Q1' => [1,  3,  "1.º Trimestre {$year}"],
            'Q2' => [4,  6,  "2.º Trimestre {$year}"],
            'Q3' => [7,  9,  "3.º Trimestre {$year}"],
            'Q4' => [10, 12, "4.º Trimestre {$year}"],
            'S1' => [1,  6,  "1.º Semestre {$year}"],
            'S2' => [7,  12, "2.º Semestre {$year}"],
        ];

        [$startMonth, $endMonth, $label] = $ranges[$period];

        $from = \Carbon\Carbon::create($year, $startMonth, 1)->startOfDay();
        $to   = \Carbon\Carbon::create($year, $endMonth, 1)->endOfMonth()->endOfDay();

        return [$from, $to, $label];
    }

    /**
     * Gera um relatório filtrado de ocorrências para exportação.
     *
     * ROTA: GET /api/admin/statistics/report
     * ACESSO: Autenticado (admin, gestor)
     *
     * Query params:
     *   ?date_from=2024-01-01
     *   ?date_to=2024-12-31
     *   ?status=resolved
     *   ?project_id=1
     *   ?province_id=2
     *   ?category_id=3
     *   ?occurrence_type_id=1
     *   ?format=json|summary  (summary retorna só totais, json retorna lista completa)
     */
    public function report(Request $request): JsonResponse
    {
        $request->validate([
            'date_from'          => ['nullable', 'date'],
            'date_to'            => ['nullable', 'date', 'after_or_equal:date_from'],
            'status'             => ['nullable', 'string'],
            'project_id'         => ['nullable', 'integer', 'exists:projects,id'],
            'province_id'        => ['nullable', 'integer', 'exists:provinces,id'],
            'category_id'        => ['nullable', 'integer', 'exists:categories,id'],
            'occurrence_type_id' => ['nullable', 'integer', 'exists:occurrence_types,id'],
        ]);

        $user  = $request->user();
        $query = Occurrence::query();

        // Restrição de área para gestores (províncias + projectos)
        if ($user->isGestor()) {
            $user->loadMissing(['provinces', 'projects']);
            $provinceIds = collect($user->province_id ? [$user->province_id] : [])
                ->merge($user->provinces->pluck('id'))
                ->unique()->values()->all();
            $projectIds = $user->projects->pluck('id')->all();
            $query->where(fn($q) =>
                $q->whereIn('province_id', $provinceIds)
                  ->orWhereIn('project_id', $projectIds)
            );
        }

        // Aplicar filtros
        $query->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from));
        $query->when($request->date_to,   fn($q) => $q->whereDate('created_at', '<=', $request->date_to));
        $query->when($request->status,    fn($q) => $q->where('status', $request->status));
        $query->when($request->project_id,         fn($q) => $q->where('project_id', $request->project_id));
        $query->when($request->province_id,        fn($q) => $q->where('province_id', $request->province_id));
        $query->when($request->category_id,        fn($q) => $q->where('category_id', $request->category_id));
        $query->when($request->occurrence_type_id, fn($q) => $q->where('occurrence_type_id', $request->occurrence_type_id));

        // Resumo via SQL - evita carregar todos os registos só para agregar
        $summary = [
            'total'      => (clone $query)->count(),
            'by_status'  => (clone $query)
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status'),
            'by_project' => (clone $query)
                ->join('projects', 'occurrences.project_id', '=', 'projects.id')
                ->select('projects.name', DB::raw('count(*) as total'))
                ->groupBy('projects.name')
                ->pluck('total', 'name'),
            'overdue'    => (clone $query)->overdue()->count(),
        ];

        $occurrences = $query->with([
            'project:id,name', 'province:id,name',
            'category:id,name', 'occurrenceType:id,name,alert_level',
            'assignedTo:id,name',
        ])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'summary'     => $summary,
            'occurrences' => $occurrences->map(fn($o) => [
                'tracking_code' => $o->tracking_code,
                'subject'       => $o->subject,
                'status'        => $o->status->label(),
                'project'       => $o->project?->name ?? '-',
                'province'      => $o->province?->name ?? '-',
                'category'      => $o->category?->name ?? '-',
                'type'          => $o->occurrenceType?->name ?? '-',
                'alert_level'   => $o->occurrenceType?->alert_level?->label() ?? '-',
                'assigned_to'   => $o->assignedTo?->name ?? '-',
                'submitted_at'  => $o->created_at->format('d/m/Y'),
                'due_date'      => $o->due_date?->format('d/m/Y') ?? '-',
            ]),
        ], 200);
    }
}