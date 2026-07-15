<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Mail\UserCredentialsMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function __construct(
        private AuditService $auditService
    ) {}

    /**
     * Lista utilizadores com filtros e paginação.
     * ROTA: GET /api/admin/users
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = User::with(['province:id,name', 'provinces:id,name', 'projects:id,name,code'])
            ->withCount(['assignedOccurrences', 'submittedOccurrences'])
            ->where('role', '!=', 'admin');

        $query->when($request->role,        fn($q) => $q->where('role', $request->role));
        $query->when($request->province_id, fn($q) => $q->where('province_id', $request->province_id));
        $query->when(!is_null($request->is_active), fn($q) =>
            $q->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN))
        );
        $query->when($request->search, fn($q) =>
            $q->where(fn($q2) =>
                $q2->where('name',  'like', "%{$request->search}%")
                   ->orWhere('email', 'like', "%{$request->search}%")
            )
        );

        $perPage = min($request->integer('per_page', 15), 100);

        return UserResource::collection($query->orderBy('name')->paginate($perPage));
    }

    /**
     * Detalhe de um utilizador.
     * ROTA: GET /api/admin/users/{user}
     */
    public function show(User $user): UserResource
    {
        $user->load(['province:id,name', 'provinces:id,name', 'projects:id,name,code', 'createdBy:id,name']);

        return new UserResource($user);
    }

    /**
     * Cria um utilizador interno.
     * A senha temporária é gerada e enviada por email.
     * Províncias ficam na pivot user_provinces.
     * province_id na users = primeira província da lista.
     *
     * ROTA: POST /api/admin/users
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $temporaryPassword = Str::random(10) . rand(10, 99);
        $provinceIds       = $request->province_ids ?? [];

        $user = User::create([
            'name'                   => $request->name,
            'email'                  => $request->email,
            'phone'                  => $request->phone,
            'password'               => $temporaryPassword,
            'role'                   => $request->role,
            'management_scope'       => 'provincial',
            'province_id'            => $provinceIds[0] ?? null,  // província principal
            'receives_urgent_alerts' => $request->boolean('receives_urgent_alerts'),
            'receives_gbv_alerts'    => $request->boolean('receives_gbv_alerts'),
            'is_active'              => true,
            'created_by'             => $request->user()->id,
        ]);

        // Sync províncias (pivot user_provinces)
        $user->provinces()->sync($provinceIds);

        // Sync projectos
        if ($request->has('project_ids')) {
            $user->projects()->sync($request->project_ids);
        }

        $this->sendCredentialsEmail($user, $temporaryPassword);
        $this->auditService->logCreated($user);

        return response()->json([
            'message' => "Utilizador {$user->name} criado com sucesso. Credenciais enviadas por email.",
            'user'    => new UserResource($user->load('province:id,name', 'provinces:id,name', 'projects:id,name')),
        ], 201);
    }

    /**
     * Actualiza um utilizador.
     * ROTA: PUT /api/admin/users/{user}
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $oldValues   = $user->toArray();
        $provinceIds = $request->province_ids ?? [];

        $user->update([
            'name'                   => $request->name,
            'email'                  => $request->email,
            'phone'                  => $request->phone,
            'role'                   => $request->role,
            'management_scope'       => $request->management_scope,
            'province_id'            => $provinceIds[0] ?? null,
            'receives_urgent_alerts' => $request->boolean('receives_urgent_alerts'),
            'receives_gbv_alerts'    => $request->boolean('receives_gbv_alerts'),
        ]);

        // Sync províncias (pivot)
        $user->provinces()->sync($provinceIds);

        // Sync projectos
        if ($request->has('project_ids')) {
            $user->projects()->sync($request->project_ids);
        }

        // refresh() recarrega os atributos em-lugar (1 query); depois carregamos relações
        $user->refresh();
        $this->auditService->logUpdated($user, $oldValues, $user->toArray());
        Cache::forget("gestor_funcionarios.{$user->id}");
        $user->load(['province', 'provinces', 'projects']);

        return response()->json([
            'message' => 'Utilizador actualizado com sucesso.',
            'user'    => new UserResource($user),
        ], 200);
    }

    /**
     * Activa ou desactiva a conta.
     * ROTA: PATCH /api/admin/users/{user}/toggle-status
     */
    public function toggleStatus(Request $request, User $user): JsonResponse
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'Não pode alterar o estado da sua própria conta.'], 422);
        }

        $user->update(['is_active' => !$user->is_active]);

        if (!$user->is_active) {
            $user->tokens()->delete();
        }

        $status = $user->is_active ? 'activada' : 'desactivada';
        $this->auditService->logUpdated($user, ['is_active' => !$user->is_active], ['is_active' => $user->is_active]);

        return response()->json(['message' => "Conta {$status} com sucesso.", 'is_active' => $user->is_active], 200);
    }

    /**
     * Elimina um utilizador (soft delete).
     * ROTA: DELETE /api/admin/users/{user}
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'Não pode eliminar a sua própria conta.'], 422);
        }

        $user->provinces()->detach();
        $user->projects()->detach();
        $user->delete();

        $this->auditService->logDeleted($user);

        return response()->json(['message' => "Utilizador {$user->name} eliminado com sucesso."], 200);
    }

    /**
     * Lista gestores e admins elegíveis para atribuição de ocorrências.
     * ROTA: GET /api/admin/users/gestores
     */
    public function gestores(Request $request): JsonResponse
    {
        $gestores = User::active()
            ->whereIn('role', ['gestor', 'admin'])
            ->with(['provinces:id,name'])
            ->when($request->province_id, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('management_scope', 'national')
                       ->orWhereHas('provinces', fn($q3) => $q3->where('provinces.id', $request->province_id))
                )
            )
            ->select('id', 'name', 'email', 'role', 'management_scope', 'province_id')
            ->orderBy('name')
            ->get();

        return response()->json(['gestores' => UserResource::collection($gestores)], 200);
    }

    // ─── Private ────────────────────────────────────────────────

    private function sendCredentialsEmail(User $user, string $temporaryPassword): void
    {
        try {
            Mail::to($user->email)->send(new UserCredentialsMail($user, $temporaryPassword));
        } catch (\Throwable $e) {
            Log::error("Falha ao enviar credenciais para {$user->email}: " . $e->getMessage());
        }
    }
}