<?php

namespace App\Http\Controllers;

use App\AuthData;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

/**
 * Class AuthController
 *
 * @category Auth
 * @package  App\Http\Controllers
 * @author   Petros Diveris <petros@diveris.org>
 * @license  Apache 2.0
 * @link     https://www.diveris.org
 */
class AuthController extends Controller
{
    use AuthenticatesUsers;
    
    /**
     * Register method
     *
     * @param \Illuminate\Http\Request $request request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $user = User::create(
            [
            'email' => $request->email,
            'password' => \Hash::make($request->password),
            'name'     => $request->name,
            ]
        );
        
        $token = auth()->login($user);
        
        return $this->respondWithToken($token);
    }
    
    /**
     * This is the login
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        
        $credentials = request(['email', 'password']);
        $user = auth()->attempt($credentials);
        if (null !== $user && $user) {
            if (auth()->user()->verified) {
                $token = auth('api')->attempt($credentials);
                if ($token) {
                    return $this->respondWithToken($token);
                }
            } else {
                return response()->json(['error' => 'User unverified'], 401);
            }
        } else {
            $oAuth = AuthData::where('email', '=', request(['email']))
                ->first();
            if (null!==$oAuth) {
                $user = \App\User::where('email', '=', $oAuth->email)->first();
                \Auth::login($user);

                if (auth()->user()->verified) {
                    return $this->respondWithToken($oAuth->token);
                } else {
                    return response()->json(['error' => 'User unverified'], 401);
                }
            }
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    /**
     * Get an API token for the user currently logged in
     */
    public function getUserToken()
    {
        $user = \Auth::user();
        $token = auth('api')->login($user);
        return view('auth.token', ['token'=>$token]);
    }
    
    public static function getToken()
    {
        $user = \Auth::user();
        if ($user) {
            $token = auth('api')->login($user);
            return $token;
        } else {
            return '';
        }
    }
    
    /**
     * Logout method
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        
        return response()->json(['message' => 'Successfully logged out']);
    }
    
    /**
     * The respondWithToken method
     *
     * @param string $token token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json(
            [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        );
    }
}
