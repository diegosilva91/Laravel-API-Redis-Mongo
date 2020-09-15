<?php

namespace App\Http\Middleware;

use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

use Closure;

class JWTAuthRefresh extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {

            //$user = JWTAuth::parseToken()->authenticate();
            JWTAuth::parseToken()->authenticate();
            //if( !$user ) throw new Exception('User Not Found');
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'meta' => [
                        'status' => false,
                        'errors' => [
                            'Token invalid'
                        ]
                    ]
                ],401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'meta' => [
                        'status' => false,
                        'errors' => [
                            'Token expired'
                        ]
                    ]
                ]);
            } else {
                if ($e->getMessage() === 'User Not Found') {
                    return response()->json([
                        'meta' => [
                            "status" => false,
                            "errors" => ["User not Found"
                            ]
                        ]
                    ]);
                }
                return response()->json([
                    'meta' => [
                        'success' => false,
                        'errors' => [
                            'message' => 'Token not found or missing',
                        ]
                    ]
                ]);
            }
        }
        return $next($request);

    }
}
