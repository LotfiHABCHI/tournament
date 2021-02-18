<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Repositories\Data;
use App\Repositories\Ranking;

class Repository
{
    function createDatabase(): void 
    {
    DB::unprepared(file_get_contents('database/build.sql'));
    }

    function insertTeam(array $team): int
    {
        return DB::table('teams')->insertGetId($team);
    
    }

    function insertMatch(array $match): int
    {
        return DB::table('matches')->insertGetId($match);
    }

    function teams(): array
    {
        $team= DB::table('teams')->orderBy('id','asc')->get()->toArray();
        return $team;
    }

    function matches(): array
    {
        $match= DB::table('matches')->orderBy('id','asc')->get()->toArray();
        return $match;
    }

    function fillDatabase(): void
    {
        $data =new Data();
        $teams=$data->teams();
        $matches=$data->matches();

        foreach($teams as $team){
            $this->insertTeam($team);
        }

        foreach($matches as $match){
            $this->insertMatch($match);
        }
    }

    function team($teamId) : array
    {
        $team= DB::table('teams')->where('id', $teamId)->get()->toArray();

        if(count($team)==0){
            throw new Exception('Équipe inconnue'); # ou l'appel d'une fonction ou méthode qui peut lever une exception
        }
       /* try{
        //$this->team(10000);    
        }catch (Exception $exception) {
            $message = $exception->getMessage();
            echo $exception->getMessage();
        }*/
      
        return  $team[0];
    }
    
    function match($matchId): array
    {
        $match= DB::table('matches')->where('id', $matchId)->get()->toArray();

        if(empty($match)){
            throw new Exception('Match inconnu');
        }
        /*try{
        $this->match(10000);    
        }catch (Exception $exception) {
            $message = $exception->getMessage();
            echo $exception->getMessage();
        }*/
      
        return  $match[0];
    }


    function updateRanking(): void
    {   
        DB::table('ranking')->delete();

        $ranking=new Ranking();
        $teams=$this->teams();
        $matches=$this->matches();
        $ranks=$ranking->SortedRanking($teams, $matches);
        foreach($ranks as $rank){
           DB::table('ranking')->insertGetId($rank);
        }
   
    }

    function sortedRanking(): array
    {
        
        $rows = DB::table('ranking')->join('teams', 'ranking.team_id', '=', 'teams.id')
                                        ->select('ranking.*','teams.name')
                                            ->orderBy('rank', 'asc') 
                                                ->get()->toArray();
        return $rows;
    }

    function teamMatches($teamId) : array
    {
        $matches = DB::table('matches')->join('teams as teams0', 'matches.team0', '=', 'teams0.id')
                                        ->join('teams as teams1', 'matches.team1', '=', 'teams1.id')
                        ->where('matches.team0', $teamId)
                            ->orWhere('matches.team1', $teamId)
                            ->orderBy('date')
                              ->get(['matches.*', 'teams0.name as name0', 'teams1.name as name1'])
                               ->toArray();
        return $matches;
    }

    function rankingRow($teamId) : array
    {
        $rows = DB::table('ranking')->join('teams', 'ranking.team_id', '=', 'teams.id')
                        ->select('ranking.*','teams.name')->where('ranking.team_id', $teamId)->get()->toArray();
        if(empty($rows)){
            throw new Exception('Équipe inconnue');
        }
        return $rows[0];

    }

}

