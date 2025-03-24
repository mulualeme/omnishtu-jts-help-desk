<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin') ||
               $user->id === $ticket->created_by ||
               $user->id === $ticket->assigned_to;
    }

    public function create(User $user): bool
    {
        return true; // Any authenticated user can create tickets
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin') ||
               $user->id === $ticket->created_by ||
               $user->id === $ticket->assigned_to;
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin') ||
               $user->id === $ticket->created_by;
    }
} 