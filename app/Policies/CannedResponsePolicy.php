<?php

namespace App\Policies;

use App\Models\CannedResponse;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CannedResponsePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view the list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CannedResponse $cannedResponse): bool
    {
        return $user->hasRole('admin') || 
               $cannedResponse->is_public || 
               $cannedResponse->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CannedResponse $cannedResponse): bool
    {
        return $user->hasRole('admin') || $cannedResponse->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CannedResponse $cannedResponse): bool
    {
        return $user->hasRole('admin') || $cannedResponse->user_id === $user->id;
    }
} 