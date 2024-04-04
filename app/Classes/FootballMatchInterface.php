<?php

namespace App\Classes;

use App\Models\LeagueDetails;
use App\Models\Matches;
use App\Models\Teams;

interface FootballMatchInterface
{
    public static function getInstance(): FootballMatchInterface;

    public function setHomeTeam(Teams $team): FootballMatchInterface;

    public function setAwayTeam(Teams $team): FootballMatchInterface;

    public function setMatch(Matches $match): FootballMatchInterface;

    public function getMatch(): Matches;

    public function playMatch(): void;

    public function saveResult();
}
