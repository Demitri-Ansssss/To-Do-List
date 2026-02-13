<?php

/*
|--------------------------------------------------------------------------
| Vercel API Entry Point
|--------------------------------------------------------------------------
| This file is the entry point for Vercel. It bootstraps the application
| and handles the request. It also includes a helper to run migrations
| in production if needed.
*/

try {
    if (getenv('RUN_MIGRATIONS_ON_DEPLOY') === 'true') {
        require __DIR__ . '/../vendor/autoload.php';
        $app = require_once __DIR__ . '/../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->call('migrate', ['--force' => true]);
    }
} catch (\Exception $e) {
    echo "<h1>Migration Error</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    exit(1);
}

require __DIR__ . '/../public/index.php';