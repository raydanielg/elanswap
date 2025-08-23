<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * This file is the entry point for your Laravel application when deployed to cPanel.
 * It redirects all requests to the public directory where the actual Laravel
 * application is located.
 */

$publicPath = __DIR__ . '/public';
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// This allows us to emulate Apache's "mod_rewrite" functionality.
if ($uri !== '/' && file_exists($publicPath . $uri)) {
    return false;
}

require_once $publicPath . '/index.php';
