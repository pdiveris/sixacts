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
    return view('static.welcome');
});

Route::get('/test', function () {
    phpinfo();
    return '';
});

Route::get('terms', 'StaticController@content')->name('terms');
Route::get('privacy', 'StaticController@content')->name('privacy');
Route::get('gdpr', 'StaticController@content')->name('gdpr');
Route::get('register', 'StaticController@userRegistration')->name('register');

Route::get('signin', 'StaticController@signin')->name('signin');
Route::get('login/twitter', 'Auth\LoginController@redirectToProvider')->name('signin_twitter');
Route::get('login/twitter/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('login/google', 'Auth\LoginController@redirectToProvider')->name('signin_google');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('twitter', function () {
    $token = '253052843-Pjd6WsvNs8xaPStSu6LwcaouPjW0c2y8H2IjFIdX';
    $secret = 'q13oYTMLTGGMCBnOi2aDbDbTluaX8gxs1vwJmd3r0znR6';
    $user = \Socialite::driver('twitter')
        ->userFromTokenAndSecret($token, $secret);
    dd($user);
    
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

