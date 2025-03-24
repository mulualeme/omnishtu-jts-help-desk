<?php

namespace App\Http\Controllers;

use App\Models\CannedResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CannedResponseController extends Controller
{
    public function index()
    {
        $responses = CannedResponse::query()
            ->when(!auth()->user()->hasRole('admin'), function ($query) {
                $query->where(function ($q) {
                    $q->where('user_id', auth()->id())
                        ->orWhere('is_public', true);
                });
            })
            ->with('user')
            ->orderBy('category')
            ->orderBy('title')
            ->paginate(10);

        return Inertia::render('CannedResponses/Index', [
            'responses' => $responses,
        ]);
    }

    public function create()
    {
        return Inertia::render('CannedResponses/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
            'is_public' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();

        CannedResponse::create($validated);

        return redirect()->route('canned-responses.index')
            ->with('success', 'Canned response created successfully.');
    }

    public function edit(CannedResponse $cannedResponse)
    {
        $this->authorize('update', $cannedResponse);

        return Inertia::render('CannedResponses/Edit', [
            'response' => $cannedResponse,
        ]);
    }

    public function update(Request $request, CannedResponse $cannedResponse)
    {
        $this->authorize('update', $cannedResponse);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
            'is_public' => 'boolean',
        ]);

        $cannedResponse->update($validated);

        return redirect()->route('canned-responses.index')
            ->with('success', 'Canned response updated successfully.');
    }

    public function destroy(CannedResponse $cannedResponse)
    {
        $this->authorize('delete', $cannedResponse);

        $cannedResponse->delete();

        return redirect()->route('canned-responses.index')
            ->with('success', 'Canned response deleted successfully.');
    }
} 