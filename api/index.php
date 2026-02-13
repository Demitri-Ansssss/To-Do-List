<?php

/*
|--------------------------------------------------------------------------
| Vercel Deployment Helper
|--------------------------------------------------------------------------
| This file routes requests to the Laravel index.php. It also includes
| a helper to run migrations in production if needed.
*/

// Map cache to /tmp for Vercel (Read-only filesystem)
$_ENV['ILLUMINATE_BOOTSTRAP_CACHE_PATH'] = '/tmp';
$_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/events.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/routes.php';
$_ENV['APP_SERVICES_PATH'] = '/tmp/services.php';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp';

// Also set via putenv for broader compatibility
putenv('ILLUMINATE_BOOTSTRAP_CACHE_PATH=/tmp');
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_PATH=/tmp/services.php');
putenv('VIEW_COMPILED_PATH=/tmp');

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