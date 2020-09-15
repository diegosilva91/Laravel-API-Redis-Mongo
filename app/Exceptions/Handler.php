<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            $preException = $exception->getPrevious();
            if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['error' => 'token is expired'], 400);
            } elseif ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['error' => 'token is invalid'], 400);
            } elseif ($preException instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
                return response()->json(['error' => 'token absent'], 400);
            } else if ($preException instanceof TokenBlacklistedException) {
                return response()->json([
                        'data' => null,
                        'status' => false,
                        'err_' => [
                            'message' => 'Token Blacklisted',
                            'code' => 1
                        ]
                    ]
                );
            }
            if ($exception->getMessage() === 'Token not provided') {
                return response()->json([
                        'data' => null,
                        'status' => false,
                        'err_' => [
                            'message' => 'Token not provided',
                            'code' => 1
                        ]
                    ]
                );
            }else if( $exception->getMessage() === 'User not found'){
                return response()->json([
                        'data' => null,
                        'status' => false,
                        'err_' => [
                            'message' => 'User Not Found',
                            'code' => 1
                        ]
                    ]
                );
            }
        }
        return parent::render($request, $exception);

    }
}
