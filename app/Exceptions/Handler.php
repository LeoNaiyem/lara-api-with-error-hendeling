<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*') || $request->expectsJson()) {

            // 404 - Model Not Found
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'error' => true,
                    'message' => 'Resource not found.',
                ], 404);
            }

            // 422 - Validation Error
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed.',
                    'errors' => $exception->errors(),
                ], 422);
            }

            // 401 - Unauthenticated
            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'error' => true,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            // 500 - Unexpected error
            if (app()->environment('local')) {
                return response()->json([
                    'error' => true,
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => collect($exception->getTrace())->take(3),
                ], 500);
            }

            // Production: hide error details
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
