<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

try {
    define('LARAVEL_START', microtime(true));

    // Prepare writable temporary directories for serverless environment
    $storagePath = '/tmp/storage';
    $dirs = [
        $storagePath . '/app/public',
        $storagePath . '/framework/cache/data',
        $storagePath . '/framework/sessions',
        $storagePath . '/framework/testing',
        $storagePath . '/framework/views',
        $storagePath . '/logs',
        '/tmp/bootstrap/cache',
    ];

    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }

    // Register Composer autoloader
    require __DIR__ . '/../vendor/autoload.php';

    // Bootstrap Laravel application
    /** @var Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // Ensure storage operations route to /tmp
    $app->useStoragePath($storagePath);

    // Handle incoming HTTP request
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Laravel Boot / Runtime Error on Vercel:\n";
    echo $e->getMessage() . "\n\n";
    echo 'File: ' . $e->getFile() . ':' . $e->getLine() . "\n\n";
    echo "Trace:\n" . $e->getTraceAsString();
}
