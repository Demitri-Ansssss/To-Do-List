<?php

/*
|--------------------------------------------------------------------------
| Vercel Deployment Helper
|--------------------------------------------------------------------------
| This file routes requests to the Laravel index.php. It also includes
| a helper to run migrations in production if needed.
*/

if (getenv('RUN_MIGRATIONS_ON_DEPLOY') === 'true') {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->call('migrate', ['--force' => true]);
}

require __DIR__ . '/../public/index.php';