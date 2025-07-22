<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Http\Middleware\EncryptCookies;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(EncryptCookies::class);         // ✅ Add this
        EncryptCookies::except('locale');   
        
        $middleware->append(AddQueuedCookiesToResponse::class);
        $middleware->append(StartSession::class);

        // SetLocale MUST come before HandleInertiaRequests
        $middleware->append(\App\Http\Middleware\SetLocale::class);
        $middleware->append(\App\Http\Middleware\HandleInertiaRequests::class);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();