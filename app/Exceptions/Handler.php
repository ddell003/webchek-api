<?php

namespace App\Exceptions;

use Carbon\Exceptions\InvalidArgumentException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnprocessableEntityHttpException){
            return response()->json(['message' => 'Unprocessable Entity: ' . $exception->getMessage()], 422);
        }

        if ($exception instanceof InvalidArgumentException) {
            return response()->json(['message' => 'Bad Request: ' . $exception->getMessage()], 400);
        }

        if ($exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        if ($exception instanceof AccountNotSetException) {
            return response()->json(['message' => 'There was an issue in resolving your account.'], 401);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json(['message' => 'Auth Exception'], 401)->withHeaders([
                'Access-Control-Allow-Origin' => '*'
            ]);
        }

        if ($exception instanceof AccessDeniedHttpException) {
            return response()->json(['message' => sprintf('Access Denied: %s', $exception->getMessage())], 403);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json(['message' => sprintf('Access Denied: %s', $exception->getMessage())], 403);
        }

        if ($exception instanceof UnauthorizedException) {
            return response()->json(['message' => sprintf('Access Denied: %s', $exception->getMessage())], 403);
        }

        return parent::render($request, $exception);
    }

}
