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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * AdminUserController
 *
 * Gere os utilizadores internos do sistema.
 * Exclusivo para administradores (protegido via middleware 'role:admin' na rota).
 *
 * Ao criar um utilizador, a província é SEMPRE obrigatória.
 * A senha inicial é gerada e enviada por email.
 */
class AdminUserController extends Controller
{
    public function __construct(
        private AuditService $auditService
    ) {}

    /**
     * ROTA: GET /api/admin/users
     * ACESSO: admin (middleware na rota)
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = User::with(['province:id,name', 'projects:id,name,code'])
            ->withCount(['assignedOccurrences', 'submittedOccurrences']);

        $query->when($request->role, fn($q) => $q->where('role', $request->role));
        $query->when($request->province_id, fn($q) => $q->where('province_id', $request->province_id));
        $query->when(!is_null($request->is_active), fn($q) =>
            $q->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN))
        );
        $query->when($request->search, fn($q) =>
            $q->where(fn($q2) =>
                $q2->where('name', 'like', "%{$request->search}%")
                   ->orWhere('email', 'like', "%{$request->search}%")
            )
        );

        $perPage = min($request->integer('per_page', 15), 100);

        return UserResource::collection($query->orderBy('name')->paginate($perPage));
    }

    /**
     * ROTA: GET /api/admin/users/{user}
     * ACESSO: admin
     */
    public function show(User $user): UserResource
    {
        $user->load(['province:id,name', 'projects:id,name,code', 'createdBy:id,name']);

        return new UserResource($user);
    }

    /**
     * Cria um novo utilizador interno.
     * A senha inicial é gerada e enviada por email.
     * A província é sempre obrigatória.
     *
     * ROTA: POST /api/admin/users
     * ACESSO: admin
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $temporaryPassword = 12345678;

        $user = User::create([
            ...$request->validated(),
            'password'         => $temporaryPassword,
            'created_by'       => $request->user()->id,
            'is_active'        => true,
            'management_scope' => 'provincial',
        ]);

        if ($request->has('project_ids')) {
            $user->projects()->sync($request->project_ids);
        }

        $this->sendCredentialsEmail($user, $temporaryPassword);
        $this->auditService->logCreated($user);

        return response()->json([
            'message' => "Utilizador {$user->name} criado com sucesso. Credenciais enviadas por email.",
            'user'    => new UserResource($user->load('province:id,name', 'projects:id,name')),
        ], 201);
    }

    /**
     * ROTA: PUT /api/admin/users/{user}
     * ACESSO: admin
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $oldValues = $user->toArray();
        $user->update($request->validated());

        if ($request->has('project_ids')) {
            $user->projects()->sync($request->project_ids);
        }

        $this->auditService->logUpdated($user, $oldValues, $user->fresh()->toArray());

        return response()->json([
            'message' => 'Utilizador actualizado com sucesso.',
            'user'    => new UserResource($user->fresh(['province', 'projects'])),
        ], 200);
    }

    /**
     * Activa ou desactiva a conta de um utilizador.
     * Um admin não pode desactivar a sua própria conta.
     *
     * ROTA: PATCH /api/admin/users/{user}/toggle-status
     * ACESSO: admin
     */
    public function toggleStatus(Request $request, User $user): JsonResponse
    {
        if ($user->id === $request->user()->id) {
            return response()->json([
                'message' => 'Não pode alterar o estado da sua própria conta.',
            ], 422);
        }

        $user->update(['is_active' => !$user->is_active]);

        if (!$user->is_active) {
            $user->tokens()->delete();
        }

        $status = $user->is_active ? 'activada' : 'desactivada';
        $this->auditService->logUpdated(
            $user,
            ['is_active' => !$user->is_active],
            ['is_active' => $user->is_active]
        );

        return response()->json([
            'message'   => "Conta {$status} com sucesso.",
            'is_active' => $user->is_active,
        ], 200);
    }

    /**
     * Lista gestores e admins elegíveis para atribuição de ocorrências.
     *
     * ROTA: GET /api/admin/users/gestores
     * ACESSO: admin, gestor
     */
    public function gestores(Request $request): JsonResponse
    {
        $gestores = User::active()
            ->whereIn('role', ['gestor', 'admin'])
            ->with('province:id,name')
            ->when($request->province_id, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('management_scope', 'national')
                       ->orWhere('province_id', $request->province_id)
                )
            )
            ->select('id', 'name', 'email', 'role', 'management_scope', 'province_id')
            ->orderBy('name')
            ->get();

        return response()->json([
            'gestores' => UserResource::collection($gestores),
        ], 200);
    }

    // ─── Private ────────────────────────────────────────────────

    private function sendCredentialsEmail(User $user, string $temporaryPassword): void
    {
        try {
            Mail::raw(
                "Prezado(a) {$user->name},\n\n"
                . "A sua conta no sistema MDR foi criada com sucesso.\n\n"
                . "Credenciais de acesso:\n"
                . "  Email: {$user->email}\n"
                . "  Senha: {$temporaryPassword}\n\n"
                . "Acesse o sistema em: " . config('app.url') . "\n\n"
                . "Por motivos de segurança, altere a sua senha após o primeiro login.\n\n"
                . "Com os melhores cumprimentos,\n"
                . "Equipa MDR — BIOFUND/FNDS",
                fn($mail) => $mail->to($user->email)->subject('MDR — Credenciais de Acesso')
            );
        } catch (\Throwable $e) {
            Log::error("Falha ao enviar credenciais para {$user->email}: " . $e->getMessage());
        }
    }
}