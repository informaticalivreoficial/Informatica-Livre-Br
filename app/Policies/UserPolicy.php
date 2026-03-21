<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function view(User $user, User $model): bool
    {
        // 🚀 SuperAdmin vê todos
        if ($user->isSuperAdmin()) {
            return true;
        }

        // 🧑‍💼 Manager vê todos da empresa (ou ajuste conforme regra)
        if ($user->isManager()) {
            return true;
        }

        // 👷 Employee vê apenas o próprio perfil
        return $user->id === $model->id;
    }

    public function update(User $user, User $model): bool
    {
        // 🚀 Super Admin pode tudo
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // 🧑‍💼 Manager
        if ($user->isManager()) {
            return
                (
                    $model->isEmployee()                    
                )
                || $user->id === $model->id;
        }

        // 👷 Employee → somente o próprio perfil
        if ($user->isEmployee()) {
            return $user->id === $model->id;
        }

        return false;
    }

    public function delete(User $user, User $model): bool
    {
        // 🚀 SuperAdmin pode deletar qualquer um (exceto ele mesmo)
        if ($user->isSuperAdmin()) {
            return $user->id !== $model->id;
        }       

        // 🧑‍💼 Manager deleta apenas colaboradores
        if ($user->isManager()) {
            return $model->isEmployee();
        }

        return false;
    }
}
