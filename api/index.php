<?php

/*
|--------------------------------------------------------------------------
| Runtime setup for serverless environments
|--------------------------------------------------------------------------
|
| Providers such as Vercel deploy the application under /var/task, which is
| read-only at runtime. Laravel still needs writable paths for storage,
| compiled views, logs, and bootstrap cache files, so those paths must point
| to /tmp before the framework is bootstrapped.
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

    $serverlessEnv = [
        'APP_STORAGE_PATH' => $storagePath,
        'VIEW_COMPILED_PATH' => $storagePath . '/framework/views',
        'APP_PACKAGES_CACHE' => $cachePath . '/packages.php',
        'APP_SERVICES_CACHE' => $cachePath . '/services.php',
        'APP_CONFIG_CACHE' => $cachePath . '/config.php',
        'APP_ROUTES_CACHE' => $cachePath . '/routes-v7.php',
        'APP_EVENTS_CACHE' => $cachePath . '/events.php',
        'LOG_CHANNEL' => 'stderr',
        'LOG_STACK' => 'stderr',
        'CACHE_DRIVER' => 'array',
        'SESSION_DRIVER' => 'cookie',
    ];

    foreach ($serverlessEnv as $key => $value) {
        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

if (! empty($_ENV['APP_STORAGE_PATH'])) {
    $app->useStoragePath($_ENV['APP_STORAGE_PATH']);
}

// Handle the request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
