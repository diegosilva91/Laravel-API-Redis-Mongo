<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Created a new User
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request,[
            'username' => 'required', 'string', 'max:255', 'unique:users',
            'password' => 'required', 'string', 'min:8',
        ]);
        $user =  User::create([
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'user' => $user,
            'created'=>'ok'
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request,['username' => 'required','password'=> 'required']);
        $credentials = $request->only(['username', 'password']);
        $username=$credentials['username'];
        $token = Auth::attempt($credentials);
        if (empty($token)) {
            return response()->json([
                'meta'=>[
                    'success'=>false,
                    'errors'=>["Password incorrect for: $username"]
                ],
                'error' => 'Unauthorized'
            ], 401);
        }
        /*Redis::set('token', $token);*/
        /*$redis = Redis::connection();*/
        $value_cache = Cache::store('redis')->put('token', $token,auth()->factory()->getTTL() * 60* 24);
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    public function payload()
    {
        return response()->json(auth()->payload());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Retrieve the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token)
    {
        return response()->json([
            'meta'=>[
                'success'=>true,
                'errors'=>[]
            ],
            'data' => [
                'token' => $token,
                'minutes_to_expire' => $this->expirationTimeToken()
            ]
        ]);
    }
    public function expirationTimeToken()
    {
        return auth()->factory()->getTTL() * 60;
    }
}
