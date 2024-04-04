<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('front.home');
})->name('home');

Route::get('/fixtures', function () {
    return view('front.fixtures');
})->name('fixtures');

Route::get('/simulation', function () {
    return view('front.simulation');
})->name('simulation');

require_once __DIR__ . '/api.php';
