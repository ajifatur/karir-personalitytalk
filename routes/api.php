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

header('Access-Control-Allow-Origin: *');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/vacancy', 'API\VacancyController@index')->name('api.vacancy.index');
Route::get('/vacancy/{url}', 'API\VacancyController@detail')->name('api.vacancy.detail');
Route::get('/selection/detail', 'SelectionController@detail')->name('api.selection.detail');

\Ajifatur\Helpers\RouteExt::api();