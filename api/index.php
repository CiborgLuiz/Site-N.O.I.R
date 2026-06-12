<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "PASSOU 1<br>";

$temporaryPaths = [
    'APP_CONFIG_CACHE' => '/tmp/laravel/cache/config.php',
    'APP_EVENTS_CACHE' => '/tmp/laravel/cache/events.php',
    'APP_PACKAGES_CACHE' => '/tmp/laravel/cache/packages.php',
    'APP_ROUTES_CACHE' => '/tmp/laravel/cache/routes.php',
    'APP_SERVICES_CACHE' => '/tmp/laravel/cache/services.php',
    'VIEW_COMPILED_PATH' => '/tmp/laravel/views',
];

echo "PASSOU 2<br>";

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

echo "PASSOU 3<br>";

require __DIR__.'/../vendor/autoload.php';

echo "PASSOU 4<br>";

$app = require __DIR__.'/../bootstrap/app.php';

echo "PASSOU 5<br>";

require __DIR__.'/../public/index.php';