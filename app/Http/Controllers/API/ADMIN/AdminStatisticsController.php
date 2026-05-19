<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Occurrence;
use App\Models\User;
use App\Models\NotificationLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $user  = $request->user();

        // Base query com restrição por área se for gestor provincial
        $baseQuery = fn() => Occurrence::when(
            $user->management_scope === 'provincial',
            fn($q) => $q->where('province_id', $user->province_id)
        );

        // Totais por estado
        $totals = $baseQuery()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Total geral
        $totalAll = array_sum($totals);

        // Ocorrências fora do prazo (SLA vencido)
        $overdue = $baseQuery()->overdue()->count();

        // Distribuição por nível de alerta
        $byAlertLevel = $baseQuery()
            ->join('occurrence_types', 'occurrences.occurrence_type_id', '=', 'occurrence_types.id')
            ->select('occurrence_types.alert_level', DB::raw('count(*) as total'))
            ->groupBy('occurrence_types.alert_level')
            ->pluck('total', 'alert_level')
            ->toArray();

        // Top 5 províncias com mais ocorrências
        $byProvince = $baseQuery()
            ->join('provinces', 'occurrences.province_id', '=', 'provinces.id')
            ->select('provinces.name', DB::raw('count(*) as total'))
            ->groupBy('provinces.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Top categorias com mais ocorrências
        $byCategory = $baseQuery()
            ->join('categories', 'occurrences.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        // Ocorrências por mês (últimos 6 meses)
        $byMonth = $baseQuery()
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get()
            ->map(fn($row) => [
                'label' => sprintf('%04d-%02d', $row->year, $row->month),
                'total' => $row->total,
            ]);

        // Resolvidas por mês (últimos 6 meses) — segunda linha do gráfico
        $byMonthResolved = $baseQuery()
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->where('status', 'resolved')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get()
            ->map(fn($row) => [
                'label' => sprintf('%04d-%02d', $row->year, $row->month),
                'total' => $row->total,
            ]);

        // Ocorrências recentes (últimas 10)
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

        return response()->json([
            'totals'            => array_merge(['all' => $totalAll], $totals),
            'overdue'           => $overdue,
            'by_alert_level'    => $byAlertLevel,
            'by_category'       => $byCategory,
            'by_province'       => $byProvince,
            'by_month'          => $byMonth,
            'by_month_resolved' => $byMonthResolved,
            'recent'            => $recent,
        ], 200);
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
        $query = Occurrence::with([
            'project:id,name', 'province:id,name',
            'category:id,name', 'occurrenceType:id,name,alert_level',
            'assignedTo:id,name',
        ]);

        // Restrição de área para gestores provinciais
        if ($user->management_scope === 'provincial') {
            $query->where('province_id', $user->province_id);
        }

        // Aplicar filtros
        $query->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from));
        $query->when($request->date_to,   fn($q) => $q->whereDate('created_at', '<=', $request->date_to));
        $query->when($request->status,    fn($q) => $q->where('status', $request->status));
        $query->when($request->project_id,         fn($q) => $q->where('project_id', $request->project_id));
        $query->when($request->province_id,        fn($q) => $q->where('province_id', $request->province_id));
        $query->when($request->category_id,        fn($q) => $q->where('category_id', $request->category_id));
        $query->when($request->occurrence_type_id, fn($q) => $q->where('occurrence_type_id', $request->occurrence_type_id));

        $occurrences = $query->orderBy('created_at', 'desc')->get();

        // Resumo agregado
        $summary = [
            'total'      => $occurrences->count(),
            'by_status'  => $occurrences->groupBy('status')->map->count(),
            'by_project' => $occurrences->groupBy('project.name')->map->count(),
            'overdue'    => $occurrences->filter(fn($o) => $o->isOverdue())->count(),
        ];

        return response()->json([
            'summary'     => $summary,
            'occurrences' => $occurrences->map(fn($o) => [
                'tracking_code' => $o->tracking_code,
                'subject'       => $o->subject,
                'status'        => $o->status->label(),
                'project'       => $o->project->name,
                'province'      => $o->province->name,
                'category'      => $o->category->name,
                'type'          => $o->occurrenceType->name,
                'alert_level'   => $o->occurrenceType->alert_level->label(),
                'assigned_to'   => $o->assignedTo?->name ?? '—',
                'submitted_at'  => $o->created_at->format('d/m/Y'),
                'due_date'      => $o->due_date?->format('d/m/Y') ?? '—',
            ]),
        ], 200);
    }
}