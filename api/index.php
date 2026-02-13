<?php

/*
|--------------------------------------------------------------------------
| Vercel Deployment Helper
|--------------------------------------------------------------------------
| This file routes requests to the Laravel index.php. It also includes
| a helper to run migrations in production if needed.
*/

// Map cache to /tmp for Vercel (Read-only filesystem)
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
}

try {
    if (getenv('RUN_MIGRATIONS_ON_DEPLOY') === 'true') {
        require __DIR__ . '/../vendor/autoload.php';
        $app = require_once __DIR__ . '/../bootstrap/app.php';
        $app->useStoragePath('/tmp');
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->call('migrate', ['--force' => true]);
    }
} catch (\Exception $e) {
    echo "<h1>Migration Error</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    exit(1);
}

require __DIR__ . '/../public/index.php';