<?php

/**
 * This file is part of mobicms/mobicms package
 *
 * @see       https://github.com/mobicms/mobicms for the canonical source repository
 * @license   https://github.com/mobicms/mobicms/blob/develop/LICENSE GPL-3.0
 * @copyright https://github.com/mobicms/mobicms/blob/develop/README.md
 */

declare(strict_types=1);

namespace MobicmsTest;

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\PhpFileProvider;
use Mobicms\ConfigProvider;
use Mobicms\ContainerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerFactoryTest extends TestCase
{
    public function testFactoryReturnsContainerObject(): void
    {
        $container = ContainerFactory::getContainer();
        $this->assertInstanceOf(ContainerInterface::class, $container);
    }

    public function testConfigHasNesessaryProviders(): void
    {
        $providers = ContainerFactory::getConfigProviders();
        $this->assertEquals(new ArrayProvider(['config_cache_path' => M_FILE_CONFIG_CACHE]), $providers[0]);
        $this->assertEquals(\Mezzio\ConfigProvider::class, $providers[1]);
        $this->assertEquals(\Mezzio\Helper\ConfigProvider::class, $providers[2]);
        $this->assertEquals(\Mezzio\Router\FastRouteRouter\ConfigProvider::class, $providers[3]);
        $this->assertEquals(\Mezzio\Router\ConfigProvider::class, $providers[4]);
        $this->assertEquals(\Laminas\HttpHandlerRunner\ConfigProvider::class, $providers[5]);
        $this->assertEquals(ConfigProvider::class, $providers[6]);
        $this->assertEquals(new PhpFileProvider((string) M_PATH_CONFIG . 'packages/*.php'), $providers[7]);
        $this->assertEquals(
            new PhpFileProvider((string) M_PATH_CONFIG . 'autoload/{{,*.}global,{,*.}local}.php'),
            $providers[8]
        );
    }
}
