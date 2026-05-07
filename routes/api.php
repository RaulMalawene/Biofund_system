<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Public\PublicOccurrenceController;
use App\Http\Controllers\Api\Gestor\GestorOccurrenceController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\AdminStatisticsController;
use App\Http\Controllers\Api\Admin\ParametrizationController;
use App\Http\Controllers\Api\AttachmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MDR — API Routes
|--------------------------------------------------------------------------
|
| Organização:
|   1. Rotas públicas        → sem autenticação
|   2. Rotas de autenticação → login, logout, password
|   3. Rotas protegidas      → requerem token Sanctum
|      3.1 Sessão activa     → me, logout, change-password
|      3.2 Ocorrências       → todos os autenticados (visibilidade por role)
|      3.3 Status / Assign   → admin + gestor apenas
|      3.4 Admin exclusivo   → delete, restore, users, stats
|      3.5 Parametrização    → admin (escrita) + gestor (leitura)
|
*/

// ═══════════════════════════════════════════════════════════════
// 1. ROTAS PÚBLICAS (sem autenticação)
// ═══════════════════════════════════════════════════════════════

Route::prefix('public')->name('public.')->group(function () {

    Route::get('form-data', [PublicOccurrenceController::class, 'formData'])
        ->name('form-data');

    Route::get('provinces/{province}/districts', [PublicOccurrenceController::class, 'districtsByProvince'])
        ->name('districts');

    Route::post('occurrences', [PublicOccurrenceController::class, 'store'])
        ->name('occurrences.store');

    Route::get('occurrences/track/{code}', [PublicOccurrenceController::class, 'track'])
        ->name('occurrences.track');
});

// ═══════════════════════════════════════════════════════════════
// 2. AUTENTICAÇÃO (sem autenticação prévia)
// ═══════════════════════════════════════════════════════════════

Route::prefix('auth')->name('auth.')->group(function () {

    Route::post('login', [LoginController::class, 'login'])
        ->name('login');

    Route::post('forgot-password', [PasswordResetController::class, 'forgotPassword'])
        ->name('forgot-password');

    Route::post('reset-password', [PasswordResetController::class, 'resetPassword'])
        ->name('reset-password');
});

// ═══════════════════════════════════════════════════════════════
// 3. ROTAS PROTEGIDAS (requerem token Sanctum)
// ═══════════════════════════════════════════════════════════════

Route::middleware('auth:sanctum')->group(function () {

    // ── 3.1 SESSÃO ACTIVA (qualquer utilizador autenticado) ─────

    Route::prefix('auth')->name('auth.')->group(function () {
        Route::get('me', [LoginController::class, 'me'])
            ->name('me');
        Route::post('logout', [LoginController::class, 'logout'])
            ->name('logout');
        Route::post('change-password', [PasswordResetController::class, 'changePassword'])
            ->name('change-password');
    });

    // ── 3.2 OCORRÊNCIAS — leitura e registo (admin + gestor + funcionario) ──

    Route::prefix('occurrences')->name('occurrences.')->group(function () {

        // Listar (visibilidade filtrada por role no controller)
        Route::get('/', [GestorOccurrenceController::class, 'index'])
            ->name('index');

        // Detalhe (visibilidade filtrada por role no controller)
        Route::get('{occurrence}', [GestorOccurrenceController::class, 'show'])
            ->name('show');

        // Registar nova ocorrência
        Route::post('/', [GestorOccurrenceController::class, 'store'])
            ->name('store');

        // Download de anexo — acesso controlado por role
        Route::get('{occurrence}/attachments/{attachment}', [AttachmentController::class, 'download'])
            ->name('attachments.download');
    });

    // ── 3.3 OCORRÊNCIAS — acções (admin + gestor apenas) ────────

    Route::prefix('occurrences')->name('occurrences.')
        ->middleware('role:admin,gestor')
        ->group(function () {

        // Mudar estado — justificação obrigatória ao resolver ou rejeitar
        Route::patch('{occurrence}/status', [GestorOccurrenceController::class, 'updateStatus'])
            ->name('update-status');

        // Atribuir a gestor ou escalar para admin
        Route::patch('{occurrence}/assign', [GestorOccurrenceController::class, 'assign'])
            ->name('assign');
    });

    // ── 3.4 OCORRÊNCIAS — acções exclusivas do admin ─────────────

    Route::prefix('occurrences')->name('occurrences.')
        ->middleware('role:admin')
        ->group(function () {

        Route::get('deleted', [GestorOccurrenceController::class, 'deleted'])
            ->name('deleted');

        Route::delete('{occurrence}', [GestorOccurrenceController::class, 'destroy'])
            ->name('destroy');
    });

    // ── 3.5 ADMIN — UTILIZADORES (apenas admin) ─────────────────

    Route::prefix('admin')->name('admin.')
        ->middleware('role:admin')
        ->group(function () {

        Route::get('users', [AdminUserController::class, 'index'])
            ->name('users.index');
        Route::get('users/gestores', [AdminUserController::class, 'gestores'])
            ->name('users.gestores');
        Route::get('users/{user}', [AdminUserController::class, 'show'])
            ->name('users.show');
        Route::post('users', [AdminUserController::class, 'store'])
            ->name('users.store');
        Route::put('users/{user}', [AdminUserController::class, 'update'])
            ->name('users.update');
        Route::patch('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])
            ->name('users.toggle-status');
    });

    // Gestores também podem listar para atribuição
    Route::get('admin/users/gestores', [AdminUserController::class, 'gestores'])
        ->middleware('role:admin,gestor')
        ->name('admin.users.gestores.gestor');

    // ── 3.6 ESTATÍSTICAS (admin + gestor) ───────────────────────

    Route::prefix('admin')->name('admin.')
        ->middleware('role:admin,gestor')
        ->group(function () {

        Route::get('statistics/dashboard', [AdminStatisticsController::class, 'dashboard'])
            ->name('statistics.dashboard');
        Route::get('statistics/report', [AdminStatisticsController::class, 'report'])
            ->name('statistics.report');
    });

    // ── 3.7 PARAMETRIZAÇÃO — leitura (admin + gestor) ───────────

    Route::prefix('admin')->name('admin.')
        ->middleware('role:admin,gestor')
        ->group(function () {

        Route::get('categories', [ParametrizationController::class, 'categoriesIndex'])
            ->name('categories.index');
        Route::get('categories/{category}/subcategories', [ParametrizationController::class, 'subcategoriesIndex'])
            ->name('subcategories.index');
        Route::get('projects', [ParametrizationController::class, 'projectsIndex'])
            ->name('projects.index');
        Route::get('occurrence-types', [ParametrizationController::class, 'occurrenceTypesIndex'])
            ->name('occurrence-types.index');
    });

    // ── 3.8 PARAMETRIZAÇÃO — escrita (apenas admin) ─────────────

    Route::prefix('admin')->name('admin.')
        ->middleware('role:admin')
        ->group(function () {

        Route::post('categories', [ParametrizationController::class, 'categoriesStore'])
            ->name('categories.store');
        Route::put('categories/{category}', [ParametrizationController::class, 'categoriesUpdate'])
            ->name('categories.update');

        Route::post('categories/{category}/subcategories', [ParametrizationController::class, 'subcategoriesStore'])
            ->name('subcategories.store');
        Route::put('subcategories/{subcategory}', [ParametrizationController::class, 'subcategoriesUpdate'])
            ->name('subcategories.update');

        Route::post('projects', [ParametrizationController::class, 'projectsStore'])
            ->name('projects.store');
        Route::put('projects/{project}', [ParametrizationController::class, 'projectsUpdate'])
            ->name('projects.update');

        Route::post('occurrence-types', [ParametrizationController::class, 'occurrenceTypesStore'])
            ->name('occurrence-types.store');
        Route::put('occurrence-types/{occurrenceType}', [ParametrizationController::class, 'occurrenceTypesUpdate'])
            ->name('occurrence-types.update');
    });
});