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

Route::get('terms', 'StaticController@content');
Route::get('privacy', 'StaticController@content');
Route::get('gdpr', 'StaticController@content');

Route::get('login/twitter', 'Auth\LoginController@redirectToProvider');
Route::get('login/twitter/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('twitter', function () {


});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

