<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

Route::get('/', [Controller::class, 'showRanking'])->name('ranking.show');

//Routes des équipes
Route::get('/teams/{teamId}', [Controller::class, 'showTeam'])->where('teamId', '[0-9]+')->name('teams.show');
Route::get('/teams/create', [Controller::class, 'createTeam'])->name('teams.create');
Route::post('/teams', [Controller::class, 'storeTeam'])->name('teams.store');
Route::get('/teams/{teamId}/follow', [Controller::class, 'followTeam'])->where('teamId', '[0-9]+')->name('teams.follow');

//Routes des matchs
Route::get('/matches/create', [Controller::class, 'createMatch'])->name('matches.create');
Route::post('/matches', [Controller::class, 'storeMatch'])->name('matches.store');
Route::post('/matches/{matchId}/delete', [Controller::class, 'deleteMatch'])->where('matchId', '[0-9]+')->name('matches.delete');


//Routes de utilisateurs
Route::get('/login', [Controller::class, 'showLoginForm'])->name('login');
Route::post('/login', [Controller::class, 'login'])->name('login.post');

Route::post('/logout', [Controller::class, 'logout'])->name('logout');

Route::get('/register', [Controller::class, 'showRegisterForm'])->name('register');
Route::post('/register', [Controller::class, 'register'])->name('register.post');

Route::get('/changepass', [Controller::class, 'showChangePasswordForm'])->name('changepass');
Route::post('/changepass', [ Controller::class, 'changePassword'])->name('changepass.post');










