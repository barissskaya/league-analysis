<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leagues;
use App\Models\Matches;
use App\Models\Teams;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    public function index(Request $request): JsonResponse
    {

        $league = Leagues::with('leagueDetails', 'leagueDetails.team')->orderBy('id', 'DESC')->get()->first();
        return response()->json($league);
    }

    public function showCurrentWeek(Request $request)
    {
        $league = Leagues::active()->get()->first();
        if ($league) {
            $matches = Matches::leagueById($league->id)->weekByNumber($league->current_week)
                ->with('homeTeam', 'awayTeam')->get();
            return response()->json($matches);
        }
        return response()->json([]); // league closed
    }

    public function resetLeague(Request $request)
    {
        $league = Leagues::active()->get()->first();
        if ($league) {
            $league->status = 0;
            $league->save();
        }
        return response()->json('League has been closed.');
    }


    public function predictions(Request $request)
    {
        $league = Leagues::with('leagueDetails', 'leagueDetails.team')->orderBy('id', 'DESC')->get()->first();
        if ($league->isFinished()) {
            // first team 100 percent
            $championTeamId = $league->leagueDetails->first()->team_id;
            $data = [];
            foreach ($league->leagueDetails as $detail) {
                $data[] = [
                    'name' => $detail->team->name,
                    'percent' => $championTeamId == $detail->team->id ? 100 : 0,
                ];
            }

        } else {

            $remainingMatches = $league->total_week - $league->current_week + 1;
            if ($remainingMatches <= 3) {
                // start analyze

                $possibleMaxWinPoints = $remainingMatches * 3;

                $teams = [];
                foreach ($league->leagueDetails as $detail) {
                    $teams[] = [
                        'name' => $detail->team->name,
                        'point' => $detail->points,
                        'possibleMaxPoint' => $detail->points + $possibleMaxWinPoints
                    ];
                }

                $firstTeamPoint = $teams[0]['point'];
                $totalMaxPoints = 0;
                foreach ($teams as $key => $team) {
                    if ($key != 0 && $team['possibleMaxPoint'] < $firstTeamPoint){
                        // This team can never become a champion
                        $teams[$key]['percent'] = 0;
                    }else{
                        $totalMaxPoints += $team['possibleMaxPoint'];
                    }
                }
                $data = [];
                foreach ($teams as $team) {
                    if (isset($team['percent'])){
                        $chance = $team['percent'];
                    }else{
                        $chance = ($team['possibleMaxPoint'] / $totalMaxPoints) * 100;
                        $chance = number_format($chance, 2, '.', '');
                        if (str_contains($chance, '.') && str_ends_with($chance, '0')) {
                            $chance = substr($chance, 0, -3);
                        }
                    }
                    $data[] = [
                        'name' => $team['name'],
                        'percent' => $chance,
                    ];
                }
                return response()->json($data);
            } else {

                $data = [];
                foreach ($league->leagueDetails as $detail) {
                    $data[] = [
                        'name' => $detail->team->name,
                        'percent' => 0
                    ];
                }
            }
        }
        return response()->json($data);
    }
}
