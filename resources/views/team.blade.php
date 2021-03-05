<!doctype html>
<html>
@extends('base')
    <head>
     
    @section('title')
        Matchs de l'équipe {{$team1['name']}}
    @endsection
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous" >
    </head>
    <body>
  
   <div class="container">
   @section('content')
   <a class="btn btn-primary" href="{{ route('teams.follow', ['teamId'=>$team1['team_id']]) }}">Suivre</a><br><br>

   <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                    <th>N°<th>Équipe<th>MJ</th><th>G</th><th>N</th><th>P</th><th>BP</th><th>BC</th><th>DB</th><th>PTS</th></th></tr>
                </thead>
                <tbody>
                <tr><td>{{ $team1['rank'] }}</td><td> <a href="{{route('teams.show', ['teamId'=>$team1['team_id']])}}">{{$team1['name']}}</a></td>
                <td>{{ $team1['match_played_count'] }}</td><td>{{ $team1['won_match_count'] }}</td><td>{{ $team1['draw_match_count'] }}</td>
                <td>{{ $team1['lost_match_count'] }}</td><td>{{ $team1['goal_for_count'] }}</td>
                <td>{{ $team1['goal_against_count'] }}</td><td>{{ $team1['goal_difference'] }}</td>
                <td>{{ $team1['points'] }}</td></th>
                </tbody>
</table>
       <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                   <!-- <th><td>Date</td><td>team0</td><td>score0</td>  <td>score1</td><td>team1</td></th>  -->                   </tr>
                </thead>
                <tbody>
                @foreach ($team as $match)
                
                <tr><td>  </td><td>{{ $match['date'] }}</td>
                <td><a href="{{route('teams.show', ['teamId'=>$match['team0']])}}">
                {{ $match['name0'] }}</a></td><td>{{ $match['score0'] }}</td>  
                <td>{{ $match['score1'] }}</td><td><a href="{{route('teams.show', ['teamId'=>$match['team1']])}}">
                {{ $match['name1'] }}</a></td> <td> <a  type="submit" class="btn btn-outline-primary">Supprimer le match</a>
</td></th>

            @endforeach
                </tbody>
            </table>
            @endsection

        </div>

            
           
    </body>
</html>

