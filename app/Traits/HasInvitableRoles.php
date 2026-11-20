<?php

namespace App\Traits;

use App\Models\User;

trait HasInvitableRoles
{
    public function getInvitableRoles(User $user): array
    {
        if ($user->hasRole('super_admin')) {
            return ['manager', 'sales', 'member'];
        }

        if ($user->hasRole('admin')) {
            return ['sales'];
        }

        return [];
    }
}
