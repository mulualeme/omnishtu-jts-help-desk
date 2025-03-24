<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBaseCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KnowledgeBaseCategoryController extends Controller
{
    public function index()
    {
        $categories = KnowledgeBaseCategory::withCount('articles')
            ->orderBy('name')
            ->paginate(10);

        return Inertia::render('KnowledgeBase/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return Inertia::render('KnowledgeBase/Categories/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:knowledge_base_categories',
            'description' => 'nullable|string',
        ]);

        KnowledgeBaseCategory::create($validated);

        return redirect()->route('knowledge-base.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(KnowledgeBaseCategory $category)
    {
        return Inertia::render('KnowledgeBase/Categories/Edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, KnowledgeBaseCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:knowledge_base_categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('knowledge-base.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(KnowledgeBaseCategory $category)
    {
        if ($category->articles()->exists()) {
            return back()->with('error', 'Cannot delete category with associated articles.');
        }

        $category->delete();

        return redirect()->route('knowledge-base.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
} 