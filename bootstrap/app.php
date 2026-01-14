<?php

use App\Responses\ExceptionResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api/routes.php',
        commands: __DIR__.'/../routes/console/routes.php',
        channels: __DIR__.'/../routes/channels/routes.php',
        health: '/up',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(
            renderUsing: static fn (Error $exception, Request $request): JsonResponse => ExceptionResponse::fromError(exception: $exception, request: $request),
        );

        $exceptions->renderable(
            renderUsing: static fn (Exception $exception, Request $request): ExceptionResponse => ExceptionResponse::fromException(exception: $exception),
        );
    })->create();
