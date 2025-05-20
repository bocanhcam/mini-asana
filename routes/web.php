<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('login', fn () => Inertia::render('Login/Show'));
Route::get('projects', fn () => Inertia::render('Projects/Index'));

