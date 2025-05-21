<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;

class ListProjectController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'projects' => ProjectResource::collection($request->user()->projects),
        ]);
    }
}
