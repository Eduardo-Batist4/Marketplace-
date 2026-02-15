<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [
        InvalidCredentialsException::class => 'warning',
        InsufficientQuantityException::class => 'warning',
    ];

    protected $dontReport = [
        ValidationException::class,
        NoItemsInCartException::class,
    ];

    public function register(): void
    {

        $this->renderable(function (HttpExceptionInterface $e, $request) {
            if ($request->expectsJson()) {
                $response = [
                    'error' => $e->getErrorType(),
                    'status' => $e->getStatusCode(),
                    'message' => $e->getMessage(),
                ];

                return response()->json($response, $e->getStatusCode());
            }
        });

        $this->reportable(function (Throwable $e) {
            if ($e instanceof ValidationException) {
                return;
            }

            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }
}
