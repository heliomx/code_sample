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

Route::post('auth/login', 'AuthController@login');

// Protected Calls
Route::group(['middleware' => 'jwt.auth'], function(){
  // Auth
  Route::get('auth/user', 'AuthController@user');
  Route::post('auth/logout', 'AuthController@logout');


  // Users
  Route::get('users', 'AuthController@indexUser');
  Route::get('users/{id}', 'AuthController@show');
  Route::post('users', 'AuthController@register');
  Route::post('users/{id}', 'AuthController@update');
  Route::delete('users/{id}', 'AuthController@destroy');


});
Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'AuthController@refresh');
});

// Users
Route::post('forgotPassword', 'AuthController@forgotPassword');
Route::post('updatePassword', 'AuthController@updateForgottenPassword');
Route::post('userByToken', 'AuthController@userByToken');


// Clients
Route::get('clients', 'Api\ClientController@index');
Route::get('clients/{id}', 'Api\ClientController@show');
Route::patch('clients/{id}', 'Api\ClientController@update');
Route::delete('clients/{id}', 'Api\ClientController@destroy');
Route::post('clients', 'Api\ClientController@store');

// Programs
Route::get('programs', 'Api\ProgramController@index');
Route::get('programs/{id}', 'Api\ProgramController@show');
Route::delete('programs/{id}', 'Api\ProgramController@destroy');
Route::post('programs/{id}', 'Api\ProgramController@edit');
Route::post('programs', 'Api\ProgramController@store');
Route::get('programs/downloads/{id}', 'Api\ProgramController@download');

//Packages
Route::get('packages/{id}', 'Api\PackageController@show');

//TUS
Route::post('uploadjobs', '\OneOffTech\TusUpload\Http\Controllers\TusUploadQueueController@store');

// Content
Route::post('contents/images/{docType}', 'Api\ContentController@storeImage');
Route::post('contents/{docType}', 'Api\ContentController@store');
Route::get('contents/{docType}', 'Api\ContentController@show');

// Contact
Route::get('contacts', 'Api\ContactController@index');
Route::get('contacts/{id}', 'Api\ContactController@show');
Route::post('contacts', 'Api\ContactController@store');
Route::post('contacts/{id}', 'Api\ContactController@update');

