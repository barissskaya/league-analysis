<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teams;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teams = Teams::all();
        return response()->json($teams);
    }
}
