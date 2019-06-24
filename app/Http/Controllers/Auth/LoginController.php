<?php

namespace App\Http\Controllers\Auth;

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
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = \Socialite::driver('twitter')->user();
    
        var_dump($user);
    
        // OAuth Two Providers
        // $token = $user->token;
        // $refreshToken = $user->refreshToken; // not always provided
        // $expiresIn = $user->expiresIn;
    
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
    }
}
