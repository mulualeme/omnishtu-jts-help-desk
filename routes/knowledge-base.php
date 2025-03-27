<?php

use App\Http\Controllers\KnowledgeBaseCategoryController;
use App\Http\Controllers\KnowledgeBaseArticleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // Categories
    Route::resource('knowledge-base/categories', KnowledgeBaseCategoryController::class)
        ->names('knowledge-base.categories');

    // Articles
    Route::resource('knowledge-base/articles', KnowledgeBaseArticleController::class)
        ->names('knowledge-base.articles');
});

// Public Knowledge Base Routes
Route::get('knowledge-base', [KnowledgeBaseArticleController::class, 'index'])
    ->name('knowledge-base.index');
Route::get('knowledge-base/{article:slug}', [KnowledgeBaseArticleController::class, 'show'])
    ->name('knowledge-base.show'); 