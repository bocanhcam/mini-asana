<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditProjectRequest;

class EditProjectController extends Controller
{
    public function __invoke(int $id, EditProjectRequest $request)
    {
        $project = $request->user()->findProjectOrFail($id);

        $project->update($request->validated());

        return response()->json();
    }
}
