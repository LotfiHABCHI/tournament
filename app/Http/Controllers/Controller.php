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


    public function createTeam()
    {
        return view('team_create');
    }

    public function storeTeam(Request $request)
    {
        $messages = [
            'team_name.required' => "Vous devez saisir un nom d'équipe.",
            'team_name.min' => "Le nom doit contenir au moins :min caractères.",
            'team_name.max' => "Le nom doit contenir au plus :max caractères.",
            'team_name.unique' => "Le nom d'équipe existe déjà."
          ];
        $rules = ['team_name' => ['required', 'min:3', 'max:20', 'unique:teams,name']];
        $validatedData = $request->validate($rules);
        return $request->input('team_name');
    }
    
}
