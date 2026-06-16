<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Public\PublicOccurrenceController;
use App\Http\Controllers\Api\Gestor\GestorOccurrenceController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\AdminStatisticsController;
use App\Http\Controllers\Api\Admin\ParametrizationController;
use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MDR - API Routes
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

    Route::get('occurrences/{code}/attachments/{attachmentId}', [PublicOccurrenceController::class, 'downloadAttachment'])
        ->name('occurrences.attachment.download');
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
        Route::post('profile', [LoginController::class, 'updateProfile'])
            ->name('profile');
        Route::post('change-password', [PasswordResetController::class, 'changePassword'])
            ->name('change-password');
    });

    // ── 3.2 OCORRÊNCIAS - leitura e registo (admin + gestor + funcionario) ──

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

        // Download de anexo - acesso controlado por role
        Route::get('{occurrence}/attachments/{attachment}', [AttachmentController::class, 'download'])
            ->name('attachments.download');
    });

    // ── 3.3 OCORRÊNCIAS - acções (admin + gestor apenas) ────────

    Route::prefix('occurrences')->name('occurrences.')
        ->middleware('role:admin,gestor')
        ->group(function () {

        // Mudar estado - comentário obrigatório em nao_validado e resolvido
        Route::patch('{occurrence}/status', [GestorOccurrenceController::class, 'updateStatus'])
            ->name('update-status');

        // Adicionar comentário sem alterar o estado (disponível em todos os estados)
        Route::post('{occurrence}/comment', [GestorOccurrenceController::class, 'addComment'])
            ->name('add-comment');

        // Atribuir a gestor ou escalar para admin
        Route::patch('{occurrence}/assign', [GestorOccurrenceController::class, 'assign'])
            ->name('assign');

        // Actualizar classificação (projecto e categoria)
        Route::patch('{occurrence}/classification', [GestorOccurrenceController::class, 'updateClassification'])
            ->name('update-classification');
    });

    // ── 3.4 OCORRÊNCIAS - acções exclusivas do admin ─────────────

    Route::prefix('occurrences')->name('occurrences.')
        ->middleware('role:admin')
        ->group(function () {

        Route::get('deleted', [GestorOccurrenceController::class, 'deleted'])
            ->name('deleted');

        Route::delete('{occurrence}', [GestorOccurrenceController::class, 'destroy'])
            ->name('destroy');
    });

    // ── 3.5 NOTIFICAÇÕES (qualquer utilizador autenticado) ──────

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',              [NotificationController::class, 'index'])    ->name('index');
        Route::get('count',         [NotificationController::class, 'count'])    ->name('count');
        Route::patch('{notification}/read', [NotificationController::class, 'markRead']) ->name('mark-read');
        Route::post('read-all',     [NotificationController::class, 'markAllRead']) ->name('mark-all-read');
    });

    // ── 3.6 ADMIN - UTILIZADORES (apenas admin) ─────────────────

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
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])
            ->name('users.destroy');
    });

    // Gestores também podem listar para atribuição
    Route::get('admin/users/gestores', [AdminUserController::class, 'gestores'])
        ->middleware('role:admin,gestor')
        ->name('admin.users.gestores.gestor');

    // ── 3.7 ESTATÍSTICAS (admin + gestor + observador) ──────────

    // Dashboard: também acessível ao observador (apenas leitura, dados do seu escopo)
    Route::prefix('admin')->name('admin.')
        ->middleware('role:admin,gestor,observador')
        ->group(function () {

        Route::get('statistics/dashboard', [AdminStatisticsController::class, 'dashboard'])
            ->name('statistics.dashboard');
    });

    // Relatório: apenas admin + gestor
    Route::prefix('admin')->name('admin.')
        ->middleware('role:admin,gestor')
        ->group(function () {

        Route::get('statistics/report', [AdminStatisticsController::class, 'report'])
            ->name('statistics.report');

        Route::get('statistics/report/periodic', [AdminStatisticsController::class, 'periodicReport'])
            ->name('statistics.report.periodic');
    });

    // ── 3.8 PARAMETRIZAÇÃO - leitura (admin + gestor) ───────────

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

    // ── 3.9 PARAMETRIZAÇÃO - escrita (apenas admin) ─────────────

    Route::prefix('admin')->name('admin.')
        ->middleware('role:admin')
        ->group(function () {

        Route::post('categories', [ParametrizationController::class, 'categoriesStore'])
            ->name('categories.store');
        Route::put('categories/{category}', [ParametrizationController::class, 'categoriesUpdate'])
            ->name('categories.update');
        Route::delete('categories/{category}', [ParametrizationController::class, 'categoriesDestroy'])
            ->name('categories.destroy');

        Route::post('categories/{category}/subcategories', [ParametrizationController::class, 'subcategoriesStore'])
            ->name('subcategories.store');
        Route::put('subcategories/{subcategory}', [ParametrizationController::class, 'subcategoriesUpdate'])
            ->name('subcategories.update');

        Route::post('projects', [ParametrizationController::class, 'projectsStore'])
            ->name('projects.store');
        Route::put('projects/{project}', [ParametrizationController::class, 'projectsUpdate'])
            ->name('projects.update');
        Route::delete('projects/{project}', [ParametrizationController::class, 'projectsDestroy'])
            ->name('projects.destroy');

        Route::post('occurrence-types', [ParametrizationController::class, 'occurrenceTypesStore'])
            ->name('occurrence-types.store');
        Route::put('occurrence-types/{occurrenceType}', [ParametrizationController::class, 'occurrenceTypesUpdate'])
            ->name('occurrence-types.update');
    });
});