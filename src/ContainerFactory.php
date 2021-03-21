<?php

/**
 * This file is part of mobicms/mobicms package
 *
 * @see       https://github.com/mobicms/mobicms for the canonical source repository
 * @license   https://github.com/mobicms/mobicms/blob/develop/LICENSE GPL-3.0
 * @copyright https://github.com/mobicms/mobicms/blob/develop/README.md
 */

declare(strict_types=1);

namespace Mobicms;

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;
use Laminas\ServiceManager\ServiceManager;

class ContainerFactory
{
    /** @var null|ServiceManager */
    private static $containerInstance;

    public static function getContainer(): ServiceManager
    {
        return null !== self::$containerInstance
            ? self::$containerInstance
            : self::$containerInstance = self::buildContainer(self::getConfig());
    }

    private static function buildContainer(array $config): ServiceManager
    {
        /** @var array<array-key, string> $dbConfig */
        $dbConfig = $config['database'] ?? [];

        /** @var array<array-key, array<array-key, mixed>> $dependencies */
        $dependencies = $config['dependencies'];
        $dependencies['services']['database'] = $dbConfig;
        unset($config['dependencies'], $config['database']);
        $dependencies['services']['config'] = $config;

        return new ServiceManager($dependencies);
    }

    private static function getConfig(): array
    {
        $aggregator = new ConfigAggregator(
            [
                // Include cache configuration
                new ArrayProvider(['config_cache_path' => M_FILE_CONFIG_CACHE]),

                // Include packages configuration
                \Mezzio\ConfigProvider::class,
                \Mezzio\Helper\ConfigProvider::class,
                \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
                \Mezzio\Router\ConfigProvider::class,
                \Laminas\HttpHandlerRunner\ConfigProvider::class,
                ConfigProvider::class,

                // // Load packages configurations
                new PhpFileProvider(M_PATH_CONFIG . 'packages/*.php'),

                // Load application config in a pre-defined order
                new PhpFileProvider(M_PATH_CONFIG . 'autoload/{{,*.}global,{,*.}local}.php'),
            ],
            M_FILE_CONFIG_CACHE
        );
        return $aggregator->getMergedConfig();
    }
}
