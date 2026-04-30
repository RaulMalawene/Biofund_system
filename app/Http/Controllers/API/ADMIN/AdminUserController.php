<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * AdminUserController
 *
 * Gere os utilizadores internos do sistema MDR.
 * Exclusivo para administradores.
 *
 * Inclui criação, edição, activação/desactivação
 * e listagem de utilizadores com filtros.
 */
class AdminUserController extends Controller
{
    public function __construct(
        private AuditService $auditService
    ) {}

    /**
     * Lista todos os utilizadores internos com filtros.
     *
     * ROTA: GET /api/admin/users
     * ACESSO: Autenticado (apenas admin)
     *
     * Query params (opcionais):
     *   ?role=gestor
     *   ?province_id=1
     *   ?is_active=true
     *   ?search=nome_ou_email
     *   ?per_page=15
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorizeAdmin($request);

        $query = User::with(['province:id,name', 'projects:id,name,code'])
            ->withCount(['assignedOccurrences', 'submittedOccurrences']);

        $query->when($request->role, fn($q) =>
            $q->where('role', $request->role)
        );
        $query->when($request->province_id, fn($q) =>
            $q->where('province_id', $request->province_id)
        );
        $query->when(!is_null($request->is_active), fn($q) =>
            $q->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN))
        );
        $query->when($request->search, fn($q) =>
            $q->where(function ($q2) use ($request) {
                $q2->where('name', 'like', "%{$request->search}%")
                   ->orWhere('email', 'like', "%{$request->search}%");
            })
        );

        $perPage = min($request->integer('per_page', 15), 100);

        return response()->json(
            $query->orderBy('name')->paginate($perPage)
        , 200);
    }

    /**
     * Retorna os dados de um utilizador específico.
     *
     * ROTA: GET /api/admin/users/{user}
     * ACESSO: Autenticado (apenas admin)
     */
    public function show(Request $request, User $user): JsonResponse
    {
        $this->authorizeAdmin($request);

        $user->load(['province:id,name', 'projects:id,name,code', 'createdBy:id,name']);

        return response()->json(['user' => $user], 200);
    }

    /**
     * Cria um novo utilizador interno.
     * A senha inicial é gerada automaticamente e enviada por email.
     *
     * ROTA: POST /api/admin/users
     * ACESSO: Autenticado (apenas admin)
     *
     * Body:
     *   {
     *     "name": "Maria Cossa",
     *     "email": "maria@mdr.biofund.org.mz",
     *     "phone": "+258 84 000 0000",
     *     "role": "gestor",
     *     "management_scope": "provincial",
     *     "province_id": 1,
     *     "project_ids": [1, 2],
     *     "receives_urgent_alerts": true,
     *     "receives_gbv_alerts": false
     *   }
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->authorizeAdmin($request);

        // Gera uma senha inicial aleatória segura
        $temporaryPassword = Str::password(12);

        $user = User::create([
            ...$request->validated(),
            'password'   => Hash::make($temporaryPassword),
            'created_by' => $request->user()->id,
            'is_active'  => true,
        ]);

        // Atribui os projectos ao utilizador (pivot user_projects)
        if ($request->has('project_ids')) {
            $user->projects()->sync($request->project_ids);
        }

        // Envia email com as credenciais iniciais
        $this->sendCredentialsEmail($user, $temporaryPassword);

        $this->auditService->logCreated($user);

        return response()->json([
            'message' => "Utilizador {$user->name} criado com sucesso. As credenciais foram enviadas por email.",
            'user'    => $user->load('province:id,name', 'projects:id,name'),
        ], 201);
    }

    /**
     * Actualiza os dados de um utilizador.
     * Não é possível alterar a senha por aqui (usa change-password).
     *
     * ROTA: PUT /api/admin/users/{user}
     * ACESSO: Autenticado (apenas admin)
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->authorizeAdmin($request);

        $oldValues = $user->toArray();
        $user->update($request->validated());

        // Sincroniza os projectos se fornecidos
        if ($request->has('project_ids')) {
            $user->projects()->sync($request->project_ids);
        }

        $this->auditService->logUpdated($user, $oldValues, $user->fresh()->toArray());

        return response()->json([
            'message' => 'Utilizador actualizado com sucesso.',
            'user'    => $user->load('province:id,name', 'projects:id,name'),
        ], 200);
    }

    /**
     * Activa ou desactiva a conta de um utilizador.
     * Um admin não pode desactivar a sua própria conta.
     *
     * ROTA: PATCH /api/admin/users/{user}/toggle-status
     * ACESSO: Autenticado (apenas admin)
     *
     * Resposta (200):
     *   { "message": "Conta activada/desactivada.", "is_active": true }
     */
    public function toggleStatus(Request $request, User $user): JsonResponse
    {
        $this->authorizeAdmin($request);

        // Impede que o admin desactive a sua própria conta
        if ($user->id === $request->user()->id) {
            return response()->json([
                'message' => 'Não pode alterar o estado da sua própria conta.',
            ], 422);
        }

        $user->update(['is_active' => !$user->is_active]);

        // Revoga todos os tokens se a conta foi desactivada
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
     * Lista todos os gestores elegíveis para atribuição de ocorrências.
     * Usado pelo frontend no dropdown de atribuição.
     *
     * ROTA: GET /api/admin/users/gestores
     * ACESSO: Autenticado (apenas admin)
     *
     * Query param opcional:
     *   ?province_id=1 → filtra gestores da província
     */
    public function gestores(Request $request): JsonResponse
    {
        $this->authorizeAdmin($request);

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

        return response()->json(['gestores' => $gestores], 200);
    }

    /**
     * Envia as credenciais iniciais de acesso por email.
     *
     * @param  User    $user               O utilizador criado
     * @param  string  $temporaryPassword  A senha temporária em texto claro
     */
    private function sendCredentialsEmail(User $user, string $temporaryPassword): void
    {
        try {
            \Illuminate\Support\Facades\Mail::raw(
                "Prezado(a) {$user->name},\n\n"
                . "A sua conta no sistema MDR foi criada com sucesso.\n\n"
                . "Credenciais de acesso:\n"
                . "  Email: {$user->email}\n"
                . "  Senha: {$temporaryPassword}\n\n"
                . "Acesse o sistema em: {$_ENV['APP_URL']}\n\n"
                . "Por motivos de segurança, altere a sua senha após o primeiro login.\n\n"
                . "Com os melhores cumprimentos,\n"
                . "Equipa MDR — BIOFUND/FNDS",
                fn($mail) => $mail->to($user->email)
                    ->subject('MDR — Credenciais de Acesso')
            );
        } catch (\Throwable $e) {
            // Não bloqueia a criação do utilizador se o email falhar
            \Illuminate\Support\Facades\Log::error(
                "Falha ao enviar credenciais para {$user->email}: " . $e->getMessage()
            );
        }
    }

    /**
     * Verifica se o utilizador autenticado é administrador.
     * Retorna 403 se não for.
     */
    private function authorizeAdmin(Request $request): void
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Apenas administradores têm acesso a esta funcionalidade.');
        }
    }
}