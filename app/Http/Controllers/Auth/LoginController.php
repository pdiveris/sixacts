<?php
/**
 * Login Controller
 *
 * Handles aspects of the login process,
 * including syncing with OAuth data from external providers
 *
 * PHP version 7.2
 *
 * LICENSE: This source file is subject to version 2.0 of the Apache License
 * that is available through the world-wide-web at the following URI:
 * https://www.apache.org/licenses/LICENSE-2.0.
 *
 * @category  Controller
 * @package   Auth
 * @author    Petros Diveris <petros@diveris.org>
 * @copyright 2019 Bentleyworks
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   GIT: $Id$
 * @link      https://github.com/pdiveris/sixproposals/blob/master/app/Http/Controllers/Auth/LoginController.php
 * @see       Six Acts
 */
namespace App\Http\Controllers\Auth;

use App\AuthData;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;
use App\Mail\VariableUserEmail as UserEmail;

/**
 * Class LoginController
 *
 * @category Controller
 * @package  App\Http\Controllers\Auth
 * @author   Petros Diveris <petros@diveris.org>
 * @license  http://www.diveris.org MIT
 * @link     http://www.diveris.org
 */
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
     * @param \Illuminate\Http\Request $request The Request
     *
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
     * @param \Illuminate\Http\Request $request The request
     *
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
     *
     * @param \App\AuthData $authData Authentication data
     *
     * @return bool
     *
     * @TODO: queue the standard emails to be sent regarding new account
     *
     * Login the user
     */
    public function synUser(AuthData $authData)
    {
        $ret = false;
        $recs = User::where('email', '=', $authData->email)->get();
        
        // check if the user exists
        // if not, create user with random password
        if (count($recs) < 1 ) {
            $password = Utils::generatePassword();
            $ourUser= new User(
                [
                    'email'=>$authData->email,
                    'name'=> $authData->name,
                    'password'=>bcrypt($password),
                    'social_avatar'=>$authData->avatar,
                    'social_avatar_large'=>$authData->avatar_original,
                    'verified' => 1, // as agreed, set to 1
                ]
            );
            $ourUser->save();
            $email = new UserEmail($ourUser, 'user_welcome');
            $x = new SendEmailJob($email);
            dispatch($x);
            
        } else {
            $ourUser = $recs[0];
        }
        // authenticate user
        \Auth::login($ourUser);
        return $ret;
    }
    
    /**
     * Delete scheme (initiated at the provider end)
     *
     * @param \Illuminate\Http\Request $request request
     *
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
     * @param \Illuminate\Http\Request $request request
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderDelete(Request $request)
    {
        $provider = str_replace('login/', '', $request->path());
        $provider = str_replace('/deauthorize', '', $provider);
        
        $user = \Socialite::driver($provider)->user();
        return redirect('/');
    }
    
    /**
     * The user has been authenticated.
     * Part of the custom verification email infrastructure as per:
     * @link https://laracasts.com/discuss/channels/laravel/modify-authenticated-method-inside-authenticatesusersphp-class
     * @link https://codebriefly.com/custom-user-email-verification-activation-laravel/
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->verified) {
            $msg = 'You need to confirm your account. We have sent you an activation code, please check your email.';
            auth()->logout();
            return back()->with('warning', $msg);
        }
        return redirect()->intended($this->redirectPath());
    }
}
