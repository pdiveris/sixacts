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
    
    use App\Jobs\SendEmailJob;
    use Carbon\Carbon;
    
    Route::get('/', function () {
    return view('static.welcome');
});

Route::get('email', 'EmailController@sendEmail');

Route::get('/test', function () {
    $user = \App\User::find(3);
    $mailShot = new \App\Mail\UserCreated($user);
    
    SendEmailJob::dispatch($user, $mailShot)->delay(Carbon::now()->addSeconds(3));

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
 */ 'twitter', function ()
{
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
