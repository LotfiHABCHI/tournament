<?php

namespace App\Http\Controllers;

use Exception;
use App\Repositories\Repository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function __construct(Repository $repository)
    {
    $this->repository = $repository;
    }


    public function showRanking()
    {
       $ranking = $this->repository->sortedRanking();
        return view('ranking', ['ranking' => $ranking]);
        

    }

    public function showTeam(int $teamId)
    {
        $team = $this->repository->teamMatches($teamId);
        $team1 = $this->repository->rankingRow($teamId);
        return view('team', ['team'=>$team,'team1'=>$team1] );
    }

    
}
