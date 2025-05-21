<?php

use App\Http\Controllers\Api\CreateTaskController;
use App\Http\Controllers\Api\ListProjectController;
use App\Http\Controllers\Api\ListTaskController;
use App\Http\Controllers\Api\PostLoginController;
use App\Http\Controllers\ChangeTaskStatusController;
use App\Http\Controllers\CreateProjectController;
use App\Http\Controllers\DeleteProjectController;
use App\Http\Controllers\DeleteTaskController;
use App\Http\Controllers\EditProjectController;
use App\Http\Controllers\EditTaskController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('login', PostLoginController::class);
Route::post('register', RegisterController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('projects')->group(function () {
        Route::get('/', ListProjectController::class);
        Route::post('create', CreateProjectController::class);
        Route::put('{id}/edit', EditProjectController::class);
        Route::delete('{id}/delete', DeleteProjectController::class);

        Route::prefix('{projectId}/tasks')->group(function () {
            Route::get('/', ListTaskController::class);
            Route::post('create', CreateTaskController::class);
            Route::put('{id}/edit', EditTaskController::class);
            Route::put('{id}/changeStatus', ChangeTaskStatusController::class);
            Route::delete('{id}/delete', DeleteTaskController::class);
        });
    });
});

