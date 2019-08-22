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
    Route::get('/', 'StaticController@home')->name('home');
    Route::get('ssr', 'StaticController@homeRendered')->name('ssr');
    Route::get('plain', 'StaticController@homePlain')->name('plain');
    Route::get('nchan', 'StaticController@nchan')->name('nchan');
    Route::get('plain/vote', 'StaticController@plainVote')->name('plainvote');
    Route::get('full', 'StaticController@homeFull')->name('full');
    Route::get('react', 'StaticController@react')->name('react');
    Route::get('echo', 'EchoController@index')->name('echo');
    Route::get('/propose', 'SiteController@getProposal')->name('propose');
    Route::post('/propose', 'SiteController@postProposal')->name('propose_post');
    Route::get( '/proposal/{slug}', 'ProposalController@view')->name('proposal');
    Route::get( '/pause/', 'SiteController@pause')->name('pause');

    Route::get(
        '/user/profile',
        'SiteController@userProfile'
    )->name('profile')->middleware('verified');
    
    Route::post(
        '/user/profile',
        'SiteController@postUserProfile'
    )->name('post_profile')->middleware('verified');
    
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
        '/campaigner',
        function () {
            \Session::put('campaigner', true);
            return redirect('/');
        }
    );
    
    Route::get(
        '/stop',
        function () {
            \Session::put('campaigner', false);
            return redirect('/');
        }
    );
    
    Route::get('/twitter', function() {
        $twits =  Twitter::getUserTimeline(['screen_name' => 'ActsSix', 'count' => 3, 'format' => 'json']);
        $ttl = env('TWITTER_TTL', 'undefined');
        if ($ttl === 'undefined') {
            \Cache::set('twitter', $twits);
        } else {
            \Cache::set('twitter', $twits, $ttl);
        }
        return '';
    });
    
    Route::get('/sse', function () {
        $user = \App\User::find(1);
        event(new \App\Events\ProposalVotedEvent(
            [
                'message'=>'refresh', 'user'=>'Petros Diveris'
            ], 'messages',
            $user)
        );
        return '';
    });
    
    Route::get('/become/{id}', 'SiteController@become')->middleware('guest');
    
    Route::get('email', 'EmailController@sendEmail');
    
    Route::get('sse/test', 'ServerSideEventsController@test');
    Route::get('sse/server/{token?}', 'ServerSideEventsController@server');
    Route::get('sse/semaphore/{token?}', 'ServerSideEventsController@semaphore');
    Route::get('sse/client/{token?}', 'ServerSideEventsController@client');

    Route::feeds('/feed');
