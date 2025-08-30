<?php
// Check if accessing the root domain - redirect to Next.js homepage
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($request_uri, PHP_URL_PATH);

// If accessing root or homepage, serve Next.js
if ($path === '/' || $path === '/index.php' || $path === '') {
    // Check if index.html exists and serve it
    if (file_exists(__DIR__ . '/index.html')) {
        header('Content-Type: text/html; charset=utf-8');
        readfile(__DIR__ . '/index.html');
        exit();
    }
}

// For Laravel routes (login, register, admin, etc.), continue with Laravel
/**
 * Laravel - A PHP Framework For Web Artisans
 */

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
