<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Route;
use MoonShine\Laravel\Http\Middleware\Authenticate;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function() {
            Route::middleware(['moonshine',Authenticate::class])
                // ->namespace($this->namespace)
                ->group(base_path('routes/moonshine.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            ThrottleRequests::class.':global',
        ]);
        $middleware->validateCsrfTokens(except: [
            'purchase/callback',

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (DomainException $e) {
            flash()->alert($e->getMessage());

            //TODO редиректит на изображение
            return session()->previousUrl()
                ? back()
                : redirect()->route('home');
        });
    })->create();
