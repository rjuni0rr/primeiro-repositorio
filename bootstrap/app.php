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
        // colocou uma limitação de validação de metodos POST (csrf)
        $middleware->validateCsrfTokens(except: [
            '/dispenser/get-bundle-data',
            '/dispenser/get-ticket',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
