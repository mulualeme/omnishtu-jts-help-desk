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
            $categories = KnowledgeBaseCategory::with(['articles' => function ($query) {
                $query->select('id', 'category_id', 'title', 'slug', 'is_published', 'updated_at')
                    ->orderBy('updated_at', 'desc');
            }])
            ->withCount('articles')
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                $category->articles = $category->articles->map(function ($article) {
                    return [
                        'id' => $article->id,
                        'title' => $article->title,
                        'slug' => $article->slug,
                        'is_published' => $article->is_published,
                        'updated_at' => $article->updated_at->format('M j, Y'),
                    ];
                });
                return $category;
            });

            return Inertia::render('KnowledgeBase/Index', [
                'categories' => $categories,
            ]);
        }

        // For public view, only show published articles
        $categories = KnowledgeBaseCategory::with(['articles' => function ($query) {
            $query->where('is_published', true)
                ->select('id', 'category_id', 'title', 'slug', 'updated_at')
                ->orderBy('updated_at', 'desc');
        }])
        ->withCount(['articles' => function ($query) {
            $query->where('is_published', true);
        }])
        ->orderBy('name')
        ->get()
        ->map(function ($category) {
            $category->articles = $category->articles->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'updated_at' => $article->updated_at->format('M j, Y'),
                ];
            });
            return $category;
        });

        return Inertia::render('KnowledgeBase/Index', [
            'categories' => $categories,
        ]);
    }

    public function create(Request $request)
    {
        $categories = KnowledgeBaseCategory::orderBy('name')->get();
        $selectedCategoryId = $request->query('category_id');

        return Inertia::render('KnowledgeBase/Articles/Create', [
            'categories' => $categories,
            'selectedCategoryId' => $selectedCategoryId,
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