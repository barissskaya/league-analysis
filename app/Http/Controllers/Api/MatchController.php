<?php

namespace App\Http\Controllers\Api;

use App\Classes\FootballMatch;
use App\Http\Controllers\Controller;
use App\Models\Leagues;
use App\Models\Matches;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MatchController extends Controller
{
    public function playNextWeek(Request $request)
    {

        DB::beginTransaction();
        try {
            $league = Leagues::active()->get()->first();
            $matches = Matches::leagueById($league->id)->notPlayed()->weekByNumber($league->current_week)
                ->with('homeTeam', 'awayTeam')->get();

            $playedMatches = [];
            foreach ($matches as $match) {
                $footballMatch = FootballMatch::getInstance();
                $footballMatch->setMatch($match)->playMatch();
                $footballMatch->saveResult();
                $playedMatches[] = $footballMatch->getMatch();
            }
            $this->refreshLeagueTable($playedMatches, $league);
            if ($league->current_week + 1 > $league->total_week){
                $league->status = 0;
                $league->save();
            }else{
                $league->current_week = $league->current_week + 1;
                $league->save();
            }
            DB::commit();
            return response()->json('Week ' . $league->current_week . ' matches have been completed.');
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json('Something went wrong', Response::HTTP_BAD_REQUEST);
        }
    }


    public function playAllWeeks(Request $request)
    {
        DB::beginTransaction();
        try {
            $league = Leagues::active()->get()->first();
            $matches = Matches::leagueById($league->id)->notPlayed()->with('homeTeam', 'awayTeam')->orderBy('week', 'ASC')->get();

            $playedMatches = [];
            foreach ($matches as $match) {
                $footballMatch = FootballMatch::getInstance();
                $footballMatch->setMatch($match)->playMatch();
                $footballMatch->saveResult();
                $playedMatches[] = $footballMatch->getMatch();
            }
            $this->refreshLeagueTable($playedMatches, $league);

            $league->current_week = $league->total_week;
            $league->status = 0;
            $league->save();
            DB::commit();
            return response()->json('All week\'s matches have been completed.');
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json('Something went wrong', Response::HTTP_BAD_REQUEST);
        }
    }

    private function refreshLeagueTable(array $playedMatches, Leagues $league)
    {
        $leagueDetails = $league->leagueDetails;
        foreach ($playedMatches as $match) {

            $homePoints = 0;
            $awayPoints = 0;

            if ($match->home_goal > $match->away_goal){
                $homePoints = 3;
            }else if ($match->home_goal < $match->away_goal){
                $awayPoints = 3;
            }else{
                $homePoints = 1;
                $awayPoints = 1;
            }

            foreach ($leagueDetails as $leagueDetail){
                if ($leagueDetail->team_id == $match->home_team_id){
                    $leagueDetail->played = $leagueDetail->played + 1;
                    $leagueDetail->gf = $leagueDetail->gf + $match->home_goal;
                    $leagueDetail->ga = $leagueDetail->ga + $match->away_goal;
                    $leagueDetail->gd = $leagueDetail->gf - $leagueDetail->ga;
                    $leagueDetail->points = $leagueDetail->points + $homePoints;

                    if ($homePoints == 3){
                        $leagueDetail->won = $leagueDetail->won + 1;
                    }else if ($homePoints == 1){
                        $leagueDetail->drawn = $leagueDetail->drawn + 1;
                    }else{
                        $leagueDetail->lost = $leagueDetail->lost + 1;
                    }
                    $leagueDetail->save();
                }

                if ($leagueDetail->team_id == $match->away_team_id){
                    $leagueDetail->played = $leagueDetail->played + 1;
                    $leagueDetail->gf = $leagueDetail->gf + $match->away_goal;
                    $leagueDetail->ga = $leagueDetail->ga + $match->home_goal;
                    $leagueDetail->gd = $leagueDetail->gf - $leagueDetail->ga;
                    $leagueDetail->points = $leagueDetail->points + $awayPoints;

                    if ($awayPoints == 3){
                        $leagueDetail->won = $leagueDetail->won + 1;
                    }else if ($awayPoints == 1){
                        $leagueDetail->drawn = $leagueDetail->drawn + 1;
                    }else{
                        $leagueDetail->lost = $leagueDetail->lost + 1;
                    }
                    $leagueDetail->save();
                }
            }
        }
    }
}
