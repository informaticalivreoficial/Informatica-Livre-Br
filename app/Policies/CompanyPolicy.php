<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    /**
     * Quem pode criar empresas
     */
    public function create(User $user)
    {
        // Apenas SuperAdmin pode criar empresas
        return $user->isSuperAdmin();
    }

    /**
     * Quem pode ver a lista de empresas
     */
    public function viewAny(User $user)
    {
        // SuperAdmin pode ver todas as empresas
        return $user->isSuperAdmin();
    }

    /**
     * Quem pode visualizar uma empresa específica
     */
    public function view(User $user, Company $company)
    {
        // SuperAdmin ve TODAS as empresas
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Quem pode editar uma empresa
     */
    public function update(User $user, Company $company)
    {
        // SuperAdmin pode editar TODAS as empresas
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Employee NÃO pode editar empresas
        return false;
    }

    /**
     * Quem pode deletar uma empresa
     */
    public function delete(User $user, Company $company)
    {
        // Apenas SuperAdmin pode deletar empresas
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Ninguém mais pode deletar
        return false;
    }
}
