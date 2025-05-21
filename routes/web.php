<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => redirect('projects'));

Route::get('login', fn () => Inertia::render('Auth/Login'));
Route::get('register', fn () => Inertia::render('Auth/Register'));

Route::prefix('projects')->group(function () {
    Route::get('/', fn () => Inertia::render('Projects/Index'));
    Route::get('/{id}/tasks', [TaskController::class, 'index']);
});



