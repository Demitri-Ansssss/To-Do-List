<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

if (isset($_SERVER['VERCEL_URL'])) {
    $cachePath = '/tmp';
    $keys = [
        'APP_CONFIG_CACHE' => $cachePath . '/config.php',
        'APP_EVENTS_CACHE' => $cachePath . '/events.php',
        'APP_PACKAGES_CACHE' => $cachePath . '/packages.php',
        'APP_ROUTES_CACHE' => $cachePath . '/routes.php',
        'APP_SERVICES_CACHE' => $cachePath . '/services.php',
        'VIEW_COMPILED_PATH' => $cachePath . '/views',
    ];

    foreach ($keys as $key => $value) {
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }

    if (!is_dir($cachePath . '/views')) {
        @mkdir($cachePath . '/views', 0755, true);
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
