<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with(['leader:id,name', 'members:id,name'])
            ->withCount('members')
            ->latest()
            ->get()
            ->map(function ($team) {
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                    'description' => $team->description,
                    'leader' => $team->leader,
                    'members_count' => $team->members_count,
                    'created_at' => $team->created_at->format('M j, Y'),
                ];
            });

        return Inertia::render('Teams/Index', [
            'teams' => $teams
        ]);
    }

    public function create()
    {
        $users = User::role(['agent', 'admin'])->get(['id', 'name']);
        
        return Inertia::render('Teams/Create', [
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'leader_id' => 'required|exists:users,id',
            'members' => 'required|array|min:1',
            'members.*' => 'exists:users,id'
        ]);

        $team = Team::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'leader_id' => $validated['leader_id'],
        ]);

        // Add members to the team
        $team->members()->attach($validated['members'], ['role' => 'member']);
        
        // Add leader as admin
        if (!in_array($validated['leader_id'], $validated['members'])) {
            $team->members()->attach($validated['leader_id'], ['role' => 'admin']);
        }

        return redirect()->route('teams.index')
            ->with('success', 'Team created successfully.');
    }

    public function edit(Team $team)
    {
        $team->load(['leader:id,name', 'members:id,name']);
        $users = User::role(['agent', 'admin'])->get(['id', 'name']);

        return Inertia::render('Teams/Edit', [
            'team' => $team,
            'users' => $users
        ]);
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'leader_id' => 'required|exists:users,id',
            'members' => 'required|array|min:1',
            'members.*' => 'exists:users,id'
        ]);

        $team->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'leader_id' => $validated['leader_id'],
        ]);

        // Sync members
        $team->members()->sync(collect($validated['members'])->mapWithKeys(function ($id) {
            return [$id => ['role' => 'member']];
        }));

        // Ensure leader is admin
        if (!in_array($validated['leader_id'], $validated['members'])) {
            $team->members()->syncWithoutDetaching([
                $validated['leader_id'] => ['role' => 'admin']
            ]);
        }

        return redirect()->route('teams.index')
            ->with('success', 'Team updated successfully.');
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully.');
    }
} 