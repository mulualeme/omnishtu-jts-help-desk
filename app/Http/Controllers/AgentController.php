<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class AgentController extends Controller
{
    public function index()
    {
        $agents = User::role('agent')
            ->with('roles')
            ->get();

        return Inertia::render('Agents/Index', [
            'agents' => $agents
        ]);
    }

    public function create()
    {
        return Inertia::render('Agents/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('agent');

        return redirect()->route('agents.index')
            ->with('message', 'Agent created successfully');
    }

    public function edit(User $agent)
    {
        return Inertia::render('Agents/Edit', [
            'agent' => $agent
        ]);
    }

    public function update(Request $request, User $agent)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $agent->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $agent->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $agent->update([
                'password' => Hash::make($validated['password'])
            ]);
        }

        return redirect()->route('agents.index')
            ->with('message', 'Agent updated successfully');
    }

    public function destroy(User $agent)
    {
        $agent->delete();

        return redirect()->route('agents.index')
            ->with('message', 'Agent deleted successfully');
    }
} 