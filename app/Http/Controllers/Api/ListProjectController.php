<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ListProjectController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'projects' => $request->user()->projects,
        ]);
    }
}
