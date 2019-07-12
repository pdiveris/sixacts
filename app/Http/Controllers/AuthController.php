<?php

namespace App\Http\Controllers;

use App\User;
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
            'password' => bcrypt($request->password),
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
        $token = auth()->attempt($credentials);
        
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return $this->respondWithToken($token);
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
     * @param $token zakoula
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json(
            [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
            ]
        );
    }
}

