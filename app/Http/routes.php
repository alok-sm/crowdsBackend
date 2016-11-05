<?php
use App\Domain;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::post('/api/answers', 'AnswerController@store');
Route::post('/api/users', 'ClientController@store');
Route::get('/api/tasks','TaskController@assign');
Route::get('/api/rank', 'TaskController@rank');

Route::get('/api/br/get', 'BrowserState@show');
Route::post('/api/br/put', 'BrowserState@store');

Route::post('/api/user/confidence', 'UserRank@store');

