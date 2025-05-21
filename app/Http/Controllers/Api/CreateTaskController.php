<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateTaskRequest;
use Illuminate\Http\Request;

class CreateTaskController extends Controller
{
    public function __invoke(int $projectId, CreateTaskRequest $request)
    {
        $request->user()->projects()->findOrFail($projectId)->tasks()->create($request->validated());

        return response()->json();
    }
}
