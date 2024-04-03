<?php

namespace App\Classes;

use App\Models\Matches;
use App\Models\Teams;
use Exception;

class FootballMatch implements FootballMatchInterface
{
    private static $instance;
    protected $homeTeam;
    protected $awayTeam;
    protected $match;
    protected $homeGoal;
    protected $awayGoal;
    protected $isPlayed;

    private function __construct()
    {
    }

    public static function getInstance(): FootballMatchInterface
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        self::$instance->resetData();
        return self::$instance;
    }

    public function resetData()
    {
        $this->homeTeam = null;
        $this->awayTeam = null;
        $this->match = null;
        $this->homeGoal = null;
        $this->awayGoal = null;
        $this->isPlayed = null;
    }

    public function setHomeTeam(Teams $team): FootballMatchInterface
    {
        $this->homeTeam = $team;
        return $this;
    }

    public function setAwayTeam(Teams $team): FootballMatchInterface
    {
        $this->awayTeam = $team;
        return $this;
    }

    public function setMatch(Matches $match): FootballMatchInterface
    {
        $this->match = $match;
        $this->isPlayed = $this->match->played;
        $this->setHomeTeam($match->homeTeam);
        $this->setAwayTeam($match->awayTeam);
        return $this;
    }

    public function getMatch(): Matches
    {
        return $this->match;
    }

    /**
     * @throws Exception
     */
    public function playMatch(): void
    {
        $this->validData();
       if (!$this->isPlayed){
           $this->homeGoal = rand(0, 10);
           $this->awayGoal = rand(0, 10);
           $this->isPlayed = true;
       }
    }

    public function saveResult()
    {
        $this->match->home_goal = $this->homeGoal;
        $this->match->away_goal = $this->awayGoal;
        $this->match->played = 1;
        $this->match->save();
    }

    /**
     * @throws Exception
     */
    private function validData(): void
    {
        if (is_null($this->match)) {
            throw new Exception('There is no match data.');
        }
        if (is_null($this->homeTeam)) {
            throw new Exception('There is no home team data.');
        }
        if (is_null($this->awayTeam)) {
            throw new Exception('There is no away team data.');
        }
    }
}
