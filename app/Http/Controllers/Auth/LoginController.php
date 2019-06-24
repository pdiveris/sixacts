<?php

namespace App\Http\Controllers\Auth;

use App\AuthData;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;


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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(Request $request)
    {
        $provider = str_replace('login/', '', $request->path());
        return \Socialite::driver($provider)->redirect();
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
    public function handleProviderCallback(Request $request)
    {
        $provider = str_replace('login/', '', $request->path());
        $provider = str_replace('/callback', '', $provider);
        
        $user = \Socialite::driver($provider)->user();
        
        $store = new AuthData();
        dump($user);
        
        $store->token = $user->token;
        $store->tokenSecret = $user->tokenSecret;
        $store->theirId = $user->getId();
        $store->nickname = $user->getNickname();
        $store->name = $user->getName();
        $store->email = $user->getEmail();
        $store->avatar = $user->getAvatar();
        
        if (method_exists($user, 'getUser') && null!== $user->getUser()) {
            $store->user = json_encode($user->getUser());
        }
        
        $store->provider = $provider;
        $store->scheme = 'OAuth 1';
        
        // Exception handler to be added..
        $store->save();

        // Symfony console debugger ON please
        if (env('APP_ENV') == 'local' && env('APP_DEBUG') != false) {
            dump($user);
        }
        return '';
        // return redirect('/');
    }
    
    public function handleProviderCallbackGoogle()
    {
        $user = \Socialite::driver('google')->user();
        dd($user);
    }
}
