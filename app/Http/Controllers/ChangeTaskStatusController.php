<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChangeTaskStatusController extends Controller
{
    public function __invoke(int $projectId, int $taskId, Request $request)
    {
        $request->user()->findProjectOrFail($projectId)->findTaskOrFail($taskId)->update([
            'status' => $request->status,
        ]);

        return response()->json();
    }
}
