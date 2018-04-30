<?php

use Illuminate\Http\Request;
use App\Client;
use App\Http\Resources\Client as ClientResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', 'AuthController@register');

Route::post('auth/login', 'AuthController@login');
Route::group(['middleware' => 'jwt.auth'], function(){
  Route::get('auth/user', 'AuthController@user');
  Route::post('auth/logout', 'AuthController@logout');
});
Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'AuthController@refresh');
});


// Clients
Route::get('clients', 'Api\ClientController@index');
Route::get('clients/{id}', 'Api\ClientController@show');
Route::patch('clients/{id}', 'Api\ClientController@update');
Route::delete('clients/{id}', 'Api\ClientController@destroy');
Route::post('clients', 'Api\ClientController@store');

// Programs
Route::get('programs', 'Api\ProgramController@index');
Route::get('programs/{id}', 'Api\ProgramController@show');
Route::post('programs/{id}', 'Api\ProgramController@edit');