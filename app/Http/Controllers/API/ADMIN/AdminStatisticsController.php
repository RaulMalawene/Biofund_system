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

        // Filtered requests bypass cache (params vary)
        if ($filterStatus || $filterAlertType || $filterYear || $filterProvince || $filterProject) {
            return response()->json(
                $this->buildDashboardData($user, $filterStatus, $filterAlertType, $filterYear, $filterProvince, $filterProject),
                200
            );
        }

        $cacheKey = $user->isGestor() ? "dashboard.gestor.{$user->id}" : 'dashboard.admin';
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
    ): array {
        $gestorProvinceIds = [];
        $gestorProjectIds  = [];
        if ($user->isGestor()) {
            $user->loadMissing(['provinces', 'projects']);
            $gestorProvinceIds = collect($user->province_id ? [$user->province_id] : [])
                ->merge($user->provinces->pluck('id'))
                ->unique()->values()->all();
            $gestorProjectIds = $user->projects->pluck('id')->all();
        }

        $baseQuery = fn() => Occurrence::when(
            $user->isGestor(),
            fn($q) => $q->where(fn($inner) =>
                $inner->whereIn('province_id', $gestorProvinceIds)
                      ->orWhereIn('project_id', $gestorProjectIds)
            )
        )
        ->when($filterStatus,    fn($q) => $q->where('status',      $filterStatus))
        ->when($filterAlertType, fn($q) => $q->where('alert_type',  $filterAlertType))
        ->when($filterYear,      fn($q) => $q->whereYear('created_at', $filterYear))
        ->when($filterProvince,  fn($q) => $q->where('province_id', $filterProvince))
        ->when($filterProject,   fn($q) => $q->where('project_id',  $filterProject));

        $totals   = $baseQuery()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        $totalAll = array_sum($totals);

        $overdue = $baseQuery()->overdue()->count();

        $byAlertLevel = $baseQuery()
            ->join('occurrence_types', 'occurrences.occurrence_type_id', '=', 'occurrence_types.id')
            ->select('occurrence_types.alert_level', DB::raw('count(*) as total'))
            ->groupBy('occurrence_types.alert_level')
            ->pluck('total', 'alert_level')
            ->toArray();

        $byProvince = Occurrence::join('provinces', 'occurrences.province_id', '=', 'provinces.id')
            ->select('provinces.name', DB::raw('count(*) as total'))
            ->when(
                $user->isGestor(),
                fn($q) => $q->whereIn('occurrences.province_id', $gestorProvinceIds)->limit(5)
            )
            ->when($filterStatus,    fn($q) => $q->where('occurrences.status',      $filterStatus))
            ->when($filterAlertType, fn($q) => $q->where('occurrences.alert_type',  $filterAlertType))
            ->when($filterYear,      fn($q) => $q->whereYear('occurrences.created_at', $filterYear))
            ->when($filterProvince,  fn($q) => $q->where('occurrences.province_id', $filterProvince))
            ->when($filterProject,   fn($q) => $q->where('occurrences.project_id',  $filterProject))
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

        return [
            'totals'            => array_merge(['all' => $totalAll], $totals),
            'overdue'           => $overdue,
            'by_alert_level'    => $byAlertLevel,
            'by_category'       => $byCategory,
            'by_province'       => $byProvince,
            'by_month'          => $byMonthRaw->map(fn($r) => [
                'label' => sprintf('%04d-%02d', $r->year, $r->month),
                'total' => $r->total,
            ]),
            'by_month_resolved' => $byMonthRaw->map(fn($r) => [
                'label' => sprintf('%04d-%02d', $r->year, $r->month),
                'total' => (int) $r->resolved,
            ]),
            'recent'            => $recent,
        ];
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

        // Resumo via SQL — evita carregar todos os registos só para agregar
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
                'project'       => $o->project?->name ?? '—',
                'province'      => $o->province?->name ?? '—',
                'category'      => $o->category?->name ?? '—',
                'type'          => $o->occurrenceType?->name ?? '—',
                'alert_level'   => $o->occurrenceType?->alert_level?->label() ?? '—',
                'assigned_to'   => $o->assignedTo?->name ?? '—',
                'submitted_at'  => $o->created_at->format('d/m/Y'),
                'due_date'      => $o->due_date?->format('d/m/Y') ?? '—',
            ]),
        ], 200);
    }
}