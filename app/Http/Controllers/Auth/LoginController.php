<?php

namespace App\Http\Controllers\Auth;

use App\AuthData;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\User;
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
    protected $redirectTo = '/';

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
     * OAuth 1 and OAuth2 handle, tries to guess based om the existence of
     *      tokenSecret, expiresIm
     * in the response data.
     * Google's response typically doesn't provide refreshToken
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request)
    {
        $provider = str_replace('login/', '', $request->path());
        $provider = str_replace('/callback', '', $provider);
        
        $user = \Socialite::driver($provider)->user();
        
        $store = new AuthData();
        $store->scheme = 'NOAuth';
        
        $store->token = $user->token;
        $store->their_id = $user->getId();
        $store->nickname = $user->getNickname();
        $store->name = $user->getName();
        $store->email = $user->getEmail();
        $store->avatar = $user->getAvatar();
        
        if (property_exists($user, 'tokenSecret')) {
            $store->scheme = 'OAuth 1';
            $store->token_secret = $user->tokenSecret;
        }
    
        if (property_exists($user, 'refreshToken')) {
            $store->refresh_token = $user->refreshToken;
        }
    
        if (property_exists($user, 'expiresIn')) {
            $store->expires_in = $user->expiresIn;
            $store->scheme = 'OAuth2';
        }
    
        if (method_exists($user, 'getUser') && null!== $user->getUser()) {
            $store->user = json_encode($user->getUser());
        }
        
        $store->provider = $provider;
        
        // Exception handler to be added..
        $store->save();

        // Symfony console debugger ON please
        if (env('APP_DEBUG') === true) {
            dump($user);
        }
        
        // sync OAuth user with local repository
        $this->synUser($store);
        
        return redirect('/');
    }
    
    /**
     * Sync local user with the OAuth user they are authenticating as
     * If user doesn't exist create them
     * @TODO: queue the standard emails to be sent regarding new account
     *
     * Login the user
     *
     * @param \App\AuthData $authData
     * @return bool
     */
    public function synUser(AuthData $authData)
    {
        $ret = false;
        $recs = User::where('email', '=',$authData->email)->get();
        
        // check if the user exists
        // if not, create user with random password
        if ( count( $recs ) < 1 ) {
            $ourUser= new User();
            $ourUser->email = $authData->email;
            $ourUser->name = $authData->name;
            
            $password = Utils::generatePassword();
            $ourUser->password = bcrypt($password);
            $ourUser->save();
        } else {
            $ourUser = $recs[0];
        }
        \Auth::login($ourUser);

        return $ret;
    }
    
    /**
     * Delete scheme (initiated at the provider end)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleProviderDeauthorize(Request $request)
    {
        $provider = str_replace('login/', '', $request->path());
        $provider = str_replace('/deauthorize', '', $provider);
        
        $user = \Socialite::driver($provider)->user();
        return redirect('/');
    }

    /**
     * Data Deletion request (initiated at the provider end)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleProviderDelete(Request $request)
    {
        $provider = str_replace('login/', '', $request->path());
        $provider = str_replace('/deauthorize', '', $provider);
        
        $user = \Socialite::driver($provider)->user();
        return redirect('/');
    }
}
