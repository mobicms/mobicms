<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;

// Load configuration
$config = require __DIR__ . '/config.php';

/** @psalm-var array{shared_by_default?: bool} $dependencies */
$dependencies = (array) $config['dependencies'];
$dbConfig = (array) $config['database'];
unset($config['dependencies'], $config['database']);
$dependencies['services']['database'] = $dbConfig;
$dependencies['services']['config'] = $config;

return new ServiceManager($dependencies);
