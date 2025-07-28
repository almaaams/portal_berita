<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Jika file yang diminta ada di folder public, jangan handle oleh Laravel
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

// Jalankan index Laravel
require_once __DIR__.'/public/index.php';