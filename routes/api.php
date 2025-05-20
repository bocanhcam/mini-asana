<?php

use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::post('login', \App\Http\Controllers\Api\PostLoginController::class);

Route::get('projects', \App\Http\Controllers\Api\ListProjectController::class)->middleware('auth:sanctum');

