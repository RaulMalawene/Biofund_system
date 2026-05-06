<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Public\PublicOccurrenceController;
use App\Http\Controllers\Api\Gestor\GestorOccurrenceController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\AdminStatisticsController;
use App\Http\Controllers\Api\Admin\ParametrizationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MDR — API Routes
|--------------------------------------------------------------------------
|
| Organização:
|   1. Rotas públicas       → sem autenticação
|   2. Rotas de autenticação → login, logout, password
|   3. Rotas protegidas     → requerem token Sanctum
|      3.1 Ocorrências      → todos os utilizadores autenticados
|      3.2 Admin — Users    → apenas admin
|      3.3 Admin — Stats    → admin e gestor
|      3.4 Parametrização   → admin (escrita) e gestor (leitura)
|
*/

// ═══════════════════════════════════════════════════════════════
// 1. ROTAS PÚBLICAS (sem autenticação)
// ═══════════════════════════════════════════════════════════════

Route::prefix('public')->name('public.')->group(function () {

    // Dados para preencher o formulário público (selects)
    // GET /api/public/form-data
    Route::get('form-data', [PublicOccurrenceController::class, 'formData'])
        ->name('form-data');

    // Distritos de uma província (chamado ao seleccionar província)
    // GET /api/public/provinces/{province}/districts
    Route::get('provinces/{province}/districts', [PublicOccurrenceController::class, 'districtsByProvince'])
        ->name('districts');

    // Submissão pública de ocorrência (formulário sem login)
    // POST /api/public/occurrences
    Route::post('occurrences', [PublicOccurrenceController::class, 'store'])
        ->name('occurrences.store');

    // Acompanhamento por código de seguimento
    // GET /api/public/occurrences/track/{code}
    Route::get('occurrences/track/{code}', [PublicOccurrenceController::class, 'track'])
        ->name('occurrences.track');
});

// ═══════════════════════════════════════════════════════════════
// 2. AUTENTICAÇÃO (sem autenticação prévia)
// ═══════════════════════════════════════════════════════════════

Route::prefix('auth')->name('auth.')->group(function () {

    // Login → retorna token Sanctum
    // POST /api/auth/login
    Route::post('login', [LoginController::class, 'login'])
        ->name('login');

    // Solicitar recuperação de senha por email
    // POST /api/auth/forgot-password
    Route::post('forgot-password', [PasswordResetController::class, 'forgotPassword'])
        ->name('forgot-password');

    // Redefinir senha com token recebido por email
    // POST /api/auth/reset-password
    Route::post('reset-password', [PasswordResetController::class, 'resetPassword'])
        ->name('reset-password');
});

// ═══════════════════════════════════════════════════════════════
// 3. ROTAS PROTEGIDAS (requerem token Sanctum)
// ═══════════════════════════════════════════════════════════════

Route::middleware('auth:sanctum')->group(function () {

    // ── Auth (sessão activa) ────────────────────────────────────

    // Dados do utilizador autenticado
    // GET /api/auth/me
    Route::get('auth/me', [LoginController::class, 'me'])
        ->name('auth.me');

    // Logout
    // POST /api/auth/logout
    Route::post('auth/logout', [LoginController::class, 'logout'])
        ->name('auth.logout');

    // Alterar senha (requer senha actual)
    // POST /api/auth/change-password
    Route::post('auth/change-password', [PasswordResetController::class, 'changePassword'])
        ->name('auth.change-password');

    // ── 3.1 OCORRÊNCIAS (admin + gestor + funcionario) ──────────

    Route::prefix('occurrences')->name('occurrences.')->group(function () {

        // Listar ocorrências (com filtros e paginação)
        // GET /api/occurrences
        Route::get('/', [GestorOccurrenceController::class, 'index'])
            ->name('index');

        // Ver ocorrências removidas (apenas admin)
        // GET /api/occurrences/deleted
        Route::get('deleted', [GestorOccurrenceController::class, 'deleted'])
            ->name('deleted');

        // Detalhe de uma ocorrência
        // GET /api/occurrences/{occurrence}
        Route::get('{occurrence}', [GestorOccurrenceController::class, 'show'])
            ->name('show');

        // Submeter nova ocorrência (utilizador interno)
        // POST /api/occurrences
        Route::post('/', [GestorOccurrenceController::class, 'store'])
            ->name('store');

        // Mudar estado (validar, rejeitar, iniciar análise)
        // PATCH /api/occurrences/{occurrence}/status
        Route::patch('{occurrence}/status', [GestorOccurrenceController::class, 'updateStatus'])
            ->name('update-status');

        // Atribuir ocorrência a um gestor (apenas admin)
        // PATCH /api/occurrences/{occurrence}/assign
        Route::patch('{occurrence}/assign', [GestorOccurrenceController::class, 'assign'])
            ->name('assign');

        // Remover ocorrência — soft delete (apenas admin)
        // DELETE /api/occurrences/{occurrence}
        Route::delete('{occurrence}', [GestorOccurrenceController::class, 'destroy'])
            ->name('destroy');
    });

    // ── 3.2 ADMIN — UTILIZADORES ────────────────────────────────

    Route::prefix('admin')->name('admin.')->group(function () {

        // ── Utilizadores ──────────────────────────────────────

        // Listar utilizadores (com filtros)
        // GET /api/admin/users
        Route::get('users', [AdminUserController::class, 'index'])
            ->name('users.index');

        // Listar gestores elegíveis para atribuição
        // GET /api/admin/users/gestores
        Route::get('users/gestores', [AdminUserController::class, 'gestores'])
            ->name('users.gestores');

        // Detalhe de um utilizador
        // GET /api/admin/users/{user}
        Route::get('users/{user}', [AdminUserController::class, 'show'])
            ->name('users.show');

        // Criar utilizador
        // POST /api/admin/users
        Route::post('users', [AdminUserController::class, 'store'])
            ->name('users.store');

        // Actualizar utilizador
        // PUT /api/admin/users/{user}
        Route::put('users/{user}', [AdminUserController::class, 'update'])
            ->name('users.update');

        // Activar / Desactivar conta
        // PATCH /api/admin/users/{user}/toggle-status
        Route::patch('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // ── Estatísticas ───────────────────────────────────────

        // Dashboard (cards + gráficos)
        // GET /api/admin/statistics/dashboard
        Route::get('statistics/dashboard', [AdminStatisticsController::class, 'dashboard'])
            ->name('statistics.dashboard');

        // Relatório filtrado
        // GET /api/admin/statistics/report
        Route::get('statistics/report', [AdminStatisticsController::class, 'report'])
            ->name('statistics.report');

        // ── Parametrização — Categorias ────────────────────────

        // GET /api/admin/categories
        Route::get('categories', [ParametrizationController::class, 'categoriesIndex'])
            ->name('categories.index');

        // POST /api/admin/categories
        Route::post('categories', [ParametrizationController::class, 'categoriesStore'])
            ->name('categories.store');

        // PUT /api/admin/categories/{category}
        Route::put('categories/{category}', [ParametrizationController::class, 'categoriesUpdate'])
            ->name('categories.update');

        // GET /api/admin/categories/{category}/subcategories
        Route::get('categories/{category}/subcategories', [ParametrizationController::class, 'subcategoriesIndex'])
            ->name('subcategories.index');

        // POST /api/admin/categories/{category}/subcategories
        Route::post('categories/{category}/subcategories', [ParametrizationController::class, 'subcategoriesStore'])
            ->name('subcategories.store');

        // PUT /api/admin/subcategories/{subcategory}
        Route::put('subcategories/{subcategory}', [ParametrizationController::class, 'subcategoriesUpdate'])
            ->name('subcategories.update');

        // ── Parametrização — Projectos ─────────────────────────

        // GET /api/admin/projects
        Route::get('projects', [ParametrizationController::class, 'projectsIndex'])
            ->name('projects.index');

        // POST /api/admin/projects
        Route::post('projects', [ParametrizationController::class, 'projectsStore'])
            ->name('projects.store');

        // PUT /api/admin/projects/{project}
        Route::put('projects/{project}', [ParametrizationController::class, 'projectsUpdate'])
            ->name('projects.update');

        // ── Parametrização — Tipos de Ocorrência ───────────────

        // GET /api/admin/occurrence-types
        Route::get('occurrence-types', [ParametrizationController::class, 'occurrenceTypesIndex'])
            ->name('occurrence-types.index');

        // POST /api/admin/occurrence-types
        Route::post('occurrence-types', [ParametrizationController::class, 'occurrenceTypesStore'])
            ->name('occurrence-types.store');

        // PUT /api/admin/occurrence-types/{occurrenceType}
        Route::put('occurrence-types/{occurrenceType}', [ParametrizationController::class, 'occurrenceTypesUpdate'])
            ->name('occurrence-types.update');
    });
});
