<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Web middleware group - PROPER ORDER IS CRITICAL
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);

        // Global middleware - these run on every request
        $middleware->append([
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Configure cookie encryption - CORRECT SYNTAX
        $middleware->encryptCookies(except: [
            'locale', // Don't encrypt locale cookie
        ]);

        // Middleware aliases for route-specific usage
        $middleware->alias([
            'setlocale' => \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();