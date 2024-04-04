<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeagueDetails;
use App\Models\Leagues;
use App\Models\Matches;
use App\Models\Teams;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FixtureController extends Controller
{
    public function index(Request $request): JsonResponse
    {

        $league = Leagues::active()->get()->first();
        $matches = Matches::select('id','week', 'home_team_id', 'away_team_id')
            ->with('homeTeam', 'awayTeam')
            ->leagueById($league->id)
            ->get();

        $weeks = [];
        foreach ($matches as $match){
            if (!isset($weeks[$match->week])){
                $weeks[$match->week]['week'] = $match->week;
            }
            $weeks[$match->week]['matches'][] = [
              'homeTeam' => $match->homeTeam->name,
              'awayTeam' => $match->awayTeam->name,
            ];
        }
        return response()->json($weeks);
    }

    private function disableActiveLeague()
    {

        $activeLeague = Leagues::active()->get()->first();
        if($activeLeague){
            $activeLeague->status = 0;
            $activeLeague->save();
        }
    }
    private function createWeeks($teams): array
    {
        $matches = [];
        foreach ($teams as $homeTeam){
            foreach ($teams as $awayTeam){
                if ($homeTeam->id != $awayTeam->id){
                    $matches[] = [
                        'home_team_id' => $homeTeam->id,
                        'away_team_id' => $awayTeam->id,
                    ];
                }
            }
        }

        $weeks = [];
        $i = 1;
        $week = 1;
        do{
            $firstMatch = $matches[0];
            $secondMatch = $matches[$i];

            $teamsOfWeek = [$firstMatch['home_team_id'], $firstMatch['away_team_id'], $secondMatch['home_team_id'], $secondMatch['away_team_id']];
            if (count(array_unique($teamsOfWeek)) == 4){
                $weeks[$week][] = $firstMatch;
                $weeks[$week][] = $secondMatch;
                unset($matches[0]);
                unset($matches[$i]);
                $matches = array_values($matches);
                $i = 1;
                $week++;
            }else{
                $i++;
            }
        }while(count($matches) > 0);

        return $weeks;
    }
    public function generate(Request $request): JsonResponse
    {
        $this->disableActiveLeague();
        $teams = Teams::all();
        $weeks = $this->createWeeks($teams);
        $totalWeek = array_key_last($weeks);

        $currentDate = date('Y-m-d');
        $league = new Leagues();
        $league->start = $currentDate;
        $league->end = date('Y-m-d', strtotime("$currentDate +$totalWeek week"));
        $league->current_week = 1;
        $league->total_week = $totalWeek;
        $league->save();

        $matchInsertData = [];
        foreach ($weeks as $week => $matchesInWeek){
            foreach ($matchesInWeek as $match){
                $matchInsertData[] = [
                    'week' => $week,
                    'league_id' => $league->id,
                    'home_team_id' => $match['home_team_id'],
                    'away_team_id' => $match['away_team_id'],
                ];
            }
        }
        Matches::insert($matchInsertData);

        $leagueDetailInsertData = [];
        foreach ($teams as $team){
            $leagueDetailInsertData[] = [
                'team_id' => $team->id,
                'league_id' => $league->id,
            ];
        }
        LeagueDetails::insert($leagueDetailInsertData);
        return response()->json('Fixture created.');
    }
}
