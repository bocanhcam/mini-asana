<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeleteTaskController extends Controller
{
    public function __invoke(int $projectId, int $taskId, Request $request)
    {
        $request->user()->findProjectOrFail($projectId)->findTaskOrFail($taskId)->delete();

        return response()->json();
    }
}
