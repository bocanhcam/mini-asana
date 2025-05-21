<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditTaskRequest;

class EditTaskController extends Controller
{
    public function __invoke(int $projectId, int $taskId, EditTaskRequest $request)
    {
        $request->user()->findProjectOrFail($projectId)->findTaskOrFail($taskId)->update($request->validated());

        return response()->json();
    }
}
