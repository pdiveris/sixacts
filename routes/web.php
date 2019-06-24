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
    $user = \Socialite::driver('twitter')->user();
    var_dump($user);
    
    // OAuth Two Providers
    $token = $user->token;
    $refreshToken = $user->refreshToken; // not always provided
    $expiresIn = $user->expiresIn;
    
    // OAuth One Providers                                                                                                                                                                                         // OAuth One Providers
    $token = $user->token;
    var_dump($token);
    
    $tokenSecret = $user->tokenSecret;
    var_dump($tokenSecret);
    
    echo '<h3>AllProviders</h3>';
    // All Providers
    echo $user->getId();
    echo '<br/>';

    echo $user->getNickname();
    echo '<br/>';

    echo $user->getName();
    echo '<br/>';

    echo $user->getEmail();
    echo '<br/>';

    echo $user->getAvatar();
    echo '<br/>';

    
});
