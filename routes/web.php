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
    use App\Events\MessagePosted;
    use App\Jobs\SendEmailJob;
    use App\Mail\VariableUserEmail as UserEmail;
    use App\User;

    Route::get(
        'pako',
        function (\Illuminate\Http\Request $request) {
            echo '<h3>Stuff</h3>';
            var_dump($request->headers);
        }
    );

    Route::get('/', 'StaticController@home')->name('home');
    Route::get('ssr', 'StaticController@homeRendered')->name('ssr');
    Route::get('plain', 'StaticController@homePlain')->name('plain');
    Route::get('full', 'StaticController@homeFull')->name('full');
    Route::get('react', 'StaticController@react')->name('react');
    Route::get('echo', 'StaticController@react')->name('echo');
    Route::get('/propose', 'SiteController@getProposal')->name('propose');
    Route::post('/propose', 'SiteController@postProposal')->name('propose_post');
    Route::get('proposal/{slug}', 'ProposalController@view')->name('proposal');

    Route::get(
        'user/profile',
        'SiteController@userProfile'
    )->name('profile')->middleware('verified');
    
    Route::get('user/token', 'AuthController@getUserToken')->name('user_token');
    
    Route::get('message', 'MessageController@showMessageForm');
    Route::post('message', 'MessageController@post')->name('postmessage');
    
    Route::get('categories', 'StaticController@content')->name('categories_explained');
    Route::get('nine_rules', 'StaticController@content')->name('nine_rules');
    
    Route::get('forum', 'StaticController@forum')->name('forum');
    Route::get('terms', 'StaticController@content')->name('terms');
    Route::get('privacy', 'StaticController@content')->name('privacy');
    Route::get('gdpr', 'StaticController@content')->name('gdpr');
    Route::get('about', 'StaticController@content')->name('about');
    Route::get('intro', 'StaticController@content')->name('splash');
    
    
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
            // event(new \App\Events\PublicMessage(\Auth::user(), 'refresh'));
            $user = \App\User::find(1);
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
    
    Route::get('sse/test', 'ServerSideEventsController@test');
    Route::get('sse/server/{token?}', 'ServerSideEventsController@server');
    Route::get('sse/semaphore/{token?}', 'ServerSideEventsController@semaphore');
    Route::get('sse/client/{token?}', 'ServerSideEventsController@client');
