<?php

namespace App\Http\Controllers;

use App\Constants\TaskPriority;
use App\Constants\TaskStatus;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('Tasks/Index', [
            'projectId' => request()->id,
            'status' => TaskStatus::getAsArrayOptions(),
            'priorities' => TaskPriority::getAsArrayOptions(),
        ]);
    }
}
