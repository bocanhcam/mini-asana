<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeleteProjectController extends Controller
{
    public function __invoke(int $id, Request $request)
    {
        $request->user()->findProjectOrFail($id)->delete();

        return response()->json();
    }
}
