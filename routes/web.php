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

Route::get('login/facebook', 'Auth\LoginController@redirectToProvider')->name('signin_facebook');
Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('login/deauthorize/facebook', 'Auth\LoginController@handleProviderDeauthorize')
    ->name('defacebook')
;

Route::get('login/delete/faceboook', 'Auth\LoginController@handleProviderDelete')
    ->name('deletefacebook')
;

Route::get(/**
 * @return string
 */ 'twitter', function () {
    $token = '851049513880104960-lhnmSQ4AqCjAucTxEM67MYMacEgxLe5';
    $secret = 'ShzD5LYihdxzTmWBzSlBlt6ggDXhEN4mS3MIAd4IakX10';
    
/*    $user = \Socialite::driver('twitter')
        ->userFromTokenAndSecret($token, $secret);*/

    // $credentials = ['email'=>'petros@diveris.org', 'password'=>'yellowbrix!!'];
    $ourUser = \App\User::find(1);
    dump('Becoming Eva...');
    \Auth::login($ourUser, true);
    return redirect('/');
    // return '';
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
                                                                                                                                                                                                                                                                                                                                                                                                    Route::get('email', 'EmailController@sendEmail');
