<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teams;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $teams = Teams::all();
        return response()->json($teams);
    }
}
