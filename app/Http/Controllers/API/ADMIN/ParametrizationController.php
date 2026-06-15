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
use Illuminate\Support\Facades\Cache;

class ParametrizationController extends Controller
{
    public function __construct(
        private AuditService $auditService
    ) {}

    // ─────────────────────────────────────────────────────────────
    // CATEGORIAS
    // ─────────────────────────────────────────────────────────────

    /**
     * Lista todas as categorias com subcategorias e contagem de ocorrências.
     * ROTA: GET /api/admin/categories
     */
    public function categoriesIndex(): JsonResponse
    {
        $categories = Cache::remember('admin.categories', 600, function () {
            return Category::withCount('occurrences')
                ->with(['subcategories' => fn($q) => $q->select('id', 'category_id', 'name', 'is_active')->orderBy('name')])
                ->orderBy('name')
                ->get()
                ->toArray();
        });

        return response()->json(['categories' => $categories], 200);
    }

    /**
     * Cria uma nova categoria.
     * ROTA: POST /api/admin/categories
     *
     * Body: {
     *   "code":        "FAU",               (obrigatório, único, max 20)
     *   "name":        "Fauna",             (obrigatório, max 100)
     *   "description": "Ocorrências…",      (opcional)
     *   "icon":        "fauna",             (opcional - fauna|flora|agua|fogo|pesca|lixo|ar|caca)
     *   "color":       "#52B788",           (opcional - hex)
     *   "tags":        ["animais","ilegal"] (opcional - array de strings)
     * }
     */
    public function categoriesStore(Request $request): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'code'        => ['required', 'string', 'max:20', 'unique:categories,code'],
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'icon'        => ['nullable', 'string', 'in:fauna,flora,agua,fogo,pesca,lixo,ar,caca'],
            'color'       => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'tags'        => ['nullable', 'array'],
            'tags.*'      => ['string', 'max:50'],
        ]);

        $category = Category::create([...$data, 'is_active' => true]);
        $this->auditService->logCreated($category);
        Cache::forget('ref.form_data');
        Cache::forget('admin.categories');

        return response()->json([
            'message'  => 'Categoria criada com sucesso.',
            'category' => $category,
        ], 201);
    }

    /**
     * Actualiza uma categoria.
     * ROTA: PUT /api/admin/categories/{category}
     */
    public function categoriesUpdate(Request $request, Category $category): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'code'        => ['required', 'string', 'max:20', "unique:categories,code,{$category->id}"],
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'icon'        => ['nullable', 'string', 'in:fauna,flora,agua,fogo,pesca,lixo,ar,caca'],
            'color'       => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'tags'        => ['nullable', 'array'],
            'tags.*'      => ['string', 'max:50'],
            'is_active'   => ['boolean'],
        ]);

        $old = $category->toArray();
        $category->update($data);
        $this->auditService->logUpdated($category, $old, $category->toArray());
        Cache::forget('ref.form_data');
        Cache::forget('admin.categories');

        return response()->json([
            'message'  => 'Categoria actualizada.',
            'category' => $category,
        ], 200);
    }

    /**
     * Elimina (soft delete) uma categoria.
     * ROTA: DELETE /api/admin/categories/{category}
     *
     * Bloqueado se existirem ocorrências associadas - nesse caso a
     * categoria deve ser desactivada em vez de eliminada.
     */
    public function categoriesDestroy(Request $request, Category $category): JsonResponse
    {
        $this->authorizeAdmin($request);

        $occurrencesCount = $category->occurrences()->count();
        if ($occurrencesCount > 0) {
            return response()->json([
                'message' => "Não é possível apagar a categoria \"{$category->name}\" porque existem {$occurrencesCount} ocorrência(s) associada(s). Desactive-a em alternativa.",
            ], 422);
        }

        $this->auditService->logDeleted($category);
        $category->delete();
        Cache::forget('ref.form_data');
        Cache::forget('admin.categories');

        return response()->json(['message' => 'Categoria apagada com sucesso.'], 200);
    }

    // ─────────────────────────────────────────────────────────────
    // SUBCATEGORIAS
    // ─────────────────────────────────────────────────────────────

    public function subcategoriesIndex(Category $category): JsonResponse
    {
        return response()->json([
            'subcategories' => $category->subcategories()
                ->withCount('occurrences')
                ->orderBy('name')
                ->get(),
        ], 200);
    }

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
        Cache::forget('ref.form_data');
        Cache::forget('admin.categories');

        return response()->json([
            'message'     => 'Subcategoria criada com sucesso.',
            'subcategory' => $sub,
        ], 201);
    }

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
        Cache::forget('ref.form_data');
        Cache::forget('admin.categories');

        return response()->json([
            'message'     => 'Subcategoria actualizada.',
            'subcategory' => $subcategory,
        ], 200);
    }

    // ─────────────────────────────────────────────────────────────
    // PROJECTOS
    // ─────────────────────────────────────────────────────────────

    public function projectsIndex(): JsonResponse
    {
        $projects = Cache::remember('admin.projects', 600, function () {
            return Project::withCount('occurrences')
                ->orderBy('name')
                ->get()
                ->toArray();
        });

        return response()->json(['projects' => $projects], 200);
    }

    public function projectsStore(Request $request): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'code'        => ['required', 'string', 'max:20', 'unique:projects,code'],
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'icon'        => ['nullable', 'string', 'in:nature,tree,water,fire,drop,person,leaf,fish'],
        ]);

        $project = Project::create([...$data, 'is_active' => true]);
        $this->auditService->logCreated($project);
        Cache::forget('ref.form_data');
        Cache::forget('admin.projects');

        return response()->json([
            'message' => 'Projecto criado com sucesso.',
            'project' => $project,
        ], 201);
    }

    public function projectsUpdate(Request $request, Project $project): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'code'        => ['required', 'string', 'max:20', "unique:projects,code,{$project->id}"],
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'icon'        => ['nullable', 'string', 'in:nature,tree,water,fire,drop,person,leaf,fish'],
            'is_active'   => ['boolean'],
        ]);

        $old = $project->toArray();
        $project->update($data);
        $this->auditService->logUpdated($project, $old, $project->toArray());
        Cache::forget('ref.form_data');
        Cache::forget('admin.projects');

        return response()->json([
            'message' => 'Projecto actualizado.',
            'project' => $project,
        ], 200);
    }

    /**
     * Elimina (soft delete) um projecto.
     * ROTA: DELETE /api/admin/projects/{project}
     *
     * Bloqueado se existirem ocorrências associadas - nesse caso o
     * projecto deve ser desactivado em vez de eliminado.
     */
    public function projectsDestroy(Request $request, Project $project): JsonResponse
    {
        $this->authorizeAdmin($request);

        $occurrencesCount = $project->occurrences()->count();
        if ($occurrencesCount > 0) {
            return response()->json([
                'message' => "Não é possível apagar o projecto \"{$project->name}\" porque existem {$occurrencesCount} ocorrência(s) associada(s). Desactive-o em alternativa.",
            ], 422);
        }

        $this->auditService->logDeleted($project);
        $project->delete();
        Cache::forget('ref.form_data');
        Cache::forget('admin.projects');

        return response()->json(['message' => 'Projecto apagado com sucesso.'], 200);
    }

    // ─────────────────────────────────────────────────────────────
    // TIPOS DE OCORRÊNCIA
    // ─────────────────────────────────────────────────────────────

    public function occurrenceTypesIndex(): JsonResponse
    {
        $types = Cache::remember('admin.occurrence_types', 600, function () {
            return OccurrenceType::withCount('occurrences')
                ->orderBy('name')
                ->get()
                ->toArray();
        });

        return response()->json(['occurrence_types' => $types], 200);
    }

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
        Cache::forget('ref.form_data');
        Cache::forget('admin.occurrence_types');

        return response()->json([
            'message'         => 'Tipo de ocorrência criado com sucesso.',
            'occurrence_type' => $type,
        ], 201);
    }

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
        Cache::forget('ref.form_data');
        Cache::forget('admin.occurrence_types');

        return response()->json([
            'message'         => 'Tipo de ocorrência actualizado.',
            'occurrence_type' => $occurrenceType,
        ], 200);
    }

    private function authorizeAdmin(Request $request): void
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem gerir parametrizações.');
        }
    }
}