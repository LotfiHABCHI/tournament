<?php

namespace App\Http\Controllers;

use Exception;
use App\Repositories\Repository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Session;


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
        if (session()->has('user')){
            return view('team_create');
        }
        return redirect()->route('login');
    }

    public function storeTeam(Request $request)
    {
        if (session()->has('user')){
                $messages = [
                    'team_name.required' => "Vous devez saisir un nom d'équipe.",
                    'team_name.min' => "Le nom doit contenir au moins :min caractères.",
                    'team_name.max' => "Le nom doit contenir au plus :max caractères.",
                    'team_name.unique' => "Le nom d'équipe existe déjà."
                ];
                $rules = ['team_name' => ['required', 'min:3', 'max:20', 'unique:teams,name']];
                $validatedData = $request->validate($rules, $messages);
                
                
            try {
                // appels aux méthodes de l'objet de la classe Repository
                $teamId=$this->repository->insertTeam(['name'=>$validatedData['team_name']]);
                $this->repository->updateRanking();

            } catch (Exception $exception) {
                 return redirect()->route('teams.create')->withInput()->withErrors("Impossible de créer l'équipe.");
            }
                 return redirect()->route('teams.show', ['teamId' => $teamId]);
        }
        return redirect()->route('login');

    }

    public function createMatch()
    {
        if (session()->has('user')){
        $teams=$this->repository->teams();     
            return view('match_create',['teams'=>$teams]);
        }
        return redirect()->route('login');
        
    }

    public function storeMatch(Request $request) {
    
        if (session()->has('user')){
            
            $messages = [
                'team0.required' => 'Vous devez choisir une équipe.',
                'team0.exists' => 'Vous devez choisir une équipe qui existe.',
                'team1.required' => 'Vous devez choisir une équipe.',
                'team1.exists' => 'Vous devez choisir une équipe qui existe.',
                'date.required' => 'Vous devez choisir une date.',
                'date.date' => 'Vous devez choisir une date valide.',
                'time.required' => 'Vous devez choisir une heure.',
                'time.date_format' => 'Vous devez choisir une heure valide.',
                'score0.required' => 'Vous devez choisir un nombre de buts.',
                'score0.integer' => 'Vous devez choisir un nombre de buts entier.',
                'score0.between' => 'Vous devez choisir un nombre de buts entre 0 et 50.',
                'score1.required' => 'Vous devez choisir un nombre de buts.',
                'score1.integer' => 'Vous devez choisir un nombre de buts entier.',
                'score1.between' => 'Vous devez choisir un nombre de buts entre 0 et 50.',
            ];
            $rules = [
                'team0' => ['required', 'exists:teams,id'],
                'team1' => ['required', 'exists:teams,id'],
                'date' => ['required', 'date'],
                'time' => ['required', 'date_format:H:i'],
                'score0' => ['required', 'integer', 'between:0,50'],
                'score1' => ['required', 'integer', 'between:0,50']
            ];    
            
            $validatedData = $request->validate($rules, $messages);


            $date = $validatedData['date'];
            $time = $validatedData['time'];
            $datetime = "$date $time";
            
        
            // appels aux méthodes de l'objet de la classe Repository
            
            $team0=$validatedData['team0'];
            $team1=$validatedData['team1'];
            $score0=$validatedData['score0'];
            $score1=$validatedData['score1'];

            $newMatch=['team0'=>$team0, 'team1'=>$team1, 'score0'=>$score0, 'score1'=>$score1, 'date'=>$datetime];
            
            try {
                $matchId=$this->repository->insertMatch($newMatch);

                $this->repository->updateRanking();

                } catch (Exception $exception) {
                    return redirect()->route('matches.create')->withInput()->withErrors("Impossible de créer le match.");
                }

                return redirect()->route('ranking.show');

             }
        return redirect()->route('login');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request, Repository $repository)
    {
        $rules = [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required']
        ];
        $messages = [
            'email.required' => 'Vous devez saisir un e-mail.',
            'email.email' => 'Vous devez saisir un e-mail valide.',
            'email.exists' => "Cet utilisateur n'existe pas.",
            'password.required' => "Vous devez saisir un mot de passe.",
        ];
        $validatedData = $request->validate($rules, $messages);
        //$pass = Hash::make($validatedData['password']);
        $email = $validatedData['email'];
        try {
            $request->session()->put('user', $this->repository->getUser($email, $validatedData['password']));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors("Impossible de vous authentifier.");
        }
        
        return redirect()->route('ranking.show');
    }

    public function followTeam(int $teamId)
    {
        return redirect()->route('ranking.show')->cookie('followed_team', $teamId);
    }

    public function logout(Request $request) {
        $request->session()->forget('user');
        return redirect()->route('ranking.show');
    }
    

    public function deleteMatch(int $matchId)
    {
        if (session()->has('user')){
            $match=$this->repository->delete($matchId); 
            $this->repository->updateRanking();    
            return redirect()->route('ranking.show');
        }
        return redirect()->route('login');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request, Repository $repository)
    {
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required'], 
            'passwordConfirmation'=>['required'],
        ];
        $messages = [
            'email.required' => 'Vous devez saisir un e-mail.',
            'email.email' => 'Vous devez saisir un e-mail valide.',
            'password.required' => "Vous devez saisir un mot de passe.",
            'passwordConfirmation.required' => "Vous devez confirmer le mot de passe.",
        
        ];

        
        $validatedData = $request->validate($rules, $messages);
        //$pass = Hash::make($validatedData['password']);
        $email = $validatedData['email'];
        if($validatedData['password']==$validatedData['passwordConfirmation']){
            try {
                $this->repository->addUser($email, $validatedData['password']); 
                 $request->session()->put('user', $this->repository->getUser($email, $validatedData['password']));
                
            } catch (Exception $e) {
                return redirect()->back()->withInput()->withErrors("Impossible de vous inscrire.");
            }
        }else{
            return redirect()->back()->withInput()->withErrors("mots de passe différents.");
        }
        return redirect()->route('ranking.show');
    }

    public function showChangePasswordForm() 
    {
        return view('changepass');
    }

    public function changePassword(Request $request,Repository $repository)
    {
        $rules = [
            'email' => ['required', 'email', 'exists:users,email'],
            'old_password' => ['required'],
            'new_password' => ['required'],
            'new_passwordConfirm' => ['required'],
        ];
        $messages = [
            'email.required' => 'Vous devez saisir un e-mail.',
            'email.email' => 'Vous devez saisir un e-mail valide.',
            'email.exists' => "Cet utilisateur n'existe pas.",
            'old_password.required' => "Vous devez saisir votre ancien mot de passe.",
            'new_password.required' => "Vous devez saisir un nouveau mot de passe.",
            'new_passwordConfirm.required' => "Vous devez confirmer votre nouveau mot de passe.",
        ];
        $validatedData = $request->validate($rules, $messages);
        $email = $validatedData['email'];
        if($validatedData['new_password']==$validatedData['new_passwordConfirm']){
            try {

                $user=$this->repository->getUser($email, $validatedData['old_password']);

                $user=$this->repository->changePassword($email,$validatedData['old_password'], $validatedData['new_password'] );
            
            } catch (Exception $e) {
                return redirect()->back()->withInput()->withErrors("Impossible de changer le mot de passe.");
            }
        }else{
            return redirect()->back()->withInput()->withErrors("mots de passe différents.");
        }
        return redirect()->route('ranking.show');
    }
}