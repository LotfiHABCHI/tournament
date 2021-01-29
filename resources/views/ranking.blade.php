

<!doctype html>
<html >
@extends('base')
    <head>
    @section('title')
    Classement
@endsection
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous" >
    </head>
    <body>
       <!-- <div class="border-bottom shadow-sm p-3 px-md-4 mb-3">
             <div class="container align-items-center d-flex flex-column flex-md-row">
                <h5 class="my-0 mr-md-auto font-weight-normal">Classement</h5>
                <nav class="my-2 my-md-0 mr-md-3">
                    <a class="p-2 text-dark" href="/">Classement</a>
                </nav>
                <a class="btn btn-outline-primary" href="#">Connexion</a> 
            </div>
        </div>-->
        <div class="container">
        @section('content')
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>N°<th>Équipe</th><th>MJ</th><th>G</th><th>N</th><th>P</th><th>BP</th><th>BC</th><th>DB</th><th>PTS</th></th>
                    </tr>
                </thead>
                <tbody>
                
                @foreach ($ranking as $match)
                <tr><td>{{ $match['rank'] }}</td><td><a href="{{route('ranking.show', ['team_id'=>$match['name']])}}">{{$match['name']}}</a></td><td>{{ $match['match_played_count'] }}</td><td>{{ $match['won_match_count'] }}</td><td>{{ $match['draw_match_count'] }}</td><td>{{ $match['lost_match_count'] }}</td><td>{{ $match['goal_for_count'] }}</td>
                <td>{{ $match['goal_against_count'] }}</td><td>{{ $match['goal_difference'] }}</td><td>{{ $match['points'] }}</td></th>
                 @endforeach
                </tbody>
            </table>
        @endsection

        </div>
    </body>
</html>

