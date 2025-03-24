<?php

namespace App\Policies;

use App\Models\CannedResponse;
use App\Models\User;

class CannedResponsePolicy
{
    public function update(User $user, CannedResponse $cannedResponse): bool
    {
        return $user->hasRole('admin') || $user->id === $cannedResponse->user_id;
    }

    public function delete(User $user, CannedResponse $cannedResponse): bool
    {
        return $user->hasRole('admin') || $user->id === $cannedResponse->user_id;
    }
} 