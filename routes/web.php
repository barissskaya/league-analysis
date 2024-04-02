<?php

use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('front.home');
});

require_once __DIR__ . '/api.php';
