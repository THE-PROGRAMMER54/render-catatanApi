<?php

use App\Http\Middleware\guestmiddleware;
use App\Http\Middleware\middlewareApi;
use App\Http\Middleware\usermiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(["guest.login" => guestmiddleware::class]);
        $middleware->alias(["user.login" => usermiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
