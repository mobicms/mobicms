<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

$aggregator = new ConfigAggregator(
    [
        Laminas\Diactoros\ConfigProvider::class,
        Laminas\HttpHandlerRunner\ConfigProvider::class,
        Mezzio\ConfigProvider::class,
        Mezzio\Helper\ConfigProvider::class,
        Mezzio\Router\ConfigProvider::class,
        Mezzio\Router\FastRouteRouter\ConfigProvider::class,
        // Default App module config
        Mobicms\System\ConfigProvider::class,
        Mobicms\DemoApp\ConfigProvider::class,
        // Load application config in a pre-defined order in such a way that local settings
        // overwrite global settings. (Loaded as first to last):
        //   - `global.php`
        //   - `*.global.php`
        //   - `local.php`
        //   - `*.local.php`
        new PhpFileProvider(realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php'),
    ], 'data/cache/config-cache.php'
);

return $aggregator->getMergedConfig();
