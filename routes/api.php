<?php

use Illuminate\Http\Request;

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


Route::apiResources(['user' => 'API\UserController']);
Route::apiResources(['registro' => 'API\RegistroController']);

Route::get('profile','API\UserController@profile');
Route::get('findUser','API\UserController@search');
Route::put('profile','API\UserController@updateProfile');

Route::get('personas','API\UserController@personas');

Route::apiResources(['cargo' => 'API\CargoController']);
Route::apiResources(['horarios' => 'API\HorarioController']);
Route::get('horactiva','API\HorarioController@create');