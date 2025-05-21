<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;

class CreateProjectController extends Controller
{
    public function __invoke(CreateProjectRequest $request)
    {
        $request->user()->projects()->create($request->validated());

        return response()->json();
    }
}
