<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function delete(User $user, Subscription $subscription): bool
    {
        // 🚀 Super Admin pode tudo
        if ($user->isSuperAdmin()) {
            return true;
        }

        // 🧑‍💼 Manager só se não tiver faturas
        if ($user->isManager()) {
            return !$subscription->invoices()->exists();
        }

        return false;
    }
}