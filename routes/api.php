<?php

use App\Http\Controllers\Api\FixtureController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LeagueController;
use App\Http\Controllers\Api\MatchController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->as('api.')->group(function () {
    Route::get('/teams', [HomeController::class, 'index'])->name('teams.all');
    Route::get('/fixtures', [FixtureController::class, 'index'])->name('fixture.all');
    Route::post('/fixtures/generate', [FixtureController::class, 'generate'])->name('fixture.generate');
    Route::get('/league', [LeagueController::class, 'index'])->name('league');
    Route::get('/league-predictions', [LeagueController::class, 'predictions'])->name('predictions');
    Route::get('/weekMatches', [LeagueController::class, 'showCurrentWeek'])->name('week.show');
    Route::get('/reset-league', [LeagueController::class, 'resetLeague'])->name('reset');
    Route::get('/play/next-week', [MatchController::class, 'playNextWeek'])->name('week.play.next');
    Route::get('/play/all-weeks', [MatchController::class, 'playAllWeeks'])->name('week.play.all');
});
