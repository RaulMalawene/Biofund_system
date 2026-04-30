<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OccurrenceType;
use App\Models\Project;
use App\Models\Subcategory;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * ParametrizationController
 *
 * Gere todas as tabelas de parametrização do sistema:
 *   - Categorias e Subcategorias
 *   - Projectos
 *   - Tipos de Ocorrência
 *
 * Acesso: Admin (CRUD completo) e Gestor (apenas leitura).
 *
 * NOTA: Províncias e Distritos são dados de referência pré-carregados
 * pelos seeders e não são editáveis pela interface (apenas pelo admin técnico).
 */
class ParametrizationController extends Controller
{
    public function __construct(
        private AuditService $auditService
    ) {}

    // ─────────────────────────────────────────────────────────────
    // CATEGORIAS
    // ─────────────────────────────────────────────────────────────

    /**
     * Lista todas as categorias com as suas subcategorias.
     *
     * ROTA: GET /api/admin/categories
     * ACESSO: Autenticado (admin e gestor)
     */
    public function categoriesIndex(): JsonResponse
    {
        $categories = Category::withCount('occurrences')
            ->with(['subcategories' => fn($q) => $q->withCount('occurrences')])
            ->orderBy('name')
            ->get();

        return response()->json(['categories' => $categories], 200);
    }

    /**
     * Cria uma nova categoria.
     *
     * ROTA: POST /api/admin/categories
     * ACESSO: Autenticado (apenas admin)
     *
     * Body: { "code": "ECC", "name": "Económico" }
     */
    public function categoriesStore(Request $request): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:categories,code'],
            'name' => ['required', 'string', 'max:100'],
        ]);

        $category = Category::create([...$data, 'is_active' => true]);
        $this->auditService->logCreated($category);

        return response()->json([
            'message'  => 'Categoria criada com sucesso.',
            'category' => $category,
        ], 201);
    }

    /**
     * Actualiza uma categoria existente.
     *
     * ROTA: PUT /api/admin/categories/{category}
     * ACESSO: Autenticado (apenas admin)
     */
    public function categoriesUpdate(Request $request, Category $category): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'code'      => ['required', 'string', 'max:20', "unique:categories,code,{$category->id}"],
            'name'      => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        $old = $category->toArray();
        $category->update($data);
        $this->auditService->logUpdated($category, $old, $category->toArray());

        return response()->json([
            'message'  => 'Categoria actualizada.',
            'category' => $category,
        ], 200);
    }

    // ─────────────────────────────────────────────────────────────
    // SUBCATEGORIAS
    // ─────────────────────────────────────────────────────────────

    /**
     * Lista subcategorias de uma categoria.
     *
     * ROTA: GET /api/admin/categories/{category}/subcategories
     * ACESSO: Autenticado (admin e gestor)
     */
    public function subcategoriesIndex(Category $category): JsonResponse
    {
        return response()->json([
            'subcategories' => $category->subcategories()
                ->withCount('occurrences')
                ->orderBy('name')
                ->get(),
        ], 200);
    }

    /**
     * Cria uma subcategoria dentro de uma categoria.
     *
     * ROTA: POST /api/admin/categories/{category}/subcategories
     * ACESSO: Autenticado (apenas admin)
     *
     * Body: { "name": "Desflorestação" }
     */
    public function subcategoriesStore(Request $request, Category $category): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $sub = Subcategory::create([
            'category_id' => $category->id,
            'name'        => $data['name'],
            'is_active'   => true,
        ]);

        $this->auditService->logCreated($sub);

        return response()->json([
            'message'     => 'Subcategoria criada com sucesso.',
            'subcategory' => $sub,
        ], 201);
    }

    /**
     * Actualiza uma subcategoria.
     *
     * ROTA: PUT /api/admin/subcategories/{subcategory}
     * ACESSO: Autenticado (apenas admin)
     */
    public function subcategoriesUpdate(Request $request, Subcategory $subcategory): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        $old = $subcategory->toArray();
        $subcategory->update($data);
        $this->auditService->logUpdated($subcategory, $old, $subcategory->toArray());

        return response()->json([
            'message'     => 'Subcategoria actualizada.',
            'subcategory' => $subcategory,
        ], 200);
    }

    // ─────────────────────────────────────────────────────────────
    // PROJECTOS
    // ─────────────────────────────────────────────────────────────

    /**
     * Lista todos os projectos.
     *
     * ROTA: GET /api/admin/projects
     * ACESSO: Autenticado (admin e gestor)
     */
    public function projectsIndex(): JsonResponse
    {
        $projects = Project::withCount('occurrences')
            ->with(['users:id,name,role'])
            ->orderBy('name')
            ->get();

        return response()->json(['projects' => $projects], 200);
    }

    /**
     * Cria um novo projecto.
     *
     * ROTA: POST /api/admin/projects
     * ACESSO: Autenticado (apenas admin)
     *
     * Body: { "code": "FNDS-002", "name": "Nome do Projecto", "description": "..." }
     */
    public function projectsStore(Request $request): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'code'        => ['required', 'string', 'max:20', 'unique:projects,code'],
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ]);

        $project = Project::create([...$data, 'is_active' => true]);
        $this->auditService->logCreated($project);

        return response()->json([
            'message' => 'Projecto criado com sucesso.',
            'project' => $project,
        ], 201);
    }

    /**
     * Actualiza um projecto.
     *
     * ROTA: PUT /api/admin/projects/{project}
     * ACESSO: Autenticado (apenas admin)
     */
    public function projectsUpdate(Request $request, Project $project): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'code'        => ['required', 'string', 'max:20', "unique:projects,code,{$project->id}"],
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        $old = $project->toArray();
        $project->update($data);
        $this->auditService->logUpdated($project, $old, $project->toArray());

        return response()->json([
            'message' => 'Projecto actualizado.',
            'project' => $project,
        ], 200);
    }

    // ─────────────────────────────────────────────────────────────
    // TIPOS DE OCORRÊNCIA
    // ─────────────────────────────────────────────────────────────

    /**
     * Lista todos os tipos de ocorrência.
     *
     * ROTA: GET /api/admin/occurrence-types
     * ACESSO: Autenticado (admin e gestor)
     */
    public function occurrenceTypesIndex(): JsonResponse
    {
        $types = OccurrenceType::withCount('occurrences')
            ->orderBy('name')
            ->get();

        return response()->json(['occurrence_types' => $types], 200);
    }

    /**
     * Cria um novo tipo de ocorrência.
     *
     * ROTA: POST /api/admin/occurrence-types
     * ACESSO: Autenticado (apenas admin)
     *
     * Body:
     *   {
     *     "code": "DEN",
     *     "name": "Denúncia",
     *     "alert_level": "urgent",
     *     "sla_days": 7
     *   }
     */
    public function occurrenceTypesStore(Request $request): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'code'        => ['required', 'string', 'max:20', 'unique:occurrence_types,code'],
            'name'        => ['required', 'string', 'max:100'],
            'alert_level' => ['required', 'in:normal,urgent,gbv'],
            'sla_days'    => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        $type = OccurrenceType::create([...$data, 'is_active' => true]);
        $this->auditService->logCreated($type);

        return response()->json([
            'message'         => 'Tipo de ocorrência criado com sucesso.',
            'occurrence_type' => $type,
        ], 201);
    }

    /**
     * Actualiza um tipo de ocorrência.
     *
     * ROTA: PUT /api/admin/occurrence-types/{occurrenceType}
     * ACESSO: Autenticado (apenas admin)
     */
    public function occurrenceTypesUpdate(Request $request, OccurrenceType $occurrenceType): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'code'        => ['required', 'string', 'max:20', "unique:occurrence_types,code,{$occurrenceType->id}"],
            'name'        => ['required', 'string', 'max:100'],
            'alert_level' => ['required', 'in:normal,urgent,gbv'],
            'sla_days'    => ['required', 'integer', 'min:1', 'max:365'],
            'is_active'   => ['boolean'],
        ]);

        $old = $occurrenceType->toArray();
        $occurrenceType->update($data);
        $this->auditService->logUpdated($occurrenceType, $old, $occurrenceType->toArray());

        return response()->json([
            'message'         => 'Tipo de ocorrência actualizado.',
            'occurrence_type' => $occurrenceType,
        ], 200);
    }

    /**
     * Verifica se o utilizador autenticado é administrador.
     */
    private function authorizeAdmin(Request $request): void
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem gerir parametrizações.');
        }
    }
}