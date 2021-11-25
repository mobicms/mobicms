<?php

declare(strict_types=1);

namespace Mobicms;

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;
use Laminas\ServiceManager\ServiceManager;

class ContainerFactory
{
    private static ?ServiceManager $containerInstance = null;

    public static function getContainer(): ServiceManager
    {
        if (null === self::$containerInstance) {
            $aggregator = new ConfigAggregator(self::getConfigProviders(), M_FILE_CONFIG_CACHE);
            $config = $aggregator->getMergedConfig();

            /** @var array<string> $dbConfig */
            $dbConfig = $config['database'] ?? [];

            /** @var array[] $dependencies */
            $dependencies = $config['dependencies'];
            $dependencies['services']['database'] = $dbConfig;
            unset($config['dependencies'], $config['database']);
            $dependencies['services']['config'] = $config;
            /** @psalm-suppress InvalidArgument */
            self::$containerInstance = new ServiceManager($dependencies);
        }

        return self::$containerInstance;
    }

    public static function getConfigProviders(): array
    {
        return [
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
            new PhpFileProvider((string) M_PATH_CONFIG . 'packages/*.php'),

            // Load application config in a pre-defined order
            new PhpFileProvider((string) M_PATH_CONFIG . 'autoload/{{,*.}global,{,*.}local}.php'),
        ];
    }
}
