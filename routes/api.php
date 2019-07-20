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
Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('proposals', 'ProposalController@index');
Route::get('names', 'NamesController@index');
Route::get('names/random', 'NamesController@random');
Route::post('proposals', 'ProposalController@store');
Route::get('proposals/vote/{id}/{direction}', 'ProposalController@vote');
Route::get('proposals/{id}', 'ProposalController@show');
// Route::put('proposals/{project}', 'ProposalController@markAsCompleted');
