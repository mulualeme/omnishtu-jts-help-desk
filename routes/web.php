<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\KnowledgeBaseCategoryController;
use App\Http\Controllers\KnowledgeBaseArticleController;
use App\Http\Controllers\CannedResponseController;
use App\Http\Controllers\TeamController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Categories
    Route::resource('knowledge-base/categories', KnowledgeBaseCategoryController::class)
        ->names('knowledge-base.categories');

    // Articles
    Route::resource('knowledge-base/articles', KnowledgeBaseArticleController::class)
        ->names('knowledge-base.articles');

    // Canned Responses
    Route::resource('canned-responses', CannedResponseController::class);

    // Teams routes
    Route::resource('teams', TeamController::class);
});

// Public Knowledge Base Routes
Route::get('knowledge-base', [KnowledgeBaseArticleController::class, 'index'])
    ->name('knowledge-base.index');
Route::get('knowledge-base/{article:slug}', [KnowledgeBaseArticleController::class, 'show'])
    ->name('knowledge-base.show');

require __DIR__.'/auth.php';
require __DIR__.'/ticket.php';
require __DIR__.'/agent.php';
require __DIR__.'/customer.php';
