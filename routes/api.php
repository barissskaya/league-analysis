<?php

use App\Http\Controllers\Api\HomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->as('api.')->group(function () {
    Route::get('/teams', [HomeController::class, 'index'])->name('teams.all');
});
