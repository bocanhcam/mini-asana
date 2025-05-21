<?php

namespace App\Http\Controllers\Api;

use App\Constants\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;

class ListTaskController extends Controller
{
    public function __invoke(int $projectId, Request $request)
    {
        $tasks = $request->user()->findProjectOrFail($projectId)->tasks()->get()->groupBy('status');

        $grouped = collect(array_keys(TaskStatus::get()))->mapWithKeys(function ($status) use ($tasks) {
            return [$status => TaskResource::collection($tasks->get($status, collect()))];
        });

        return response()->json([
            'tasks' => $grouped,
        ]);
    }
}
