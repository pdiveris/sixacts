<?php

namespace App\Http\Controllers\Auth;

use App\AuthData;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return \Socialite::driver('twitter')->redirect();
    }
    
    /**
     * Obtain the user information from Auth provider e.g. GitHub.
     * Redirect to home page with some pop up, possibly Bulma
     *
     * OAuth Two Providers
     *  $token = $user->token;
     *  $refreshToken = $user->refreshToken; // not always provided
     *  $expiresIn = $user->expiresIn;
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = \Socialite::driver('twitter')->user();
    
        $store = new AuthData();
        
        $store->token = $user->token;
        $store->tokenSecret = $user->tokenSecret;
        $store->theirId = $user->getId();
        $store->nickname = $user->getNickname();
        $store->name = $user->getName();
        $store->email = $user->getEmail();
        $store->avatar = $user->getAvatar();
        
        // Symfony console debugger ON please
        if ($this->app->environment() !== 'production' && env('APP_DEBUG') != false) {
            dump($user);
        }
        // add Bulma balloon
        return \Response::redirectTo('/');
    }
}
