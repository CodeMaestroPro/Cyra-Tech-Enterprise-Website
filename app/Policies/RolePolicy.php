<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('roles.view');
    }

    public function manage(User $user): bool
    {
        return $user->hasPermission('roles.manage');
    }
}
