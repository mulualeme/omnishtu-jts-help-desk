<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Ticket::with(['creator', 'assignedAgent']);

        if ($user->hasRole('customer')) {
            $query->where('created_by', $user->id);
        } elseif ($user->hasRole('agent')) {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id);
            });
        }

        $tickets = $query->latest()->get();

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets
        ]);
    }

    public function create()
    {
        return Inertia::render('Tickets/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $ticket = Ticket::create([
            ...$validated,
            'created_by' => auth()->id(),
            'status' => 'open',
            'priority' => 'medium',
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('message', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);

        $ticket->load(['creator', 'assignedAgent']);

        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket
        ]);
    }

    public function edit(Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        $agents = User::role('agent')->get();
        $ticket->load(['creator', 'assignedAgent']);

        return Inertia::render('Tickets/Edit', [
            'ticket' => $ticket,
            'agents' => $agents
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,in_progress,closed',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket)
            ->with('message', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        Gate::authorize('delete', $ticket);

        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('message', 'Ticket deleted successfully.');
    }
} 