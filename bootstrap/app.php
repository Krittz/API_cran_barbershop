<?php

use App\Http\Middleware\CheckBarbershopPermission;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'barbershop_permission' => \App\Http\Middleware\BarbershopPermission::class,
            'admin_permission' => \App\Http\Middleware\AdminMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => $e instanceof \Illuminate\Http\Response ? $e->getStatusCode() : 500
            ], $e instanceof \Illuminate\Http\Response ? $e->getStatusCode() : 500);
        });
    })
    ->create();
