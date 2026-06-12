<?php

namespace App\Enums;

/**
 * RoleEnum
 *
 * Define os perfis de acesso dos utilizadores internos do sistema.
 * Usado na coluna `role` da tabela `users`.
 *
 * - admin      → acesso total ao sistema
 * - gestor     → gere ocorrências da sua área geográfica, pode validar/rejeitar
 * - funcionario → pode submeter ocorrências mas NÃO pode validar/rejeitar
 * - observador → acesso só-leitura ao dashboard e ocorrências dos seus projectos/províncias
 */
enum RoleEnum: string
{
    case Admin      = 'admin';
    case Gestor     = 'gestor';
    case Funcionario = 'funcionario';
    case Observador = 'observador';

    /**
     * Retorna o label legível para humanos.
     */
    public function label(): string
    {
        return match($this) {
            RoleEnum::Admin       => 'Administrador',
            RoleEnum::Gestor      => 'Gestor',
            RoleEnum::Funcionario => 'Funcionário',
            RoleEnum::Observador  => 'Observador',
        };
    }

    /**
     * Retorna true se o role tem permissão para validar/rejeitar ocorrências.
     */
    public function canValidate(): bool
    {
        return match($this) {
            RoleEnum::Admin, RoleEnum::Gestor => true,
            default                           => false,
        };
    }

    /**
     * Retorna true se o role tem acesso ao painel de administração
     * (criação de utilizadores, parametrização total).
     */
    public function isAdmin(): bool
    {
        return $this === RoleEnum::Admin;
    }
}