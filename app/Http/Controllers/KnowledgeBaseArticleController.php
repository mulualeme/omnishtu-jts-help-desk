<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBaseArticle;
use App\Models\KnowledgeBaseCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KnowledgeBaseArticleController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $articles = KnowledgeBaseArticle::with('category')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return Inertia::render('KnowledgeBase/Articles/Index', [
                'articles' => $articles,
            ]);
        }

        $categories = KnowledgeBaseCategory::with(['articles' => function ($query) {
            $query->where('is_published', true)
                ->orderBy('updated_at', 'desc');
        }])->get();

        return Inertia::render('KnowledgeBase/Index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $categories = KnowledgeBaseCategory::orderBy('name')->get();

        return Inertia::render('KnowledgeBase/Articles/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:knowledge_base_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $article = KnowledgeBaseArticle::create($validated);

        if ($article->is_published) {
            $article->update(['published_at' => now()]);
        }

        return redirect()->route('knowledge-base.articles.index')
            ->with('success', 'Article created successfully.');
    }

    public function edit(KnowledgeBaseArticle $article)
    {
        $categories = KnowledgeBaseCategory::orderBy('name')->get();

        return Inertia::render('KnowledgeBase/Articles/Edit', [
            'article' => $article,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, KnowledgeBaseArticle $article)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:knowledge_base_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $article->update($validated);

        if ($article->is_published && !$article->published_at) {
            $article->update(['published_at' => now()]);
        }

        return redirect()->route('knowledge-base.articles.index')
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(KnowledgeBaseArticle $article)
    {
        $article->delete();

        return redirect()->route('knowledge-base.articles.index')
            ->with('success', 'Article deleted successfully.');
    }

    public function show(KnowledgeBaseArticle $article)
    {
        return Inertia::render('KnowledgeBase/Articles/Show', [
            'article' => $article->load('category'),
        ]);
    }
} 