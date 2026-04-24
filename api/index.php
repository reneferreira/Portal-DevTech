<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Runtime setup for Vercel
|--------------------------------------------------------------------------
|
| The Vercel serverless filesystem is read-only, except for /tmp during the
| current invocation. We point Laravel's writable paths there and switch a few
| defaults away from file-based drivers to avoid runtime write failures.
|
*/
if (isset($_SERVER['VERCEL']) || getenv('VERCEL')) {
    $tmpPath = sys_get_temp_dir();
    $storagePath = $tmpPath . '/storage';
    $cachePath = $tmpPath . '/bootstrap/cache';

    foreach ([
        $storagePath,
        $storagePath . '/app',
        $storagePath . '/framework',
        $storagePath . '/framework/cache',
        $storagePath . '/framework/cache/data',
        $storagePath . '/framework/sessions',
        $storagePath . '/framework/views',
        $storagePath . '/logs',
        $cachePath,
    ] as $directory) {
        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    $_ENV['APP_STORAGE_PATH'] = $_SERVER['APP_STORAGE_PATH'] = $storagePath;
    $_ENV['VIEW_COMPILED_PATH'] = $_SERVER['VIEW_COMPILED_PATH'] = $storagePath . '/framework/views';
    $_ENV['APP_PACKAGES_CACHE'] = $_SERVER['APP_PACKAGES_CACHE'] = $cachePath . '/packages.php';
    $_ENV['APP_SERVICES_CACHE'] = $_SERVER['APP_SERVICES_CACHE'] = $cachePath . '/services.php';
    $_ENV['APP_CONFIG_CACHE'] = $_SERVER['APP_CONFIG_CACHE'] = $cachePath . '/config.php';
    $_ENV['APP_ROUTES_CACHE'] = $_SERVER['APP_ROUTES_CACHE'] = $cachePath . '/routes-v7.php';
    $_ENV['APP_EVENTS_CACHE'] = $_SERVER['APP_EVENTS_CACHE'] = $cachePath . '/events.php';

    putenv('LOG_CHANNEL=stderr');
    putenv('LOG_STACK=stderr');
    putenv('CACHE_DRIVER=array');
    putenv('SESSION_DRIVER=cookie');

    $_ENV['LOG_CHANNEL'] = $_SERVER['LOG_CHANNEL'] = 'stderr';
    $_ENV['LOG_STACK'] = $_SERVER['LOG_STACK'] = 'stderr';
    $_ENV['CACHE_DRIVER'] = $_SERVER['CACHE_DRIVER'] = 'array';
    $_ENV['SESSION_DRIVER'] = $_SERVER['SESSION_DRIVER'] = 'cookie';
}

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

if (! empty($_ENV['APP_STORAGE_PATH'])) {
    $app->useStoragePath($_ENV['APP_STORAGE_PATH']);
}

// Handle the request
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
