<?php

use Illuminate\Database\Seeder;
use App\Repositories\Repository;

class DatabaseSeeder extends Seeder 
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        touch('database/database.sqlite');
        $repository = new Repository();
        $repository->createDatabase();
        /*$repository->insertTeam(['name' => 'Marseille']);
        $repository->insertTeam(['name' => 'Paris']);
        $repository->insertTeam(['id'=> 7, 'name'=>'Strasbourg']);
        $repository->insertTeam(['id'=> 5, 'name'=>'Nice']);        
        $repository->insertTeam(['id'=> 19, 'name'=>'Nîmes']);
        
        $repository->insertMatch(['id' => 1, 'team0'=> 7, 'team1'=> 19, 'score0'=> 2, 'score1'=> 5, 'date'=>'2048-08-03 00:00:00']);*/
        
        $repository->fillDatabase();
        $repository->updateRanking();
        //$repository->ranking();
        
        
    }

    
    

    

}

