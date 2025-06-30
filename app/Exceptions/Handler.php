<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return $this->handleApiException($request, $e);
            }
        });
    }

    protected function handleApiException($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
        }
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }
        if ($exception instanceof AuthorizationException || $exception instanceof AccessDeniedHttpException) {
            return response()->json([
                'message' => 'You do not have permission to perform this action.',
            ], 403);
        }
        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Resource not found',
            ], 404);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'message' => 'HTTP method not allowed',
            ], 405);
        }
        if ($exception instanceof ThrottleRequestsException || $exception instanceof TooManyRequestsHttpException) {
            return response()->json([
                'message' => 'Too many requests. Please slow down.',
            ], 429);
        }
        if ($exception instanceof UnprocessableEntityHttpException) {
            return response()->json([
                'message' => 'Unprocessable entity',
            ], 422);
        }
        if ($exception instanceof BadRequestHttpException) {
            return response()->json([
                'message' => 'Bad request',
            ], 400);
        }
        if ($exception instanceof ConflictHttpException) {
            return response()->json([
                'message' => 'Conflict',
            ], 409);
        }
        if ($exception instanceof ServiceUnavailableHttpException) {
            return response()->json([
                'message' => 'Service unavailable',
            ], 503);
        }
        if ($exception instanceof HttpException) {
            return response()->json([
                'message' => $exception->getMessage() ?: 'HTTP error',
            ], $exception->getStatusCode());
        }
        if ($exception instanceof HttpResponseException) {
            return $exception->getResponse();
        }
        return response()->json([
            'message' => 'Server error',
            'error' => config('app.debug') ? $exception->getMessage() : null,
        ], 500);
    }
}
