<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
        ]);

        // The device webhook is called directly by in-bus hardware (GET/POST,
        // no session, no CSRF token available), so it must be exempt.
        $middleware->validateCsrfTokens(except: [
            'webhook/device-status',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
