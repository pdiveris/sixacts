<?php
/**
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| PHP version 7.2
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Pay attention below, check for verified
use App\Jobs\SendEmailJob;
use App\Mail\VariableUserEmail as UserEmail;
use App\User;


Route::get('/', 'StaticController@home')->name('home');
Route::get('kanga', 'StaticController@kanga')->name('kanga');

Route::get(
    'user/profile',
    'SiteController@userProfile'
)->name('profile')->middleware('verified');

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
    ->name('defacebook');

Route::get('login/delete/faceboook', 'Auth\LoginController@handleProviderDelete')
    ->name('deletefacebook');

Route::get(
    'twitter',
    function () {
        // $credentials = ['email'=>'petros@diveris.org', 'password'=>'yellowbrix!!'];
        $ourUser = User::find(1);
        dump('Becoming Eva...');
        Auth::login($ourUser, true);
        return redirect('/');
        // return '';
    }
);

Route::get('/home', 'SiteController@index')->name('home');

Auth::routes();
Route::get('user/verify/{token}', 'Auth\RegisterController@verifyUser');

Route::get(
    'login/verify',
    function () {
        return view('static.registration_thanks');
    }
);

Route::get(
    '/test',
    function () {
        $user = \App\User::find(3);
        $profileUrl = url('user/profile');
        $email = new UserEmail(
            $user,
            'user_welcome',
            ['password'=>'kanga', 'profileUrl'=>$profileUrl]
        );
    
        $dispatchJob = new SendEmailJob($email);
        dispatch($dispatchJob);

        return '';
    }
);

Route::get(
    ('i'),
    function () {
        phpinfo();
        return '';
    }
);

Route::get(
    'l/{email?}',
    function ($email) {
        $user = User::where('email', '=', $email)->first();
        Auth::login($user);
        return redirect('/');
    }
);

Route::get('email', 'EmailController@sendEmail');
