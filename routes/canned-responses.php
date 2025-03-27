<?php

use App\Http\Controllers\CannedResponseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('canned-responses', CannedResponseController::class);
}); 