<?php

declare(strict_types=1);

$configGlobal = [
    // Common settings
    'debug'     => true,
    'log_file'  => 'storage/log/app.log',

    // Session
    'session'   => [
        'cookie_name'       => 'SESSID',
        'cookie_domain'     => null,
        'cookie_path'       => '/',
        'cookie_secure'     => false,
        'cookie_http_only'  => true,
        'lifetime'          => 10800,
        'gc_timestamp_file' => 'storage/cache/session_gc.timestamp',
        'gc_period'         => 3600,
    ],

    // General templates settings
    'templates' => [
        'paths' => [
            'app'     => ['system/templates/app'],
            'error'   => ['system/templates/error'],
            'layouts' => ['system/templates/layouts'],
        ],
    ],
];

/** @var array<array-key, mixed> $configLocal */
$configLocal = file_exists(__DIR__ . '/config.local.php') ? require_once __DIR__ . '/config.local.php' : [];

return array_merge($configGlobal, $configLocal);
