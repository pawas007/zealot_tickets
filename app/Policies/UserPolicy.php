<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->role->name === 'admin';
    }


}
