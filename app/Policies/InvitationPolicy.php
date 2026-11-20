<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;

class InvitationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage_users');
    }

    public function view(User $user, Invitation $invitation): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasPermissionTo('manage_users')
            && $user->company_id === $invitation->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage_users');
    }

    public function accept(User $user, Invitation $invitation): bool
    {
        return $invitation->isValid();
    }
}
