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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    phpinfo();
    return '';
});
// ['as' => 'admin.editIndustry', 'uses' => 'Industries@edit']
Route::get('gladdys', ['as'=>'gladdys', 'uses'=>'StaticController@content']);

Route::get('terms', ['as'=>'terms', 'uses'=>'StaticController@content']);
Route::get('privacy', ['as'=>'privacy', 'uses'=>'StaticController@content']);
Route::get('gdpr', ['as'=>'gdpr', 'uses'=>'StaticController@content']);
