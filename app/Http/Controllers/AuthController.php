<?php

namespace App\Http\Controllers;

use App\Models\AuthData;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Register method
     *
     * @param Request $request request
     *
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
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
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);
        $user = auth()->attempt($credentials);
        if ($user) {
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
                $user = User::where('email', '=', $oAuth->email)->first();
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
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Get an API token for the user currently logged in
     */
    public function getUserToken(): Factory|View|Application
    {
        $user = \Auth::user();
        $token = auth('api')->login($user);
        return view('auth.token', ['token'=>$token]);
    }

    public static function getToken(): ?string
    {
        $user = \Auth::user();
        if ($user) {
            return auth('api')->login($user);
        } else {
            return '';
        }
    }

    /**
     * Logout method
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * The respondWithToken method
     *
     * @param string $token token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
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
