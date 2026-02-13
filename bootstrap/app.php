<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/*
|--------------------------------------------------------------------------
| Vercel Deployment Fix: Handling Read-Only Filesystem
|--------------------------------------------------------------------------
*/
if (isset($_SERVER['VERCEL_URL']) || getenv('VERCEL')) {
    $tmpBootstrap = '/tmp/bootstrap';
    $tmpCache = $tmpBootstrap . '/cache';
    $tmpStorage = '/tmp/storage/framework';
    
    // Ensure directories exist
    foreach ([$tmpCache, $tmpStorage . '/sessions', $tmpStorage . '/views', $tmpStorage . '/cache'] as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
    
    // Copy providers.php if it exists
    if (file_exists(__DIR__ . '/providers.php')) {
        @copy(__DIR__ . '/providers.php', $tmpBootstrap . '/providers.php');
    }
}

$app = Application::configure(basePath: dirname(__DIR__))
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

/*
|--------------------------------------------------------------------------
| Apply Vercel Path Overrides
|--------------------------------------------------------------------------
*/
if (isset($_SERVER['VERCEL_URL']) || getenv('VERCEL')) {
    $app->useBootstrapPath('/tmp/bootstrap');
    $app->useStoragePath('/tmp/storage');
}

return $app;
