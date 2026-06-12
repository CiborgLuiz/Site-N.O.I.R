<?php

$temporaryPaths = [
    'APP_CONFIG_CACHE' => '/tmp/laravel/cache/config.php',
    'APP_EVENTS_CACHE' => '/tmp/laravel/cache/events.php',
    'APP_PACKAGES_CACHE' => '/tmp/laravel/cache/packages.php',
    'APP_ROUTES_CACHE' => '/tmp/laravel/cache/routes.php',
    'APP_SERVICES_CACHE' => '/tmp/laravel/cache/services.php',
    'VIEW_COMPILED_PATH' => '/tmp/laravel/views',
];

foreach ($temporaryPaths as $key => $path) {
    if (getenv($key) === false) {
        putenv($key . '=' . $path);
        $_ENV[$key] = $path;
        $_SERVER[$key] = $path;
    }

    $directory = str_ends_with($path, '.php')
        ? dirname($path)
        : $path;

    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
}

require __DIR__.'/../public/index.php';